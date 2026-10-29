<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\RequestItem;
use App\Models\PtjBudget;
use App\Models\Vendor;
use App\Models\VendorQuotation;
use App\Models\AuditLog;
use App\Services\AiEvaluationService;
use App\Jobs\EvaluateQuotationJob;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProcurementController extends Controller
{


        public function __construct(
            private readonly AiEvaluationService $aiEvaluationService
        ) {
        }


    /**
     * Display the PTJ role dashboard using live database data.
     *
     * Dashboard responsibilities:
     * - Show the logged-in PTJ department's current available budget.
     * - Show this PTJ staff member's requests still awaiting KJ/Bendahari approval.
     * - Show this PTJ staff member's real procurement request records.
     * - Support dashboard status tabs, search and pagination.
     *
     * This keeps the dashboard inside the existing Manage Procurement Request
     * controller instead of introducing a separate DashboardController.
     */
    public function dashboard(Request $request): View
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | PTJ Assignment
        |--------------------------------------------------------------------------
        */

        if (!$user->ptj_id) {
            abort(
                403,
                'Your account is not assigned to a PTJ department.'
            );
        }

        $department = DB::table('ptj_departments')
            ->where('ptj_id', $user->ptj_id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Current PTJ Budget
        |--------------------------------------------------------------------------
        | Available balance = allocated - used - reserved.
        | This is the same budget rule already used by Module 2.
        */

        $budgetYear = now()->year;

        $budget = PtjBudget::where('ptj_id', $user->ptj_id)
            ->where('budget_year', $budgetYear)
            ->first();

        $availableBudget = $budget
            ? max(
                0,
                (float) $budget->allocated_amount
                - (float) $budget->used_amount
                - (float) $budget->reserved_amount
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Dashboard Request Scope
        |--------------------------------------------------------------------------
        | Keep this identical to ProcurementController::index():
        | PTJ staff sees only requests created by themselves under their PTJ.
        */

        $baseQuery = ProcurementRequest::where(
                'user_id',
                $user->user_id
            )
            ->where(
                'ptj_id',
                $user->ptj_id
            );

        /*
        |--------------------------------------------------------------------------
        | Requests Pending Approval KPI
        |--------------------------------------------------------------------------
        | Pending_KJ      = waiting for KJ.
        | Approved_KJ     = KJ approved; waiting for Bendahari.
        */

        $pendingApprovalCount = (clone $baseQuery)
            ->whereIn('request_status', [
                'Pending_KJ',
                'Approved_KJ',
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Status Filter
        |--------------------------------------------------------------------------
        */

        $status = (string) $request->query('status', 'all');

        $allowedStatuses = [
            'all',
            'draft',
            'pending-kj',
            'pending-bendahari',
            'update',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $query = clone $baseQuery;

        switch ($status) {
            case 'draft':
                $query->where('request_status', 'Draft');
                break;

            case 'pending-kj':
                $query->where('request_status', 'Pending_KJ');
                break;

            case 'pending-bendahari':
                $query->where('request_status', 'Approved_KJ');
                break;

            case 'update':
                $query->where('request_status', 'Pending_Edit');
                break;

                    case 'in-progress':
            $query->whereIn('request_status', [
                'Approved_Bendahari',
                'LO_Generated',
            ]);
            break;

            case 'completed':
                $query->where('request_status', 'Completed');
                break;

            case 'rejected':
                $query->whereIn('request_status', [
                    'Rejected_KJ',
                    'Rejected_Bendahari',
                ]);
                break;

            default:
                // All statuses.
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        | Search by request title or request number such as 6 / REQ-0006 / REQ-6.
        */

        $search = trim((string) $request->query('search', ''));

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'request_title',
                    'like',
                    '%' . $search . '%'
                );

                $normalizedSearch = strtoupper(
                    str_replace([' ', '_'], '', $search)
                );

                if (preg_match('/^(REQ-?)?\\d+$/', $normalizedSearch)) {
                    $requestId = (int) preg_replace(
                        '/\\D/',
                        '',
                        $normalizedSearch
                    );

                    $q->orWhere(
                        'procurement_request_id',
                        $requestId
                    );
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Retrieve Real Dashboard Requests
        |--------------------------------------------------------------------------
        */

        $procurementRequests = $query
            ->latest('created_at')
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | User-Facing Status Labels
        |--------------------------------------------------------------------------
        | Database status values remain unchanged.
        */

        $statusLabels = [
            'Draft' => 'Draft',
            'Pending_KJ' => 'Pending KJ Approval',
            'Pending_Edit' => 'Update Required',
            'Approved_KJ' => 'Pending Bendahari Approval',
            'Rejected_KJ' => 'Rejected by KJ',
            'Approved_Bendahari' => 'Approved by Bendahari',
            'Rejected_Bendahari' => 'Rejected by Bendahari',
            'LO_Generated' => 'LO Generated',
            'Awaiting_Delivery' => 'Awaiting Delivery',
            'DO_Received' => 'Delivery Received',
            'Invoice_Recorded' => 'Invoice Recorded',
            'Completed' => 'Completed',
        ];

        /*
        |--------------------------------------------------------------------------
        | Greeting
        |--------------------------------------------------------------------------
        */

        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };

        return view(
            'dashboards.ptj',
            compact(
                'greeting',
                'department',
                'budgetYear',
                'budget',
                'availableBudget',
                'pendingApprovalCount',
                'procurementRequests',
                'status',
                'search',
                'statusLabels'
            )
        );
    }

    /**
     * Display the logged-in PTJ staff's procurement requests.
     */
    public function index(Request $request)
{
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | Base Query
    |--------------------------------------------------------------------------
    | PTJ staff only sees procurement requests created by themselves
    | under their own PTJ department.
    */
    $baseQuery = ProcurementRequest::where('user_id', $user->user_id)
        ->where('ptj_id', $user->ptj_id);

    /*
    |--------------------------------------------------------------------------
    | Summary Counts
    |--------------------------------------------------------------------------
    */
    $totalRequests = (clone $baseQuery)->count();

    $pendingRequests = (clone $baseQuery)
        ->whereIn('request_status', [
            'Pending_KJ',
            'Approved_KJ',
        ])
        ->count();

    $updateRequests = (clone $baseQuery)
        ->where('request_status', 'Pending_Edit')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */
    $status = $request->query('status', 'all');

    $query = clone $baseQuery;

    switch ($status) {

        case 'draft':
            $query->where('request_status', 'Draft');
            break;

        case 'pending':
            $query->whereIn('request_status', [
                'Pending_KJ',
                'Approved_KJ',
            ]);
            break;

        case 'pending-kj':
            $query->where('request_status', 'Pending_KJ');
            break;

        case 'pending-bendahari':
            $query->where('request_status', 'Approved_KJ');
            break;

        case 'update':
            $query->where('request_status', 'Pending_Edit');
            break;

        default:
            // "All" - no additional status condition.
            break;
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    $search = trim((string) $request->query('search', ''));

    if ($search !== '') {

        $query->where(function ($q) use ($search) {

            $q->where(
                'request_title',
                'like',
                '%' . $search . '%'
            );

            // Also allow searching using numeric request ID.
            if (ctype_digit($search)) {
                $q->orWhere(
                    'procurement_request_id',
                    (int) $search
                );
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve Requests
    |--------------------------------------------------------------------------
    */
    $procurementRequests = $query
        ->latest('created_at')
        ->paginate(10)
        ->withQueryString();

    return view('procurement.requestList', compact(
        'procurementRequests',
        'totalRequests',
        'pendingRequests',
        'updateRequests',
        'status',
        'search'
    ));
}

    /**
     * Display the New Procurement Request form.
     *
     * UCPMS-UC-200 B2: New Procurement Request
     */
    public function create()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Verify PTJ Assignment
        |--------------------------------------------------------------------------
        */

        if (!$user->ptj_id) {
            return redirect()
                ->route('ptj.procurement.index')
                ->with(
                    'error',
                    'Your account is not assigned to a PTJ department.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Retrieve Pre-Verified Vendors
        |--------------------------------------------------------------------------
        */

        $vendors = Vendor::orderBy('company_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Retrieve Current PTJ Budget
        |--------------------------------------------------------------------------
        */

        $budget = PtjBudget::where(
                'ptj_id',
                $user->ptj_id
            )
            ->where(
                'budget_year',
                now()->year
            )
            ->first();


        if (!$budget) {
            return redirect()
                ->route('ptj.procurement.index')
                ->with(
                    'error',
                    'No budget allocation was found for your department for the current year.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Available Budget
        |--------------------------------------------------------------------------
        */

        $availableBudget =
            (float) $budget->allocated_amount
            - (float) $budget->used_amount
            - (float) $budget->reserved_amount;


        /*
        |--------------------------------------------------------------------------
        | Display Documented View
        |--------------------------------------------------------------------------
        */

        return view(
            'procurement.newProcurement',
            compact(
                'vendors',
                'budget',
                'availableBudget'
            )
        );
    }


    /**
     * Store a new procurement request.
     *
     * UCPMS-UC-200 B2:
     * - Validate request details
     * - Save request items
     * - Validate Direct Purchase limit
     * - Check PTJ budget
     * - Save at least 3 vendor quotations
     * - Upload one PDF for each quotation
     * - Soft-reserve PTJ budget
     * - Update request status to Pending_KJ
     */
    
    public function store(Request $request)
{
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | PTJ Validation
    |--------------------------------------------------------------------------
    */

    if (!$user->ptj_id) {
        return redirect()
            ->route('ptj.procurement.index')
            ->with(
                'error',
                'Your account is not assigned to a PTJ department.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate(

        [

            // General Request Details
            'request_title' => [
                'required',
                'string',
                'max:100',
            ],

            'request_description' => [
                'required',
                'string',
            ],


            // Request Items
            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.item_name' => [
                'required',
                'string',
                'max:100',
            ],

            'items.*.item_description' => [
                'required',
                'string',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0.01',
            ],


            // atleast Three Vendor Quotations
            'quotations' => [
            'required',
            'array',
            'min:3',
        ],

            'quotations.*.vendor_id' => [
                'required',
                'integer',
                'distinct',
                'exists:vendors,vendor_id',
            ],

            'quotations.*.quotation_reference_number' => [
                'required',
                'string',
                'max:100',
            ],

            'quotations.*.quotation_date' => [
                'required',
                'date',
            ],

            'quotations.*.total_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'quotations.*.warranty_period' => [
            'required',
            'integer',
            'min:0',
        ],

        'quotations.*.delivery_lead_time' => [
            'required',
            'integer',
            'min:0',
        ],

        'quotations.*.payment_terms' => [
            'required',
            'string',
            'max:255',
        ],

            'quotations.*.quotation_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ],

        [

            /*
            |--------------------------------------------------------------------------
            | User-Friendly Validation Messages
            |--------------------------------------------------------------------------
            */

            'request_title.required' =>
                'Please enter the procurement request title.',

            'request_description.required' =>
                'Please enter the request description.',


            'items.required' =>
                'Please add at least one request item.',

            'items.min' =>
                'Please add at least one request item.',

            'items.*.item_name.required' =>
                'Each request item must have an item name.',

            'items.*.item_description.required' =>
                'Each request item must have a description.',

            'items.*.quantity.required' =>
                'Please enter the quantity for every item.',

            'items.*.quantity.min' =>
                'Item quantity must be at least 1.',

            'items.*.unit_price.required' =>
                'Please enter the unit price for every item.',


            'quotations.required' =>
                'At least three vendor quotations are required.',

            'quotations.min' =>
                'At least three vendor quotations are required.',

            'quotations.*.vendor_id.required' =>
                'Please select a vendor for every quotation.',

            'quotations.*.vendor_id.distinct' =>
                'The same vendor cannot be selected more than once.',

            'quotations.*.quotation_reference_number.required' =>
                'Please enter the quotation reference number.',

            'quotations.*.quotation_date.required' =>
                'Please enter the quotation date.',

            'quotations.*.total_amount.required' =>
                'Please enter the quoted amount for every vendor.',

            'quotations.*.quotation_file.required' =>
                'Please upload a quotation PDF for every vendor.',

            'quotations.*.quotation_file.mimes' =>
                'Quotation documents must be PDF files.',

            'quotations.*.quotation_file.max' =>
                'Each quotation PDF must not exceed 5 MB.',
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Calculate Estimated Amount
    |--------------------------------------------------------------------------
    |
    | Do NOT trust a total submitted from JavaScript.
    | Laravel recalculates every item again on the server.
    |
    */

    $calculatedItems = [];

    $estimatedAmount = 0;

    foreach ($validated['items'] as $item) {

        $quantity = (int) $item['quantity'];

        $unitPrice = round(
            (float) $item['unit_price'],
            2
        );

        $totalPrice = round(
            $quantity * $unitPrice,
            2
        );

        $estimatedAmount += $totalPrice;

        $calculatedItems[] = [
            'item_name' => $item['item_name'],
            'item_description' => $item['item_description'],
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ];
    }

    $estimatedAmount = round(
        $estimatedAmount,
        2
    );


    /*
    |--------------------------------------------------------------------------
    | UCPMS-UC-201: Direct Purchase Limit
    |--------------------------------------------------------------------------
    */

    if ($estimatedAmount > 50000) {

        throw ValidationException::withMessages([

            'estimated_amount' =>
                'Amount exceeds RM 50,000 Direct Purchase limit.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Zero / Invalid Total
    |--------------------------------------------------------------------------
    */

    if ($estimatedAmount <= 0) {

        throw ValidationException::withMessages([

            'estimated_amount' =>
                'Estimated amount must be greater than RM 0.00.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Track Uploaded Files
    |--------------------------------------------------------------------------
    |
    | Database transactions cannot automatically delete files
    | already saved to storage, so we remember their paths.
    |
    */

    $storedFiles = [];

        /*
    |--------------------------------------------------------------------------
    | Keep Created Request for AI Evaluation
    |--------------------------------------------------------------------------
    |
    | The request itself is created inside the database transaction.
    | We keep its model here so Gemini can be called only AFTER the
    | database transaction has committed successfully.
    |
    */

    $submittedProcurementRequest = null;


    try {

        DB::transaction(function () use (
            $user,
            $validated,
            $calculatedItems,
            $estimatedAmount,
            &$storedFiles,
            &$submittedProcurementRequest
        ) {


            /*
            |--------------------------------------------------------------------------
            | Get and Lock PTJ Budget
            |--------------------------------------------------------------------------
            |
            | lockForUpdate() prevents two simultaneous submissions from
            | spending the same available budget.
            |
            */

            $budget = PtjBudget::where(
                    'ptj_id',
                    $user->ptj_id
                )
                ->where(
                    'budget_year',
                    now()->year
                )
                ->lockForUpdate()
                ->first();


            if (!$budget) {

                throw ValidationException::withMessages([

                    'estimated_amount' =>
                        'No budget allocation was found for your department for the current year.',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Current Available Budget
            |--------------------------------------------------------------------------
            */

            $availableBudget =
                (float) $budget->allocated_amount
                - (float) $budget->used_amount
                - (float) $budget->reserved_amount;


            /*
            |--------------------------------------------------------------------------
            | UCPMS-UC-201: Available Budget Validation
            |--------------------------------------------------------------------------
            */

            if ($estimatedAmount > $availableBudget) {

                throw ValidationException::withMessages([

                    'estimated_amount' =>
                        'Insufficient budget balance. Available budget: RM '
                        . number_format($availableBudget, 2)
                        . '.',

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Procurement Request
            |--------------------------------------------------------------------------
            */

            $procurementRequest = ProcurementRequest::create([

                'ptj_id' =>
                    $user->ptj_id,

                'user_id' =>
                    $user->user_id,

                'request_title' =>
                    $validated['request_title'],

                'request_description' =>
                    $validated['request_description'],

                'estimated_amount' =>
                    $estimatedAmount,

                'selected_quotation_id' =>
                    null,

                'approved_amount' =>
                    null,

                'request_status' =>
                    'Pending_KJ',

            ]);

            $submittedProcurementRequest =
                 $procurementRequest;


            /*
            |--------------------------------------------------------------------------
            | Save Request Items
            |--------------------------------------------------------------------------
            */

            foreach ($calculatedItems as $item) {

                RequestItem::create([

                    'procurement_request_id' =>
                        $procurementRequest->procurement_request_id,

                    'item_name' =>
                        $item['item_name'],

                    'item_description' =>
                        $item['item_description'],

                    'quantity' =>
                        $item['quantity'],

                    'unit_price' =>
                        $item['unit_price'],

                    'total_price' =>
                        $item['total_price'],

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Save Vendor Quotations
            |--------------------------------------------------------------------------
            */

            foreach ($validated['quotations'] as $index => $quotation) {

                /*
                 * Each vendor quotation has its own PDF.
                 */
                $quotationFile =
                    request()->file(
                        "quotations.$index.quotation_file"
                    );


                $quotationFilePath =
                    $quotationFile->store(
                        'procurement/quotations/'
                        . $procurementRequest->procurement_request_id,
                        'public'
                    );


                $storedFiles[] =
                    $quotationFilePath;


                VendorQuotation::create([

                    'procurement_request_id' =>
                        $procurementRequest->procurement_request_id,

                    'vendor_id' =>
                        $quotation['vendor_id'],

                    'quotation_reference_number' =>
                        $quotation['quotation_reference_number'],

                    'quotation_date' =>
                        $quotation['quotation_date'],

                    'total_amount' =>
                        $quotation['total_amount'],

                    'quotation_file_path' =>
                        $quotationFilePath,

                    'warranty_period' =>
                        $quotation['warranty_period'] ?? null,

                    'delivery_lead_time' =>
                        $quotation['delivery_lead_time'] ?? null,

                    'payment_terms' =>
                        $quotation['payment_terms'] ?? null,

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Soft Reserve PTJ Budget
            |--------------------------------------------------------------------------
            |
            | The estimated procurement amount is reserved when the
            | request successfully enters Pending_KJ.
            |
            */

            $budget->reserved_amount =
                (float) $budget->reserved_amount
                + $estimatedAmount;

            $budget->save();

            /*
            |--------------------------------------------------------------------------
            | Audit Log - New Procurement Request Submitted
            |--------------------------------------------------------------------------
            */

            AuditLog::create([

                'procurement_request_id' =>
                    $procurementRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'action' =>
                    'Procurement Request Submitted',

                'description' =>
                    'PTJ staff submitted procurement request REQ-'
                    . str_pad(
                        $procurementRequest->procurement_request_id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    )
                    . ' for KJ approval. Status changed to Pending_KJ.',

            ]);

        });

        /*
        |--------------------------------------------------------------------------
        | Module 3 - Queue AI Quotation Evaluation
        |--------------------------------------------------------------------------
        |
        | The procurement transaction has already committed successfully.
        | The PTJ does not wait for Gemini.
        |
        | 1. Create/reset the AI recommendation row to Pending.
        | 2. Dispatch one background queue job.
        | 3. Continue immediately to the normal success redirect.
        |
        */

        if ($submittedProcurementRequest) {

            try {

                $this->aiEvaluationService
                    ->prepareForEvaluation(
                        $submittedProcurementRequest->fresh()
                    );

                EvaluateQuotationJob::dispatch(
                    $submittedProcurementRequest
                        ->procurement_request_id
                );

            } catch (\Throwable $aiQueueException) {

                /*
                | Queue preparation/dispatch problems must never undo
                | an already successful procurement submission.
                */

                report($aiQueueException);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Successful Submission
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('ptj.procurement.index')
            ->with(
                'success',
                'Procurement request submitted successfully and sent for KJ approval.'
            );


    } catch (ValidationException $exception) {

        /*
        |--------------------------------------------------------------------------
        | Remove Files If Database Transaction Failed
        |--------------------------------------------------------------------------
        */

        if (!empty($storedFiles)) {

            Storage::disk('public')
                ->delete($storedFiles);
        }

        throw $exception;


    } catch (\Throwable $exception) {

        /*
        |--------------------------------------------------------------------------
        | Unexpected Failure
        |--------------------------------------------------------------------------
        */

        if (!empty($storedFiles)) {

            Storage::disk('public')
                ->delete($storedFiles);
        }


        report($exception);


        return back()
            ->withInput()
            ->with(
                'error',
                'The procurement request could not be submitted. Please try again.'
            );
    }
}


    /**
     * Display one procurement request.
     */
    public function show(
        ProcurementRequest $procurementRequest
    ): View {

        $this->ensureRequestBelongsToLoggedInPtj(
            $procurementRequest
        );

        $procurementRequest->load([
            'user',
            'ptjDepartment',
            'items',
            'quotations.vendor',
            'selectedQuotation.vendor',
            'approvals',
            'localOrder.deliveryOrder',
        ]);


        $rejectionDecision = null;


        if (
            $procurementRequest->request_status
            === 'Rejected_KJ'
        ) {

            $rejectionDecision =
                $procurementRequest
                    ->approvals()
                    ->where(
                        'approval_stage',
                        'KJ'
                    )
                    ->where(
                        'decision',
                        'Rejected'
                    )
                    ->latest('created_at')
                    ->first();

        } elseif (
            $procurementRequest->request_status
            === 'Rejected_Bendahari'
        ) {

            $rejectionDecision =
                $procurementRequest
                    ->approvals()
                    ->where(
                        'approval_stage',
                        'Bendahari'
                    )
                    ->where(
                        'decision',
                        'Rejected'
                    )
                    ->latest('created_at')
                    ->first();

        }


        $rejectionRemarks =
            $rejectionDecision?->remarks;


        return view(
            'procurement.requestDetails',
            [
                'procurementRequest' =>
                    $procurementRequest,

                'rejectionDecision' =>
                    $rejectionDecision,

                'rejectionRemarks' =>
                    $rejectionRemarks,
            ]
        );
    }


    /**
     * Display the Edit and Resubmit Request form.
     *
     * UCPMS-UC-200 B4: Edit and Resubmit Request
     */
    public function edit(ProcurementRequest $procurementRequest)
    {
        $this->ensureRequestBelongsToLoggedInPtj(
            $procurementRequest
        );

        /*
        |--------------------------------------------------------------------------
        | Only requests returned for amendment may be edited
        |--------------------------------------------------------------------------
        */

        if ($procurementRequest->request_status !== 'Pending_Edit') {

            return redirect()
                ->route(
                    'ptj.procurement.show',
                    $procurementRequest->procurement_request_id
                )
                ->with(
                    'error',
                    'This procurement request cannot currently be edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Load Current Request Data
        |--------------------------------------------------------------------------
        */

        $procurementRequest->load([
            'items',
            'quotations.vendor',
            'ptjDepartment',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Latest KJ Return Remarks
        |--------------------------------------------------------------------------
        */

        $returnApproval = $procurementRequest->approvals()
            ->where('approval_stage', 'kj')
            ->where('decision', 'returned')
            ->latest('created_at')
            ->first();

        $returnRemarks = $returnApproval?->remarks;

        /*
        |--------------------------------------------------------------------------
        | Retrieve Pre-Verified Vendors
        |--------------------------------------------------------------------------
        */

        $vendors = Vendor::orderBy('company_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Retrieve Current PTJ Budget
        |--------------------------------------------------------------------------
        */

        $budget = PtjBudget::where(
                'ptj_id',
                auth()->user()->ptj_id
            )
            ->where(
                'budget_year',
                now()->year
            )
            ->first();


        if (!$budget) {

            return redirect()
                ->route('ptj.procurement.index')
                ->with(
                    'error',
                    'No budget allocation was found for your department for the current year.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Editable Available Budget
        |--------------------------------------------------------------------------
        |
        | The current request amount is already soft-reserved.
        |
        | Example:
        |
        | Allocated     = 300,000
        | Reserved      =   5,500   <- this request
        | Current avail = 294,500
        |
        | While editing, the request may reuse its own RM5,500 reservation.
        | Therefore:
        |
        | Editable availability =
        | allocated - used - reserved + current request amount
        |
        */

        $availableBudget =
            (float) $budget->allocated_amount
            - (float) $budget->used_amount
            - (float) $budget->reserved_amount
            + (float) $procurementRequest->estimated_amount;


        return view(
            'procurement.editRequest',
            compact(
            'procurementRequest',
            'vendors',
            'budget',
            'availableBudget',
            'returnRemarks'
        )
        );
    }


    /**
     * Update and resubmit a request returned by KJ.
     *
     * Full resubmission logic will be added together with
     * the Edit Procurement Request form.
     */

    public function update(
        Request $request,
        ProcurementRequest $procurementRequest
    ) {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        $this->ensureRequestBelongsToLoggedInPtj(
            $procurementRequest
        );


        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if ($procurementRequest->request_status !== 'Pending_Edit') {

            return redirect()
                ->route(
                    'ptj.procurement.show',
                    $procurementRequest->procurement_request_id
                )
                ->with(
                    'error',
                    'This procurement request cannot currently be edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(

            [

                /*
                | General Request Details
                */

                'request_title' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'request_description' => [
                    'required',
                    'string',
                ],


                /*
                | Request Items
                */

                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.item_name' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'items.*.item_description' => [
                    'required',
                    'string',
                ],

                'items.*.quantity' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'items.*.unit_price' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],


                /*
                | Vendor Quotations
                |
                | Updated UCPMS rule:
                | minimum 3 distinct vendors.
                */

                'quotations' => [
                    'required',
                    'array',
                    'min:3',
                ],

                /*
                * Existing quotation ID.
                * New quotation cards will have no quotation_id.
                */
                'quotations.*.quotation_id' => [
                    'nullable',
                    'integer',
                    'distinct',
                ],

                'quotations.*.vendor_id' => [
                    'required',
                    'integer',
                    'distinct',
                    'exists:vendors,vendor_id',
                ],

                'quotations.*.quotation_reference_number' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'quotations.*.quotation_date' => [
                    'required',
                    'date',
                ],

                'quotations.*.total_amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'quotations.*.warranty_period' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'quotations.*.delivery_lead_time' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'quotations.*.payment_terms' => [
                    'required',
                    'string',
                    'max:255',
                ],

                /*
                * Existing quotation:
                * PDF may remain unchanged.
                *
                * New quotation:
                * PDF requirement is checked below.
                */
                'quotations.*.quotation_file' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:5120',
                ],
            ],

            [

                'request_title.required' =>
                    'Please enter the procurement request title.',

                'request_description.required' =>
                    'Please enter the request description.',

                'items.required' =>
                    'Please add at least one request item.',

                'items.min' =>
                    'Please add at least one request item.',

                'items.*.item_name.required' =>
                    'Each request item must have an item name.',

                'items.*.item_description.required' =>
                    'Each request item must have a description.',

                'items.*.quantity.required' =>
                    'Please enter the quantity for every item.',

                'items.*.quantity.min' =>
                    'Item quantity must be at least 1.',

                'items.*.unit_price.required' =>
                    'Please enter the unit price for every item.',

                'quotations.required' =>
                    'At least three vendor quotations are required.',

                'quotations.min' =>
                    'At least three vendor quotations are required.',

                'quotations.*.vendor_id.required' =>
                    'Please select a vendor for every quotation.',

                'quotations.*.vendor_id.distinct' =>
                    'The same vendor cannot be selected more than once.',

                'quotations.*.quotation_reference_number.required' =>
                    'Please enter the quotation reference number.',

                'quotations.*.quotation_date.required' =>
                    'Please enter the quotation date.',

                'quotations.*.total_amount.required' =>
                    'Please enter the quoted amount for every vendor.',

                'quotations.*.warranty_period.required' =>
                    'Please enter the warranty period.',

                'quotations.*.delivery_lead_time.required' =>
                    'Please enter the delivery lead time.',

                'quotations.*.payment_terms.required' =>
                    'Please enter the payment terms.',

                'quotations.*.quotation_file.mimes' =>
                    'Quotation documents must be PDF files.',

                'quotations.*.quotation_file.max' =>
                    'Each quotation PDF must not exceed 5 MB.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Request Items Again on Server
        |--------------------------------------------------------------------------
        */

        $calculatedItems = [];

        $newEstimatedAmount = 0;


        foreach ($validated['items'] as $item) {

            $quantity =
                (int) $item['quantity'];


            $unitPrice =
                round(
                    (float) $item['unit_price'],
                    2
                );


            $totalPrice =
                round(
                    $quantity * $unitPrice,
                    2
                );


            $newEstimatedAmount +=
                $totalPrice;


            $calculatedItems[] = [

                'item_name' =>
                    $item['item_name'],

                'item_description' =>
                    $item['item_description'],

                'quantity' =>
                    $quantity,

                'unit_price' =>
                    $unitPrice,

                'total_price' =>
                    $totalPrice,

            ];
        }


        $newEstimatedAmount =
            round(
                $newEstimatedAmount,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Direct Purchase Limit
        |--------------------------------------------------------------------------
        */

        if ($newEstimatedAmount > 50000) {

            throw ValidationException::withMessages([

                'estimated_amount' =>
                    'Amount exceeds RM 50,000 Direct Purchase limit.',

            ]);
        }


        if ($newEstimatedAmount <= 0) {

            throw ValidationException::withMessages([

                'estimated_amount' =>
                    'Estimated amount must be greater than RM 0.00.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Uploaded File Tracking
        |--------------------------------------------------------------------------
        */

        $newStoredFiles = [];

        /*
        * These files must only be deleted AFTER the database
        * transaction succeeds.
        */
        $oldFilesToDelete = [];


        try {

            DB::transaction(function () use (
                $request,
                $user,
                $validated,
                $calculatedItems,
                $newEstimatedAmount,
                $procurementRequest,
                &$newStoredFiles,
                &$oldFilesToDelete
            ) {


                /*
                |--------------------------------------------------------------------------
                | Lock Procurement Request
                |--------------------------------------------------------------------------
                */

                $lockedRequest =
                    ProcurementRequest::where(
                        'procurement_request_id',
                        $procurementRequest->procurement_request_id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                * Prevent a stale edit page from changing a request after
                * another process has already changed its workflow state.
                */

                if ($lockedRequest->request_status !== 'Pending_Edit') {

                    throw ValidationException::withMessages([

                        'request_status' =>
                            'This request is no longer available for editing.',

                    ]);
                }


                if (
                    $lockedRequest->user_id !== $user->user_id
                    ||
                    $lockedRequest->ptj_id !== $user->ptj_id
                ) {

                    abort(
                        403,
                        'You are not authorized to modify this procurement request.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Lock Current PTJ Budget
                |--------------------------------------------------------------------------
                */

                $budget =
                    PtjBudget::where(
                        'ptj_id',
                        $user->ptj_id
                    )
                    ->where(
                        'budget_year',
                        now()->year
                    )
                    ->lockForUpdate()
                    ->first();


                if (!$budget) {

                    throw ValidationException::withMessages([

                        'estimated_amount' =>
                            'No budget allocation was found for your department for the current year.',

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Calculate Budget Available to This Request
                |--------------------------------------------------------------------------
                */

                $oldEstimatedAmount =
                    (float) $lockedRequest->estimated_amount;


                $availableForThisRequest =
                    (float) $budget->allocated_amount
                    - (float) $budget->used_amount
                    - (float) $budget->reserved_amount
                    + $oldEstimatedAmount;


                if (
                    $newEstimatedAmount
                    >
                    $availableForThisRequest
                ) {

                    throw ValidationException::withMessages([

                        'estimated_amount' =>
                            'Insufficient budget balance. Maximum available amount for this request is RM '
                            . number_format(
                                $availableForThisRequest,
                                2
                            )
                            . '.',

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Update Procurement Request
                |--------------------------------------------------------------------------
                */

                $lockedRequest->update([

                    'request_title' =>
                        $validated['request_title'],

                    'request_description' =>
                        $validated['request_description'],

                    'estimated_amount' =>
                        $newEstimatedAmount,

                    /*
                    * B4 documented workflow:
                    * return to KJ after resubmission.
                    */
                    'request_status' =>
                        'Pending_KJ',

                ]);


                /*
                |--------------------------------------------------------------------------
                | Replace Request Items
                |--------------------------------------------------------------------------
                |
                | Since the request is still in amendment stage, replacing
                | its item rows is simpler and keeps them synchronized with
                | the submitted edit form.
                |--------------------------------------------------------------------------
                */

                RequestItem::where(
                    'procurement_request_id',
                    $lockedRequest->procurement_request_id
                )->delete();


                foreach ($calculatedItems as $item) {

                    RequestItem::create([

                        'procurement_request_id' =>
                            $lockedRequest->procurement_request_id,

                        'item_name' =>
                            $item['item_name'],

                        'item_description' =>
                            $item['item_description'],

                        'quantity' =>
                            $item['quantity'],

                        'unit_price' =>
                            $item['unit_price'],

                        'total_price' =>
                            $item['total_price'],

                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Existing Quotation Records
                |--------------------------------------------------------------------------
                */

                $existingQuotations =
                    VendorQuotation::where(
                        'procurement_request_id',
                        $lockedRequest->procurement_request_id
                    )
                    ->get()
                    ->keyBy('quotation_id');


                $submittedExistingQuotationIds = [];


                /*
                |--------------------------------------------------------------------------
                | Update Existing / Create New Quotations
                |--------------------------------------------------------------------------
                */

                foreach (
                    $validated['quotations']
                    as $index => $quotationData
                ) {

                    $quotationId =
                        $quotationData['quotation_id']
                        ?? null;


                    $newFile =
                        $request->file(
                            "quotations.$index.quotation_file"
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Existing quotation
                    |--------------------------------------------------------------------------
                    */

                    if ($quotationId) {

                        if (
                            !$existingQuotations->has(
                                $quotationId
                            )
                        ) {

                            throw ValidationException::withMessages([

                                "quotations.$index.quotation_id" =>
                                    'Invalid quotation record.',

                            ]);
                        }


                        $quotation =
                            $existingQuotations->get(
                                $quotationId
                            );


                        $submittedExistingQuotationIds[] =
                            (int) $quotationId;


                        $quotationFilePath =
                            $quotation->quotation_file_path;


                        /*
                        * Replace PDF only when PTJ uploads a new PDF.
                        */
                        if ($newFile) {

                            $newPath =
                                $newFile->store(
                                    'procurement/quotations/'
                                    . $lockedRequest->procurement_request_id,
                                    'public'
                                );


                            $newStoredFiles[] =
                                $newPath;


                            if (
                                $quotation->quotation_file_path
                            ) {

                                $oldFilesToDelete[] =
                                    $quotation->quotation_file_path;
                            }


                            $quotationFilePath =
                                $newPath;
                        }


                        $quotation->update([

                            'vendor_id' =>
                                $quotationData['vendor_id'],

                            'quotation_reference_number' =>
                                $quotationData['quotation_reference_number'],

                            'quotation_date' =>
                                $quotationData['quotation_date'],

                            'total_amount' =>
                                $quotationData['total_amount'],

                            'quotation_file_path' =>
                                $quotationFilePath,

                            'warranty_period' =>
                                $quotationData['warranty_period'],

                            'delivery_lead_time' =>
                                $quotationData['delivery_lead_time'],

                            'payment_terms' =>
                                $quotationData['payment_terms'],

                        ]);

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | New quotation
                        |--------------------------------------------------------------------------
                        |
                        | A newly added vendor must provide a PDF.
                        |--------------------------------------------------------------------------
                        */

                        if (!$newFile) {

                            throw ValidationException::withMessages([

                                "quotations.$index.quotation_file" =>
                                    'Please upload a quotation PDF for the newly added vendor.',

                            ]);
                        }


                        $newPath =
                            $newFile->store(
                                'procurement/quotations/'
                                . $lockedRequest->procurement_request_id,
                                'public'
                            );


                        $newStoredFiles[] =
                            $newPath;


                        VendorQuotation::create([

                            'procurement_request_id' =>
                                $lockedRequest->procurement_request_id,

                            'vendor_id' =>
                                $quotationData['vendor_id'],

                            'quotation_reference_number' =>
                                $quotationData['quotation_reference_number'],

                            'quotation_date' =>
                                $quotationData['quotation_date'],

                            'total_amount' =>
                                $quotationData['total_amount'],

                            'quotation_file_path' =>
                                $newPath,

                            'warranty_period' =>
                                $quotationData['warranty_period'],

                            'delivery_lead_time' =>
                                $quotationData['delivery_lead_time'],

                            'payment_terms' =>
                                $quotationData['payment_terms'],

                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Remove Deleted Existing Quotations
                |--------------------------------------------------------------------------
                |
                | Example:
                | Request originally had 4 vendors.
                | PTJ removes Vendor 4 during amendment.
                |
                | Minimum-three validation still prevents fewer than 3.
                |--------------------------------------------------------------------------
                */

                foreach (
                    $existingQuotations
                    as $existingQuotation
                ) {

                    if (
                        !in_array(
                            (int) $existingQuotation->quotation_id,
                            $submittedExistingQuotationIds,
                            true
                        )
                    ) {

                        if (
                            $existingQuotation->quotation_file_path
                        ) {

                            $oldFilesToDelete[] =
                                $existingQuotation->quotation_file_path;
                        }


                        $existingQuotation->delete();
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Adjust Soft Reserved Budget
                |--------------------------------------------------------------------------
                |
                | Old request = RM5,500
                | New request = RM6,000
                |
                | Reserved:
                | reserved - 5,500 + 6,000
                |
                */

                $budget->reserved_amount =
                    round(
                        (float) $budget->reserved_amount
                        - $oldEstimatedAmount
                        + $newEstimatedAmount,
                        2
                    );


                /*
                * Safety against tiny floating point negatives.
                */
                if ($budget->reserved_amount < 0) {

                    $budget->reserved_amount =
                        0;
                }


                $budget->save();

                /*
                |--------------------------------------------------------------------------
                | Audit Log - Procurement Request Resubmitted
                |--------------------------------------------------------------------------
                */

                AuditLog::create([

                    'procurement_request_id' =>
                        $lockedRequest->procurement_request_id,

                    'user_id' =>
                        $user->user_id,

                    'action' =>
                        'Procurement Request Resubmitted',

                    'description' =>
                        'PTJ staff updated and resubmitted procurement request REQ-'
                        . str_pad(
                            $lockedRequest->procurement_request_id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        )
                        . ' for KJ approval. Status changed from Pending_Edit to Pending_KJ.',

                ]);

             });


            /*
            |--------------------------------------------------------------------------
            | Database succeeded — now old files can safely be removed
            |--------------------------------------------------------------------------
            */

            if (!empty($oldFilesToDelete)) {

                Storage::disk('public')
                    ->delete(
                        array_unique(
                            $oldFilesToDelete
                        )
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Module 3 - Queue AI Re-Evaluation After Resubmission
            |--------------------------------------------------------------------------
            |
            | The amended request and quotations are already committed and
            | the request status is now Pending_KJ. Reuse the same unique
            | ai_recommendations row, reset it to Pending, and queue one
            | fresh AI evaluation in the background.
            |
            */

            try {

                $this->aiEvaluationService
                    ->prepareForEvaluation(
                        $procurementRequest->fresh()
                    );

                EvaluateQuotationJob::dispatch(
                    $procurementRequest
                        ->procurement_request_id
                );

            } catch (\Throwable $aiQueueException) {

                /*
                | AI queue failure must not undo a valid PTJ resubmission.
                */

                report($aiQueueException);
            }


            /*
            |--------------------------------------------------------------------------
            | B4 Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('ptj.procurement.index')
                ->with(
                    'success',
                    'Procurement request updated and resubmitted successfully for KJ approval.'
                );


        } catch (ValidationException $exception) {

            /*
            * Any newly uploaded files must be removed because
            * the database transaction was rolled back.
            */

            if (!empty($newStoredFiles)) {

                Storage::disk('public')
                    ->delete(
                        $newStoredFiles
                    );
            }


            throw $exception;


        } catch (\Throwable $exception) {

            if (!empty($newStoredFiles)) {

                Storage::disk('public')
                    ->delete(
                        $newStoredFiles
                    );
            }


            report($exception);


            return back()
                ->withInput()
                ->with(
                    'error',
                    'The procurement request could not be resubmitted. Please try again.'
                );
        }
    }


    /**
     * Ensure PTJ staff cannot access another user's
     * procurement request by manually changing the URL.
     */
    private function ensureRequestBelongsToLoggedInPtj(
        ProcurementRequest $procurementRequest
    ): void {
        $user = Auth::user();

        if (
            $procurementRequest->user_id !== $user->user_id ||
            $procurementRequest->ptj_id !== $user->ptj_id
        ) {
            abort(403, 'You are not authorized to access this procurement request.');
        }
    }
}