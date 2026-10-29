<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LocalOrder;
use App\Models\ProcurementRequest;
use App\Models\DeliveryOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExecutionController extends Controller
{
    /**
     * B1: Display PTJ Active Orders.
     *
     * Approved_Bendahari:
     * Ready for Local Order preparation.
     *
     * LO_Generated:
     * Local Order generated and the order remains active
     * while waiting for physical vendor delivery.
     */
    public function activeOrders(Request $request): View
    {
        $user = auth()->user();

        $stage = $request->query('stage', 'all');

        $search = trim(
            (string) $request->query('search', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Base Active Order Query
        |--------------------------------------------------------------------------
        |
        | Only orders belonging to the logged-in PTJ are visible.
        |
        */
        $baseQuery = ProcurementRequest::query()
            ->where('ptj_id', $user->ptj_id)
            ->whereIn('request_status', [
                'Approved_Bendahari',
                'LO_Generated',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Summary Counts
        |--------------------------------------------------------------------------
        */
        $totalActiveOrders = (clone $baseQuery)->count();

        $readyForLO = (clone $baseQuery)
            ->where(
                'request_status',
                'Approved_Bendahari'
            )
            ->count();

        $generatedLO = (clone $baseQuery)
            ->where(
                'request_status',
                'LO_Generated'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Stage Filter
        |--------------------------------------------------------------------------
        */
        if ($stage === 'ready') {

            $baseQuery->where(
                'request_status',
                'Approved_Bendahari'
            );

        } elseif ($stage === 'generated') {

            $baseQuery->where(
                'request_status',
                'LO_Generated'
            );

        } else {

            $stage = 'all';
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        |
        | Search by:
        | - Request title
        | - Request ID
        | - Winning vendor company name
        |
        */
        if ($search !== '') {

            $baseQuery->where(function ($query) use ($search) {

                $query->where(
                    'request_title',
                    'like',
                    '%' . $search . '%'
                );


                /*
                |--------------------------------------------------------------
                | Request ID Search
                |--------------------------------------------------------------
                |
                | Supports searches such as:
                | 15
                | REQ-0015
                |
                */
                if (
                    preg_match(
                        '/(\d+)/',
                        $search,
                        $matches
                    )
                ) {
                    $query->orWhere(
                        'procurement_request_id',
                        (int) $matches[1]
                    );
                }


                /*
                |--------------------------------------------------------------
                | Winning Vendor Search
                |--------------------------------------------------------------
                */
                $query->orWhereHas(
                    'selectedQuotation.vendor',
                    function ($vendorQuery) use ($search) {

                        $vendorQuery->where(
                            'company_name',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Retrieve Active Orders
        |--------------------------------------------------------------------------
        */
        $activeOrders = $baseQuery
            ->with([
                'selectedQuotation.vendor',
                'localOrder',
            ])
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();


        return view(
            'execution.activeOrder',
            compact(
                'activeOrders',
                'totalActiveOrders',
                'readyForLO',
                'generatedLO',
                'stage',
                'search'
            )
        );
    }


    /**
     * B1: Display the pre-filled Draft Local Order.
     */
    public function draftLO(ProcurementRequest $procurementRequest): View|RedirectResponse
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | PTJ Ownership Check
        |--------------------------------------------------------------------------
        |
        | Prevent a PTJ staff member from accessing another PTJ's order by
        | manually changing the procurement request ID in the URL.
        |
        */
        abort_unless(
            (int) $procurementRequest->ptj_id === (int) $user->ptj_id,
            403,
            'You are not authorized to access this order.'
        );


        /*
        |--------------------------------------------------------------------------
        | Load Required Local Order Data
        |--------------------------------------------------------------------------
        */
        $procurementRequest->load([
            'ptjDepartment',
            'user',
            'items',
            'selectedQuotation.vendor',
            'localOrder',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Existing Local Order
        |--------------------------------------------------------------------------
        |
        | If this request already has a generated Local Order, PTJ should
        | view the existing official LO instead of generating a duplicate.
        |
        */
        if ($procurementRequest->localOrder) {
            return redirect()->route(
                'ptj.orders.local-order',
                $procurementRequest->localOrder
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Validation
        |--------------------------------------------------------------------------
        |
        | Only requests that successfully received final Bendahari approval
        | are allowed to enter the Draft LO stage.
        |
        */
        abort_unless(
            $procurementRequest->request_status === 'Approved_Bendahari',
            409,
            'This procurement request is not ready for Local Order generation.'
        );


        /*
        |--------------------------------------------------------------------------
        | Winning Quotation Validation
        |--------------------------------------------------------------------------
        */
        abort_if(
            !$procurementRequest->selected_quotation_id
            || !$procurementRequest->selectedQuotation,
            409,
            'The approved winning quotation could not be found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Ensure Winning Quotation Belongs to This Request
        |--------------------------------------------------------------------------
        */
        abort_unless(
            (int) $procurementRequest->selectedQuotation->procurement_request_id
                === (int) $procurementRequest->procurement_request_id,
            409,
            'The selected quotation does not belong to this procurement request.'
        );


        /*
        |--------------------------------------------------------------------------
        | Approved Amount Validation
        |--------------------------------------------------------------------------
        */
        abort_if(
            $procurementRequest->approved_amount === null,
            409,
            'The approved amount is unavailable for this procurement request.'
        );


        /*
        |--------------------------------------------------------------------------
        | Request Item Validation
        |--------------------------------------------------------------------------
        */
        abort_if(
            $procurementRequest->items->isEmpty(),
            409,
            'No procurement items were found for this request.'
        );


        return view(
            'execution.draftLO',
            compact('procurementRequest')
        );
    }


    /**
     * B1: Validate the Draft LO and generate the official Local Order PDF.
     */
    public function generateLO(
        Request $request,
        ProcurementRequest $procurementRequest
    ): RedirectResponse {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | PTJ Ownership Check
        |--------------------------------------------------------------------------
        */
        abort_unless(
            (int) $procurementRequest->ptj_id === (int) $user->ptj_id,
            403,
            'You are not authorized to generate a Local Order for this request.'
        );


        /*
        |--------------------------------------------------------------------------
        | Load Current Database Values
        |--------------------------------------------------------------------------
        */
        $procurementRequest->load([
            'ptjDepartment',
            'user',
            'items',
            'selectedQuotation.vendor',
            'localOrder',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Basic Workflow Validation
        |--------------------------------------------------------------------------
        */
        if ($procurementRequest->request_status !== 'Approved_Bendahari') {
            return back()->with(
                'error',
                'Only procurement requests with final Bendahari approval can generate a Local Order.'
            );
        }


        if ($procurementRequest->localOrder) {
            return redirect()
                ->route(
                    'ptj.orders.local-order',
                    $procurementRequest->localOrder
                )
                ->with(
                    'info',
                    'A Local Order has already been generated for this procurement request.'
                );
        }


        if (
            !$procurementRequest->selected_quotation_id
            || !$procurementRequest->selectedQuotation
        ) {
            return back()->with(
                'error',
                'The approved winning quotation could not be found.'
            );
        }


        if (
            (int) $procurementRequest->selectedQuotation->procurement_request_id
            !== (int) $procurementRequest->procurement_request_id
        ) {
            abort(
                409,
                'The selected quotation does not belong to this procurement request.'
            );
        }


        if ($procurementRequest->approved_amount === null) {
            return back()->with(
                'error',
                'The approved amount is unavailable.'
            );
        }


        if ($procurementRequest->items->isEmpty()) {
            return back()->with(
                'error',
                'No procurement items were found.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Editable LO Unit Prices
        |--------------------------------------------------------------------------
        |
        | Item name, description and quantity come from the original request
        | and are not trusted from the browser.
        |
        | PTJ may only correct the unit price when the pre-filled itemized
        | amount does not match the approved winning quotation amount.
        |
        */
        $validated = $request->validate([
            'unit_prices' => [
                'required',
                'array',
            ],

            'unit_prices.*' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],
        ], [
            'unit_prices.required' =>
                'Local Order item prices are required.',

            'unit_prices.*.required' =>
                'Each Local Order item must have a unit price.',

            'unit_prices.*.numeric' =>
                'Each unit price must be a valid number.',

            'unit_prices.*.min' =>
                'Unit price cannot be negative.',

            'unit_prices.*.decimal' =>
                'Unit prices may contain a maximum of two decimal places.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Build Final LO Itemization
        |--------------------------------------------------------------------------
        |
        | Monetary calculations are performed using cents to avoid normal
        | floating-point comparison problems.
        |
        */
        $loItems = [];

        $itemizedTotalCents = 0;


        foreach ($procurementRequest->items as $item) {

            if (
                !array_key_exists(
                    $item->request_item_id,
                    $validated['unit_prices']
                )
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'A unit price is missing for one or more Local Order items.'
                    );
            }


            $unitPrice = (float)
                $validated['unit_prices'][$item->request_item_id];


            $unitPriceCents = (int) round($unitPrice * 100);


            $quantity = (int) $item->quantity;


            $lineTotalCents = $unitPriceCents * $quantity;


            $itemizedTotalCents += $lineTotalCents;


            $loItems[] = [
                'request_item_id' => $item->request_item_id,

                'item_name' => $item->item_name,

                'item_description' => $item->item_description,

                'quantity' => $quantity,

                'unit_price' => $unitPriceCents / 100,

                'line_total' => $lineTotalCents / 100,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Approved Amount Validation
        |--------------------------------------------------------------------------
        |
        | Documented UC-500 Alternative Flow A1:
        |
        | Itemized total MUST equal the approved amount.
        |
        */
        $approvedAmountCents = (int) round(
            ((float) $procurementRequest->approved_amount) * 100
        );


        if ($itemizedTotalCents !== $approvedAmountCents) {

            return back()
                ->withInput()
                ->withErrors([
                    'lo_total' =>
                        'Itemized total must match Approved Amount. '
                        . 'Current itemized total: RM '
                        . number_format(
                            $itemizedTotalCents / 100,
                            2
                        )
                        . '. Approved Amount: RM '
                        . number_format(
                            $approvedAmountCents / 100,
                            2
                        )
                        . '.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate LO Identification
        |--------------------------------------------------------------------------
        |
        | Phase 1 requires an LO number but does not define its exact format.
        |
        | Implementation format:
        | LO-{YEAR}-{PROCUREMENT REQUEST ID}
        |
        | Example:
        | LO-2026-000015
        |
        | Using the procurement request ID prevents duplicate sequence numbers.
        |
        */
        $loNumber =
            'LO-'
            . now()->format('Y')
            . '-'
            . str_pad(
                (string) $procurementRequest->procurement_request_id,
                6,
                '0',
                STR_PAD_LEFT
            );


        $loDate = now()->toDateString();


        $totalAmount = $itemizedTotalCents / 100;


        /*
        |--------------------------------------------------------------------------
        | Generate Official LO PDF
        |--------------------------------------------------------------------------
        |
        | localOrder.blade.php is the documented Local Order template.
        |
        */
        $pdf = Pdf::loadView(
            'execution.localOrder',
            [
                'procurementRequest' => $procurementRequest,

                'selectedQuotation' =>
                    $procurementRequest->selectedQuotation,

                'vendor' =>
                    $procurementRequest->selectedQuotation->vendor,

                'loItems' => $loItems,

                'loNumber' => $loNumber,

                'loDate' => $loDate,

                'totalAmount' => $totalAmount,
            ]
        )->setPaper('a4', 'portrait');


        $pdfContents = $pdf->output();


        /*
        |--------------------------------------------------------------------------
        | File Storage Path
        |--------------------------------------------------------------------------
        */
        $fileName = $loNumber . '.pdf';

        $filePath = 'local_orders/' . $fileName;


        /*
        |--------------------------------------------------------------------------
        | Save LO + Update Workflow Atomically
        |--------------------------------------------------------------------------
        */
        $storedFilePath = null;


        try {

            $localOrder = DB::transaction(function () use (
                $procurementRequest,
                $user,
                $loNumber,
                $loDate,
                $totalAmount,
                $filePath,
                $pdfContents,
                &$storedFilePath
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock Request During Final Generation
                |--------------------------------------------------------------------------
                |
                | Prevent two simultaneous Generate button submissions from
                | producing duplicate Local Orders.
                |
                */
                $lockedRequest = ProcurementRequest::query()
                    ->where(
                        'procurement_request_id',
                        $procurementRequest->procurement_request_id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                abort_unless(
                    (int) $lockedRequest->ptj_id
                        === (int) $user->ptj_id,
                    403,
                    'You are not authorized to generate this Local Order.'
                );


                abort_unless(
                    $lockedRequest->request_status
                        === 'Approved_Bendahari',
                    409,
                    'This procurement request is no longer available for Local Order generation.'
                );


                $existingLocalOrder = LocalOrder::query()
                    ->where(
                        'procurement_request_id',
                        $lockedRequest->procurement_request_id
                    )
                    ->first();


                abort_if(
                    $existingLocalOrder,
                    409,
                    'A Local Order has already been generated for this procurement request.'
                );


                /*
                |--------------------------------------------------------------------------
                | Store Official PDF
                |--------------------------------------------------------------------------
                */
                Storage::disk('public')->put(
                    $filePath,
                    $pdfContents
                );


                $storedFilePath = $filePath;


                /*
                |--------------------------------------------------------------------------
                | Create Local Order
                |--------------------------------------------------------------------------
                */
                $localOrder = LocalOrder::create([
                    'procurement_request_id' =>
                        $lockedRequest->procurement_request_id,

                    'selected_quotation_id' =>
                        $lockedRequest->selected_quotation_id,

                    'lo_number' =>
                        $loNumber,

                    'lo_date' =>
                        $loDate,

                    'total_amount' =>
                        $totalAmount,

                    'lo_file_path' =>
                        $filePath,

                    'lo_status' =>
                        'Generated',
                ]);


                /*
                |--------------------------------------------------------------------------
                | Update Procurement Workflow
                |--------------------------------------------------------------------------
                */
                $lockedRequest->update([
                    'request_status' =>
                        'LO_Generated',
                ]);


                /*
                |--------------------------------------------------------------------------
                | Audit Log
                |--------------------------------------------------------------------------
                */
                AuditLog::create([
                    'procurement_request_id' =>
                        $lockedRequest->procurement_request_id,

                    'user_id' =>
                        $user->user_id,

                    'action' =>
                        'PTJ Generated Local Order',

                    'description' =>
                        'PTJ generated Local Order '
                        . $loNumber
                        . ' with approved amount RM '
                        . number_format($totalAmount, 2)
                        . '. Request status updated from '
                        . 'Approved_Bendahari to LO_Generated.',
                ]);


                return $localOrder;
            });


        } catch (\Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Clean Up PDF if Database Transaction Fails
            |--------------------------------------------------------------------------
            */
            if (
                $storedFilePath
                && Storage::disk('public')->exists($storedFilePath)
            ) {
                Storage::disk('public')->delete($storedFilePath);
            }


            throw $exception;
        }


        /*
        |--------------------------------------------------------------------------
        | Generation Completed
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'ptj.orders.local-order',
                $localOrder
            )
            ->with(
                'success',
                'Local Order '
                . $loNumber
                . ' was generated successfully.'
            );
    }


    /**
     * Display the already-generated official Local Order PDF.
     */
    public function showLocalOrder(
        LocalOrder $localOrder
    ): BinaryFileResponse {
        $user = auth()->user();


        $localOrder->load([
            'procurementRequest',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PTJ Ownership Check
        |--------------------------------------------------------------------------
        */
        abort_unless(
            (int) $localOrder->procurementRequest->ptj_id
                === (int) $user->ptj_id,
            403,
            'You are not authorized to view this Local Order.'
        );


        /*
        |--------------------------------------------------------------------------
        | PDF Availability Check
        |--------------------------------------------------------------------------
        */
        abort_if(
            !$localOrder->lo_file_path
            || !Storage::disk('public')->exists(
                $localOrder->lo_file_path
            ),
            404,
            'The generated Local Order PDF could not be found.'
        );


        $absolutePath = Storage::disk('public')->path(
            $localOrder->lo_file_path
        );


        /*
        |--------------------------------------------------------------------------
        | Open PDF Inside Browser
        |--------------------------------------------------------------------------
        |
        | Browser PDF viewers normally provide their own print/download
        | controls, so we do not need another PDF generation process.
        |
        */
        return response()->file(
            $absolutePath,
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="'
                    . $localOrder->lo_number
                    . '.pdf"',
            ]
        );
    }

    /**
     * Display the PTJ delivery confirmation form.
     */
    public function confirmDelivery(
        LocalOrder $localOrder
    ): View|RedirectResponse {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Load Related Procurement Data
        |--------------------------------------------------------------------------
        */

        $localOrder->load([
            'procurementRequest.ptjDepartment',
            'procurementRequest.items',
            'selectedQuotation.vendor',
            'deliveryOrder',
        ]);


        $procurementRequest =
            $localOrder->procurementRequest;

        $selectedQuotation =
            $localOrder->selectedQuotation;

        $vendor =
            $selectedQuotation?->vendor;


        /*
        |--------------------------------------------------------------------------
        | PTJ Ownership Protection
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$procurementRequest
            || (int) $procurementRequest->ptj_id
                !== (int) $user->ptj_id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Delivery Can Only Be Confirmed After LO Generation
        |--------------------------------------------------------------------------
        */

        if (
            $procurementRequest->request_status !== 'LO_Generated'
            || $localOrder->lo_status !== 'Generated'
        ) {

            return redirect()
                ->route('ptj.orders.index')
                ->with(
                    'error',
                    'Delivery can only be confirmed for a generated Local Order.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Delivery Record
        |--------------------------------------------------------------------------
        */

        if ($localOrder->deliveryOrder) {

            return redirect()
                ->route('ptj.orders.index')
                ->with(
                    'error',
                    'Delivery has already been recorded for this Local Order.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Required Relationship Validation
        |--------------------------------------------------------------------------
        */

        if (!$selectedQuotation || !$vendor) {

            return redirect()
                ->route('ptj.orders.index')
                ->with(
                    'error',
                    'The approved vendor information could not be found.'
                );
        }


        return view(
            'execution.orderDetails',
            compact(
                'localOrder',
                'procurementRequest',
                'selectedQuotation',
                'vendor'
            )
        );
    }

    /**
     * Store the PTJ delivery confirmation,
     * Delivery Order, invoice and vendor rating.
     */
    public function storeDelivery(
        Request $request,
        LocalOrder $localOrder
    ): RedirectResponse {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Validate PTJ Input
        |--------------------------------------------------------------------------
        |
        | Phase 1 requires PDF delivery documentation.
        |
        | The 5 MB limit is an implementation safeguard for this university
        | prototype. It is not a Phase 1 business rule.
        |
        */

        $validated = $request->validate(
            [
                'delivery_date' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                ],

                'do_number' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'do_pdf' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:5120',
                ],

                'invoice_pdf' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:5120',
                ],

                'vendor_rating' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:5',
                ],

                'delivery_comments' => [
                    'nullable',
                    'string',
                ],
            ],
            [
                'delivery_date.required' =>
                    'Actual Delivery Date is required.',

                'delivery_date.before_or_equal' =>
                    'Delivery Date cannot be in the future.',

                'do_number.required' =>
                    'Delivery Order Number is required.',

                'do_pdf.required' =>
                    'Delivery Order PDF is required.',

                'do_pdf.mimes' =>
                    'Delivery Order must be a PDF file.',

                'do_pdf.max' =>
                    'Delivery Order PDF must not exceed 5 MB.',

                'invoice_pdf.required' =>
                    'Vendor Invoice PDF is required.',

                'invoice_pdf.mimes' =>
                    'Vendor Invoice must be a PDF file.',

                'invoice_pdf.max' =>
                    'Vendor Invoice PDF must not exceed 5 MB.',

                'vendor_rating.required' =>
                    'Vendor Rating is required.',

                'vendor_rating.integer' =>
                    'Vendor Rating must be between 1 and 5.',

                'vendor_rating.min' =>
                    'Vendor Rating must be at least 1.',

                'vendor_rating.max' =>
                    'Vendor Rating cannot exceed 5.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Prepare Safe File Names
        |--------------------------------------------------------------------------
        */

        $doFilePath = null;

        $invoiceFilePath = null;


        try {

            /*
            |--------------------------------------------------------------------------
            | Store Uploaded Documents
            |--------------------------------------------------------------------------
            */

            $doFileName =
                'DO-'
                . $localOrder->local_order_id
                . '-'
                . Str::uuid()
                . '.pdf';


            $invoiceFileName =
                'INV-'
                . $localOrder->local_order_id
                . '-'
                . Str::uuid()
                . '.pdf';


            $doFilePath =
                $request
                    ->file('do_pdf')
                    ->storeAs(
                        'delivery_orders/do',
                        $doFileName,
                        'public'
                    );


            $invoiceFilePath =
                $request
                    ->file('invoice_pdf')
                    ->storeAs(
                        'delivery_orders/invoices',
                        $invoiceFileName,
                        'public'
                    );


            /*
            |--------------------------------------------------------------------------
            | Database Transaction
            |--------------------------------------------------------------------------
            */

            DB::transaction(
                function () use (
                    $localOrder,
                    $user,
                    $validated,
                    $doFilePath,
                    $invoiceFilePath
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock Local Order
                    |--------------------------------------------------------------------------
                    |
                    | Prevent two browser submissions from creating the same delivery
                    | record at the same time.
                    |
                    */

                    $lockedLocalOrder =
                        LocalOrder::where(
                            'local_order_id',
                            $localOrder->local_order_id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();


                    $lockedLocalOrder->load([
                        'procurementRequest',
                        'selectedQuotation.vendor',
                        'deliveryOrder',
                    ]);


                    $procurementRequest =
                        $lockedLocalOrder
                            ->procurementRequest;


                    $selectedQuotation =
                        $lockedLocalOrder
                            ->selectedQuotation;


                    $vendor =
                        $selectedQuotation?->vendor;


                    /*
                    |--------------------------------------------------------------------------
                    | Re-check PTJ Ownership
                    |--------------------------------------------------------------------------
                    */

                    abort_if(
                        !$procurementRequest
                        || (int) $procurementRequest->ptj_id
                            !== (int) $user->ptj_id,
                        403
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Re-check Workflow State
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $procurementRequest->request_status
                            !== 'LO_Generated'
                        || $lockedLocalOrder->lo_status
                            !== 'Generated'
                    ) {

                        throw new \RuntimeException(
                            'This Local Order is no longer available for delivery confirmation.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Duplicate Protection
                    |--------------------------------------------------------------------------
                    */

                    if ($lockedLocalOrder->deliveryOrder) {

                        throw new \RuntimeException(
                            'Delivery has already been recorded for this Local Order.'
                        );
                    }


                    if (!$vendor) {

                        throw new \RuntimeException(
                            'The approved vendor could not be identified.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Create Delivery Record
                    |--------------------------------------------------------------------------
                    |
                    | Normal successful delivery is matched against the generated LO.
                    |
                    */

                    DeliveryOrder::create(
                        [
                            'local_order_id' =>
                                $lockedLocalOrder
                                    ->local_order_id,

                            'user_id' =>
                                $user->user_id,

                            'do_number' =>
                                trim($validated['do_number']),

                            'do_pdf_path' =>
                                $doFilePath,

                            'invoice_pdf_path' =>
                                $invoiceFilePath,

                            'payment_status' =>
                                'Pending',

                            'delivery_date' =>
                                $validated['delivery_date'],

                            'vendor_rating' =>
                                (int) $validated['vendor_rating'],

                            'delivery_comments' =>
                                isset(
                                    $validated['delivery_comments']
                                )
                                    ? trim(
                                        $validated[
                                            'delivery_comments'
                                        ]
                                    )
                                    : null,

                            'delivery_order_status' =>
                                'Matched',
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Update Local Order
                    |--------------------------------------------------------------------------
                    */

                    $lockedLocalOrder->update(
                        [
                            'lo_status' =>
                                'Delivered',
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Update Procurement Request
                    |--------------------------------------------------------------------------
                    |
                    | Based on the settled Phase 1 B2 workflow:
                    | successful PTJ delivery documentation completes this request stage,
                    | while payment remains Pending for Bendahari.
                    |
                    */

                    $procurementRequest->update(
                        [
                            'request_status' =>
                                'Completed',
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Recalculate Vendor Average Rating
                    |--------------------------------------------------------------------------
                    |
                    | delivery_orders.vendor_rating:
                    | individual PTJ score = integer 1–5
                    |
                    | vendors.vendor_rating:
                    | average score = decimal(3,2)
                    |
                    */

                    $averageVendorRating =
                        DeliveryOrder::whereHas(
                            'localOrder.selectedQuotation',
                            function ($query) use ($vendor) {

                                $query->where(
                                    'vendor_id',
                                    $vendor->vendor_id
                                );
                            }
                        )
                            ->avg('vendor_rating');


                    $vendor->update(
                        [
                            'vendor_rating' =>
                                round(
                                    (float)
                                    $averageVendorRating,
                                    2
                                ),
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Audit Log
                    |--------------------------------------------------------------------------
                    */

                    AuditLog::create(
                        [
                            'procurement_request_id' =>
                                $procurementRequest
                                    ->procurement_request_id,

                            'user_id' =>
                                $user->user_id,

                            'action' =>
                                'PTJ Confirmed Delivery',

                            'description' =>
                                'PTJ confirmed delivery for Local Order '
                                . $lockedLocalOrder->lo_number
                                . ' using Delivery Order '
                                . trim($validated['do_number'])
                                . '. DO and invoice documents were uploaded, '
                                . 'vendor rating '
                                . (int) $validated['vendor_rating']
                                . '/5 was recorded, payment status set to Pending, '
                                . 'and request status updated from LO_Generated to Completed.',
                        ]
                    );
                }
            );


        } catch (\Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Remove Stored Files if Database Operation Failed
            |--------------------------------------------------------------------------
            */

            if (
                $doFilePath
                && Storage::disk('public')
                    ->exists($doFilePath)
            ) {

                Storage::disk('public')
                    ->delete($doFilePath);
            }


            if (
                $invoiceFilePath
                && Storage::disk('public')
                    ->exists($invoiceFilePath)
            ) {

                Storage::disk('public')
                    ->delete($invoiceFilePath);
            }


            throw $exception;
        }


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('ptj.orders.index')
            ->with(
                'success',
                'Delivery confirmed successfully. The Delivery Order and invoice have been recorded and sent for payment processing.'
            );
    }

    /**
     * Display Bendahari pending payment records.
     */
    public function pendingPayments(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->query('search', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Base Pending Payment Query
        |--------------------------------------------------------------------------
        |
        | Phase 1 B3:
        | Bendahari sees records where payment_status = Pending.
        |
        */

        $query = DeliveryOrder::query()
            ->where(
                'payment_status',
                'Pending'
            )
            ->with([
                'user',

                'localOrder.procurementRequest.ptjDepartment',

                'localOrder.selectedQuotation.vendor',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(
                function ($paymentQuery) use ($search) {

                    /*
                    |--------------------------------------------------------------
                    | Delivery Order Number
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->where(
                        'do_number',
                        'like',
                        '%' . $search . '%'
                    );


                    /*
                    |--------------------------------------------------------------
                    | Local Order Number
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder',
                        function ($localOrderQuery) use ($search) {

                            $localOrderQuery->where(
                                'lo_number',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );


                    /*
                    |--------------------------------------------------------------
                    | Procurement Request
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder.procurementRequest',
                        function ($requestQuery) use ($search) {

                            $requestQuery->where(
                                'request_title',
                                'like',
                                '%' . $search . '%'
                            );


                            /*
                            |------------------------------------------------------
                            | Allow REQ-0002 or just 2
                            |------------------------------------------------------
                            */

                            $numericSearch =
                                preg_replace(
                                    '/\D/',
                                    '',
                                    $search
                                );


                            if (
                                $numericSearch !== ''
                                && ctype_digit($numericSearch)
                            ) {

                                $requestQuery->orWhere(
                                    'procurement_request_id',
                                    (int) $numericSearch
                                );
                            }
                        }
                    );


                    /*
                    |--------------------------------------------------------------
                    | Vendor
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder.selectedQuotation.vendor',
                        function ($vendorQuery) use ($search) {

                            $vendorQuery->where(
                                'company_name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );


                    /*
                    |--------------------------------------------------------------
                    | PTJ Department
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder.procurementRequest.ptjDepartment',
                        function ($ptjQuery) use ($search) {

                            $ptjQuery->where(
                                'department_name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pending Payment Summary
        |--------------------------------------------------------------------------
        */

        $totalPendingPayments =
            DeliveryOrder::where(
                'payment_status',
                'Pending'
            )->count();


        $totalPendingAmount =
            DeliveryOrder::where(
                'delivery_orders.payment_status',
                'Pending'
            )
                ->join(
                    'local_orders',
                    'delivery_orders.local_order_id',
                    '=',
                    'local_orders.local_order_id'
                )
                ->sum(
                    'local_orders.total_amount'
                );


        /*
        |--------------------------------------------------------------------------
        | Payment Records
        |--------------------------------------------------------------------------
        */

        $pendingPayments =
            $query
                ->latest('created_at')
                ->paginate(10)
                ->withQueryString();


        return view(
            'execution.pendingPayment',
            compact(
                'pendingPayments',
                'totalPendingPayments',
                'totalPendingAmount',
                'search'
            )
        );
    }

    /**
     * Display Bendahari processed payment history.
     */
    public function paymentHistory(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->query('search', '')
        );


        /*
        |--------------------------------------------------------------------------
        | Paid Payment Query
        |--------------------------------------------------------------------------
        */

        $query = DeliveryOrder::query()
            ->where(
                'payment_status',
                'Paid'
            )
            ->with([
                'user',

                'localOrder.procurementRequest.ptjDepartment',

                'localOrder.selectedQuotation.vendor',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(
                function ($paymentQuery) use ($search) {

                    /*
                    |--------------------------------------------------------------
                    | DO Number
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->where(
                        'do_number',
                        'like',
                        '%' . $search . '%'
                    );


                    /*
                    |--------------------------------------------------------------
                    | LO Number
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder',
                        function ($localOrderQuery) use ($search) {

                            $localOrderQuery->where(
                                'lo_number',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );


                    /*
                    |--------------------------------------------------------------
                    | Procurement Request
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder.procurementRequest',
                        function ($requestQuery) use ($search) {

                            $requestQuery->where(
                                'request_title',
                                'like',
                                '%' . $search . '%'
                            );


                            $numericSearch =
                                preg_replace(
                                    '/\D/',
                                    '',
                                    $search
                                );


                            if (
                                $numericSearch !== ''
                                && ctype_digit($numericSearch)
                            ) {

                                $requestQuery->orWhere(
                                    'procurement_request_id',
                                    (int) $numericSearch
                                );
                            }
                        }
                    );


                    /*
                    |--------------------------------------------------------------
                    | Vendor
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder.selectedQuotation.vendor',
                        function ($vendorQuery) use ($search) {

                            $vendorQuery->where(
                                'company_name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );


                    /*
                    |--------------------------------------------------------------
                    | PTJ Department
                    |--------------------------------------------------------------
                    */

                    $paymentQuery->orWhereHas(
                        'localOrder.procurementRequest.ptjDepartment',
                        function ($ptjQuery) use ($search) {

                            $ptjQuery->where(
                                'department_name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | History Summary
        |--------------------------------------------------------------------------
        */

        $totalPaidPayments =
            DeliveryOrder::where(
                'payment_status',
                'Paid'
            )->count();


        $totalPaidAmount =
            DeliveryOrder::where(
                'delivery_orders.payment_status',
                'Paid'
            )
                ->join(
                    'local_orders',
                    'delivery_orders.local_order_id',
                    '=',
                    'local_orders.local_order_id'
                )
                ->sum(
                    'local_orders.total_amount'
                );


        /*
        |--------------------------------------------------------------------------
        | Records
        |--------------------------------------------------------------------------
        */

        $paymentHistory =
            $query
                ->latest('updated_at')
                ->paginate(10)
                ->withQueryString();


        return view(
            'execution.paymentHistory',
            compact(
                'paymentHistory',
                'totalPaidPayments',
                'totalPaidAmount',
                'search'
            )
        );
    }

    /**
     * Display the details of one pending payment.
     */
    public function paymentDetails(
        DeliveryOrder $deliveryOrder
    ): View|RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Load Complete Payment Context
        |--------------------------------------------------------------------------
        */

        $deliveryOrder->load([
            'user',

            'localOrder.procurementRequest.ptjDepartment',

            'localOrder.procurementRequest.user',

            'localOrder.selectedQuotation.vendor',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Required Relationship Check
        |--------------------------------------------------------------------------
        */

        $localOrder =
            $deliveryOrder->localOrder;


        $procurementRequest =
            $localOrder?->procurementRequest;


        $selectedQuotation =
            $localOrder?->selectedQuotation;


        $vendor =
            $selectedQuotation?->vendor;


        if (
            !$localOrder
            || !$procurementRequest
            || !$selectedQuotation
            || !$vendor
        ) {

            return redirect()
                ->route(
                    'bendahari.payments.index'
                )
                ->with(
                    'error',
                    'The payment record is missing required procurement information.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Record Protection
        |--------------------------------------------------------------------------
        |
        | Financial Review can be opened for:
        |
        | Pending = actionable
        | Paid    = read-only history
        |
        */

        if (
            !in_array(
                $deliveryOrder->payment_status,
                ['Pending', 'Paid'],
                true
            )
        ) {

            return redirect()
                ->route(
                    'bendahari.payments.index'
                )
                ->with(
                    'error',
                    'This payment record is not available for review.'
                );
        }


        return view(
            'execution.paymentDetail',
            compact(
                'deliveryOrder',
                'localOrder',
                'procurementRequest',
                'selectedQuotation',
                'vendor'
            )
        );
    }

    /**
     * Simulate completion of the external payment process.
     */
    public function markAsPaid(
        DeliveryOrder $deliveryOrder
    ): RedirectResponse {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        |
        | Lock the payment record so that two submissions cannot process
        | the same payment simultaneously.
        |
        */

        DB::transaction(
            function () use (
                $deliveryOrder,
                $user
            ) {

                $lockedDeliveryOrder =
                    DeliveryOrder::where(
                        'delivery_order_id',
                        $deliveryOrder
                            ->delivery_order_id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Payment Processing
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedDeliveryOrder
                        ->payment_status
                    !== 'Pending'
                ) {

                    throw new \RuntimeException(
                        'This payment has already been processed.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Load Procurement Request
                |--------------------------------------------------------------------------
                */

                $lockedDeliveryOrder->load([
                    'localOrder.procurementRequest',
                ]);


                $procurementRequest =
                    $lockedDeliveryOrder
                        ->localOrder
                        ?->procurementRequest;


                if (!$procurementRequest) {

                    throw new \RuntimeException(
                        'The related procurement request could not be found.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Mark Payment as Paid
                |--------------------------------------------------------------------------
                |
                | Phase 1 B3 specifically requires:
                |
                | payment_status:
                | Pending → Paid
                |
                | This simulates an external payment process.
                |
                */

                $lockedDeliveryOrder->update(
                    [
                        'payment_status' =>
                            'Paid',
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Audit Log
                |--------------------------------------------------------------------------
                */

                AuditLog::create(
                    [
                        'procurement_request_id' =>
                            $procurementRequest
                                ->procurement_request_id,

                        'user_id' =>
                            $user->user_id,

                        'action' =>
                            'Bendahari Marked Payment as Paid',

                        'description' =>
                            'Bendahari marked payment for Local Order '
                            . $lockedDeliveryOrder
                                ->localOrder
                                ->lo_number
                            . ' as Paid. Payment status updated from Pending to Paid to simulate completion of the external payment process.',
                    ]
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'bendahari.payments.index'
            )
            ->with(
                'success',
                'Payment has been marked as Paid successfully.'
            );
    }

    /**
     * Bendahari - View the generated Local Order PDF
     * attached to a payment record.
     */
    public function viewPaymentLocalOrder(
        DeliveryOrder $deliveryOrder
    ): BinaryFileResponse {

        $deliveryOrder->load([
            'localOrder',
        ]);


        $localOrder =
            $deliveryOrder->localOrder;


        abort_if(
            !$localOrder
            || !$localOrder->lo_file_path,
            404
        );


        abort_if(
            !Storage::disk('public')
                ->exists($localOrder->lo_file_path),
            404
        );


        return response()->file(
            Storage::disk('public')
                ->path($localOrder->lo_file_path),
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="'
                    . $localOrder->lo_number
                    . '.pdf"',
            ]
        );
    }


    /**
     * Bendahari - View the uploaded vendor Delivery Order.
     */
    public function viewDeliveryOrderDocument(
        DeliveryOrder $deliveryOrder
    ): BinaryFileResponse {

        abort_if(
            !$deliveryOrder->do_pdf_path,
            404
        );


        abort_if(
            !Storage::disk('public')
                ->exists($deliveryOrder->do_pdf_path),
            404
        );


        return response()->file(
            Storage::disk('public')
                ->path($deliveryOrder->do_pdf_path),
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="'
                    . $deliveryOrder->do_number
                    . '.pdf"',
            ]
        );
    }


    /**
     * Bendahari - View the uploaded vendor invoice.
     */
    public function viewInvoiceDocument(
        DeliveryOrder $deliveryOrder
    ): BinaryFileResponse {

        abort_if(
            !$deliveryOrder->invoice_pdf_path,
            404
        );


        abort_if(
            !Storage::disk('public')
                ->exists(
                    $deliveryOrder->invoice_pdf_path
                ),
            404
        );


        return response()->file(
            Storage::disk('public')
                ->path(
                    $deliveryOrder->invoice_pdf_path
                ),
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="Invoice-'
                    . $deliveryOrder
                        ->delivery_order_id
                    . '.pdf"',
            ]
        );
    }

        /**
     * PTJ - View the Delivery Order PDF of their own completed order.
     */
    public function viewMyDeliveryOrder(
        DeliveryOrder $deliveryOrder
    ): BinaryFileResponse {

        $this->authorizePtjDeliveryDocument($deliveryOrder);

        abort_if(
            !$deliveryOrder->do_pdf_path
            || !Storage::disk('public')->exists($deliveryOrder->do_pdf_path),
            404,
            'Delivery Order PDF not found.'
        );

        return response()->file(
            Storage::disk('public')->path($deliveryOrder->do_pdf_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'
                    . $deliveryOrder->do_number . '.pdf"',
            ]
        );
    }

    /**
     * PTJ - View the vendor invoice PDF of their own completed order.
     */
    public function viewMyInvoice(
        DeliveryOrder $deliveryOrder
    ): BinaryFileResponse {

        $this->authorizePtjDeliveryDocument($deliveryOrder);

        abort_if(
            !$deliveryOrder->invoice_pdf_path
            || !Storage::disk('public')->exists($deliveryOrder->invoice_pdf_path),
            404,
            'Invoice PDF not found.'
        );

        return response()->file(
            Storage::disk('public')->path($deliveryOrder->invoice_pdf_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Invoice-'
                    . $deliveryOrder->delivery_order_id . '.pdf"',
            ]
        );
    }

    /**
     * Make sure the document belongs to the logged-in PTJ.
     */
    private function authorizePtjDeliveryDocument(
        DeliveryOrder $deliveryOrder
    ): void {

        $deliveryOrder->load('localOrder.procurementRequest');

        $procurementRequest =
            $deliveryOrder->localOrder?->procurementRequest;

        abort_unless(
            $procurementRequest
            && (int) $procurementRequest->ptj_id
                === (int) auth()->user()->ptj_id,
            403,
            'You are not authorized to view this document.'
        );
    }

    
}