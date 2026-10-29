<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\AuditLog;
use App\Models\ProcurementRequest;
use App\Models\PtjBudget;
use App\Models\PtjDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    /**
     * Display the logged-in KJ dashboard using live database data.
     *
     * Dashboard responsibilities:
     * - Show pending KJ approvals from the KJ's own PTJ only.
     * - Count the logged-in KJ's decisions from the last 30 days.
     * - Show the five most recent KJ decisions.
     */
    public function kjDashboard(): View
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Validate KJ PTJ Assignment
        |--------------------------------------------------------------------------
        */

        if (!$user->ptj_id) {
            abort(
                403,
                'Your KJ account is not assigned to a PTJ department.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PTJ Department
        |--------------------------------------------------------------------------
        */

        $department = PtjDepartment::where(
                'ptj_id',
                $user->ptj_id
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Pending KJ Approval Queue
        |--------------------------------------------------------------------------
        |
        | A KJ may only see Pending_KJ requests belonging to their own PTJ.
        |
        */

        $pendingApprovalCount = ProcurementRequest::where(
                'ptj_id',
                $user->ptj_id
            )
            ->where(
                'request_status',
                'Pending_KJ'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Decisions During the Last 30 Days
        |--------------------------------------------------------------------------
        |
        | Count only real KJ decisions recorded by the logged-in KJ.
        | Pending requests are not decision-history records.
        |
        */

        $decisionsLast30Days = Approval::where(
                'user_id',
                $user->user_id
            )
            ->where(
                'approval_stage',
                'KJ'
            )
            ->whereIn(
                'decision',
                [
                    'Approved',
                    'Rejected',
                    'Returned',
                ]
            )
            ->whereHas(
                'procurementRequest',
                function ($query) use ($user) {
                    $query->where(
                        'ptj_id',
                        $user->ptj_id
                    );
                }
            )
            ->where(
                'created_at',
                '>=',
                now()->subDays(30)
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent KJ Decision History
        |--------------------------------------------------------------------------
        */

        $recentDecisions = Approval::with([
                'procurementRequest.ptjDepartment',
            ])
            ->where(
                'user_id',
                $user->user_id
            )
            ->where(
                'approval_stage',
                'KJ'
            )
            ->whereIn(
                'decision',
                [
                    'Approved',
                    'Rejected',
                    'Returned',
                ]
            )
            ->whereHas(
                'procurementRequest',
                function ($query) use ($user) {
                    $query->where(
                        'ptj_id',
                        $user->ptj_id
                    );
                }
            )
            ->latest('created_at')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Time-Based Greeting
        |--------------------------------------------------------------------------
        */

        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };


        return view(
            'dashboards.kj',
            compact(
                'department',
                'pendingApprovalCount',
                'decisionsLast30Days',
                'recentDecisions',
                'greeting'
            )
        );
    }

    /**
     * Display the Bendahari dashboard using live database data.
     *
     * Dashboard responsibilities:
     * - Show the university-wide queue waiting for Bendahari review.
     * - Show Bendahari approvals completed during the current month.
     * - Show the total approved amount for those monthly approvals.
     * - Show a searchable/filterable preview of the Approved_KJ queue.
     */
    public function bendahariDashboard(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Treasury Review Queue Count
        |--------------------------------------------------------------------------
        |
        | Bendahari has university-wide scope. Any request with Approved_KJ is
        | waiting for final Bendahari review.
        |
        */

        $pendingQueueCount = ProcurementRequest::where(
                'request_status',
                'Approved_KJ'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Approved This Month
        |--------------------------------------------------------------------------
        |
        | This card represents university-wide Bendahari approval activity for
        | the current month. Distinct request IDs prevent duplicate approval rows
        | from inflating the dashboard total during testing.
        |
        */

        $approvedRequestIds = Approval::where(
                'approval_stage',
                'Bendahari'
            )
            ->where(
                'decision',
                'Approved'
            )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->whereMonth(
                'created_at',
                now()->month
            )
            ->distinct()
            ->pluck('procurement_request_id');


        $approvedThisMonth =
            $approvedRequestIds->count();


        $approvedAmountThisMonth =
            $approvedRequestIds->isEmpty()
                ? 0
                : (float) ProcurementRequest::whereIn(
                    'procurement_request_id',
                    $approvedRequestIds
                )->sum('approved_amount');


        /*
        |--------------------------------------------------------------------------
        | Active PTJ Departments for Filter
        |--------------------------------------------------------------------------
        */

        $departments = PtjDepartment::where(
                'is_active',
                true
            )
            ->orderBy('department_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard Filters
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->query('search', '')
        );

        $ptjId = $request->filled('ptj_id')
            ? (int) $request->query('ptj_id')
            : null;

        $amountFilter =
            (string) $request->query('amount', 'all');


        if (!in_array(
            $amountFilter,
            [
                'all',
                'under-10000',
                '10000-30000',
                'over-30000',
            ],
            true
        )) {
            $amountFilter = 'all';
        }


        /*
        |--------------------------------------------------------------------------
        | Treasury Queue Preview
        |--------------------------------------------------------------------------
        */

        $queueQuery = ProcurementRequest::with([
                'ptjDepartment',
                'user',
                'selectedQuotation.vendor',
            ])
            ->where(
                'request_status',
                'Approved_KJ'
            );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $queueQuery->where(
                function ($query) use ($search) {

                    $query->where(
                        'request_title',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'ptjDepartment',
                        function ($departmentQuery) use ($search) {

                            $departmentQuery
                                ->where(
                                    'department_name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'department_code',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    );


                    /*
                    | Request ID formats supported:
                    | 6, 0006, REQ-0006, REQ6
                    */

                    $compactSearch = strtoupper(
                        str_replace(
                            [' ', '_'],
                            '',
                            $search
                        )
                    );

                    if (
                        preg_match(
                            '/^(REQ-?)?\\d+$/',
                            $compactSearch
                        )
                    ) {
                        $requestId = (int) preg_replace(
                            '/\\D/',
                            '',
                            $compactSearch
                        );

                        $query->orWhere(
                            'procurement_request_id',
                            $requestId
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($ptjId) {
            $queueQuery->where(
                'ptj_id',
                $ptjId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Approved Amount Filter
        |--------------------------------------------------------------------------
        |
        | Approved_KJ requests already have approved_amount populated by the KJ
        | approval workflow, so the dashboard uses that actual selected quotation
        | amount rather than estimated_amount.
        |
        */

        switch ($amountFilter) {

            case 'under-10000':

                $queueQuery->where(
                    'approved_amount',
                    '<',
                    10000
                );

                break;


            case '10000-30000':

                $queueQuery->whereBetween(
                    'approved_amount',
                    [10000, 30000]
                );

                break;


            case 'over-30000':

                $queueQuery->where(
                    'approved_amount',
                    '>',
                    30000
                );

                break;
        }


        $queueRequests = $queueQuery
            ->latest('updated_at')
            ->paginate(5)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Time-Based Greeting
        |--------------------------------------------------------------------------
        */

        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };


        return view(
            'dashboards.bendahari',
            compact(
                'greeting',
                'pendingQueueCount',
                'approvedThisMonth',
                'approvedAmountThisMonth',
                'departments',
                'queueRequests',
                'search',
                'ptjId',
                'amountFilter'
            )
        );
    }

    /**
     * B1: Display KJ pending approval list.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = ProcurementRequest::with([
            'user',
            'ptjDepartment',
        ])
            ->where('ptj_id', $user->ptj_id)
            ->where('request_status', 'Pending_KJ');

        // Search by request ID, title, or requester name.
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('request_title', 'like', "%{$search}%")
                    ->orWhere(
                        'procurement_request_id',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where(
                            'user_name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }


        // Amount filter.
        if ($request->filled('amount')) {
            switch ($request->amount) {
                case 'below_10000':
                    $query->where('estimated_amount', '<', 10000);
                    break;

                case '10000_30000':
                    $query->whereBetween(
                        'estimated_amount',
                        [10000, 30000]
                    );
                    break;

                case '30001_50000':
                    $query->whereBetween(
                        'estimated_amount',
                        [30001, 50000]
                    );
                    break;
            }
        }

        $pendingRequests = $query
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'approval.approvalPendingList',
            [
                'pendingRequests' => $pendingRequests,
                'approvalRole' => 'kj',
            ]
        );
    }


    /**
     * B1: Display KJ review page.
     */
    public function show(ProcurementRequest $procurementRequest): View
    {
        $user = Auth::user();

        $this->ensureSamePtj(
            $procurementRequest,
            $user->ptj_id
        );

        if ($procurementRequest->request_status !== 'Pending_KJ') {
            abort(
                403,
                'This procurement request is no longer pending KJ approval.'
            );
        }

        $procurementRequest->load([
            'user',
            'ptjDepartment',
            'items',
            'quotations.vendor',
            'aiRecommendation',
        ]);

        return view(
            'approval.approvalDetails',
            compact('procurementRequest')
        );
    }


    /**
     * B2: KJ approves request and selects winning quotation.
     */
    public function approve(
        Request $request,
        ProcurementRequest $procurementRequest
    ): RedirectResponse {
        $user = Auth::user();

        $this->ensureSamePtj(
            $procurementRequest,
            $user->ptj_id
        );

        $validated = $request->validate([
            'selected_quotation_id' => [
                'required',
                'integer',
            ],
        ], [
            'selected_quotation_id.required' =>
                'Please select a vendor quotation before approving.',
        ]);

        DB::transaction(function () use (
            $validated,
            $procurementRequest,
            $user
        ) {
            $lockedRequest = ProcurementRequest::where(
                'procurement_request_id',
                $procurementRequest->procurement_request_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureSamePtj(
                $lockedRequest,
                $user->ptj_id
            );

            if ($lockedRequest->request_status !== 'Pending_KJ') {
                abort(
                    403,
                    'This procurement request has already been processed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate selected quotation
            |--------------------------------------------------------------------------
            */

            $selectedQuotation = $lockedRequest
                ->quotations()
                ->where(
                    'quotation_id',
                    $validated['selected_quotation_id']
                )
                ->first();

            if (!$selectedQuotation) {
                abort(
                    422,
                    'The selected quotation does not belong to this procurement request.'
                );
            }

            $approvedAmount =
                (float) $selectedQuotation->total_amount;

            /*
            |--------------------------------------------------------------------------
            | Direct Purchase limit
            |--------------------------------------------------------------------------
            */

            if ($approvedAmount > 50000) {
                abort(
                    422,
                    'The selected quotation exceeds the RM50,000 Direct Purchase limit.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Budget
            |--------------------------------------------------------------------------
            */

            $budget = PtjBudget::where(
                'ptj_id',
                $lockedRequest->ptj_id
            )
                ->where('budget_year', now()->year)
                ->lockForUpdate()
                ->first();

            if (!$budget) {
                abort(
                    422,
                    'No PTJ budget record was found for the current year.'
                );
            }

            $allocatedAmount =
                (float) $budget->allocated_amount;

            $usedAmount =
                (float) $budget->used_amount;

            $reservedAmount =
                (float) $budget->reserved_amount;

            $estimatedAmount =
                (float) $lockedRequest->estimated_amount;

            /*
            |--------------------------------------------------------------------------
            | Replace estimated reservation with actual quotation amount
            |--------------------------------------------------------------------------
            */

            $newReservedAmount =
                $reservedAmount
                - $estimatedAmount
                + $approvedAmount;

            if ($newReservedAmount < 0) {
                $newReservedAmount = 0;
            }

            if (
                ($usedAmount + $newReservedAmount)
                > $allocatedAmount
            ) {
                abort(
                    422,
                    'Insufficient PTJ budget for the selected quotation.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Update procurement request
            |--------------------------------------------------------------------------
            */

            $lockedRequest->update([
                'selected_quotation_id' =>
                    $selectedQuotation->quotation_id,

                'approved_amount' =>
                    $approvedAmount,

                'request_status' =>
                    'Approved_KJ',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update reserved budget
            |--------------------------------------------------------------------------
            */

            $budget->update([
                'reserved_amount' =>
                    $newReservedAmount,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Store KJ approval
            |--------------------------------------------------------------------------
            */

            Approval::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'approval_stage' =>
                    'KJ',

                'decision' =>
                    'Approved',

                'remarks' =>
                    null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Audit log
            |--------------------------------------------------------------------------
            */

            AuditLog::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'action' =>
                    'KJ Approved Procurement Request',

                'description' =>
                    'KJ approved the request and selected quotation ID '
                    . $selectedQuotation->quotation_id
                    . ' with approved amount RM '
                    . number_format($approvedAmount, 2)
                    . '.',
            ]);
        });

        return redirect()
            ->route('kj.approvals.index')
            ->with(
                'success',
                'Procurement request approved successfully and forwarded to Bendahari.'
            );
    }


    /**
     * A1: KJ rejects procurement request.
     */
    public function reject(
        Request $request,
        ProcurementRequest $procurementRequest
    ): RedirectResponse {
        $user = Auth::user();

        $this->ensureSamePtj(
            $procurementRequest,
            $user->ptj_id
        );

        $validated = $request->validate([
            'remarks' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'remarks.required' =>
                'Please provide the rejection reason.',
        ]);

        DB::transaction(function () use (
            $validated,
            $procurementRequest,
            $user
        ) {
            $lockedRequest = ProcurementRequest::where(
                'procurement_request_id',
                $procurementRequest->procurement_request_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureSamePtj(
                $lockedRequest,
                $user->ptj_id
            );

            if ($lockedRequest->request_status !== 'Pending_KJ') {
                abort(
                    403,
                    'This procurement request has already been processed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Release soft reservation
            |--------------------------------------------------------------------------
            */

            $budget = PtjBudget::where(
                'ptj_id',
                $lockedRequest->ptj_id
            )
                ->where('budget_year', now()->year)
                ->lockForUpdate()
                ->first();

            if (!$budget) {
                abort(
                    422,
                    'No PTJ budget record was found for the current year.'
                );
            }

            $newReservedAmount = max(
                0,
                (float) $budget->reserved_amount
                - (float) $lockedRequest->estimated_amount
            );

            $budget->update([
                'reserved_amount' =>
                    $newReservedAmount,
            ]);

            $lockedRequest->update([
                'selected_quotation_id' => null,
                'approved_amount' => null,
                'request_status' => 'Rejected_KJ',
            ]);

            Approval::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'approval_stage' =>
                    'KJ',

                'decision' =>
                    'Rejected',

                'remarks' =>
                    $validated['remarks'],
            ]);

            AuditLog::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'action' =>
                    'KJ Rejected Procurement Request',

                'description' =>
                    $validated['remarks'],
            ]);
        });

        return redirect()
            ->route('kj.approvals.index')
            ->with(
                'success',
                'Procurement request rejected successfully.'
            );
    }


    /**
     * A2: KJ returns request to PTJ for amendment.
     */
    public function returnForEdit(
        Request $request,
        ProcurementRequest $procurementRequest
    ): RedirectResponse {
        $user = Auth::user();

        $this->ensureSamePtj(
            $procurementRequest,
            $user->ptj_id
        );

        $validated = $request->validate([
            'remarks' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'remarks.required' =>
                'Please enter the amendments required from PTJ.',
        ]);

        DB::transaction(function () use (
            $validated,
            $procurementRequest,
            $user
        ) {
            $lockedRequest = ProcurementRequest::where(
                'procurement_request_id',
                $procurementRequest->procurement_request_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureSamePtj(
                $lockedRequest,
                $user->ptj_id
            );

            if ($lockedRequest->request_status !== 'Pending_KJ') {
                abort(
                    403,
                    'This procurement request has already been processed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Do not release reserved budget.
            |
            | Pending_Edit is still an active procurement request.
            |--------------------------------------------------------------------------
            */

            $lockedRequest->update([
                'selected_quotation_id' => null,
                'approved_amount' => null,
                'request_status' => 'Pending_Edit',
            ]);

            Approval::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'approval_stage' =>
                    'KJ',

                'decision' =>
                    'Returned',

                'remarks' =>
                    $validated['remarks'],
            ]);

            AuditLog::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'action' =>
                    'KJ Returned Procurement Request for Edit',

                'description' =>
                    $validated['remarks'],
            ]);
        });

        return redirect()
            ->route('kj.approvals.index')
            ->with(
                'success',
                'Procurement request returned to PTJ for amendment.'
            );
    }


    /**
     * B3: Display logged-in KJ approval history.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = Approval::with([
            'procurementRequest.ptjDepartment',
            'procurementRequest.user',
            'procurementRequest.selectedQuotation.vendor',
        ])
            ->where('user_id', $user->user_id)
            ->where('approval_stage', 'KJ')
            ->whereHas('procurementRequest', function ($query) use ($user) {
                $query->where('ptj_id', $user->ptj_id);
            });


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas(
                'procurementRequest',
                function ($requestQuery) use ($search) {

                    $requestQuery->where(
                        'request_title',
                        'like',
                        '%' . $search . '%'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Allow numeric Request ID search
                    |--------------------------------------------------------------------------
                    */

                    if (is_numeric($search)) {

                        $requestQuery->orWhere(
                            'procurement_request_id',
                            (int) $search
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Decision Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('decision')
            &&
            in_array(
                $request->decision,
                ['Approved', 'Rejected', 'Returned'],
                true
            )
        ) {

            $query->where(
                'decision',
                $request->decision
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Retrieve History
        |--------------------------------------------------------------------------
        */

        $approvals = $query
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();


        return view(
            'approval.approvalHistory',
            compact('approvals')
        );
    }


    /**
     * Display details of a previous KJ decision.
     */
    public function historyDetails(Approval $approval): View
    {
        $user = Auth::user();

        if (
            $approval->approval_stage !== 'KJ'
            || (int) $approval->user_id !== (int) $user->user_id
        ) {
            abort(
                403,
                'You are not authorised to view this approval record.'
            );
        }

        $approval->load([
            'procurementRequest.user',
            'procurementRequest.ptjDepartment',
            'procurementRequest.items',
            'procurementRequest.quotations.vendor',
            'procurementRequest.selectedQuotation.vendor',
        ]);

        $procurementRequest =
            $approval->procurementRequest;

        $this->ensureSamePtj(
            $procurementRequest,
            $user->ptj_id
        );

        return view(
            'approval.approvalDetails',
            [
                'procurementRequest' =>
                    $procurementRequest,

                'approvalRecord' =>
                    $approval,

                'historyMode' =>
                    true,
            ]
        );
    }

    /**
     * Display Bendahari pending approval list.
     */
    public function bendahariIndex(Request $request): View
    {
        $query = ProcurementRequest::with([
            'user',
            'ptjDepartment',
            'selectedQuotation.vendor',
        ])
            ->where('request_status', 'Approved_KJ');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'request_title',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'procurement_request_id',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'user',
                        function ($userQuery) use ($search) {

                            $userQuery->where(
                                'user_name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    )
                    ->orWhereHas(
                        'ptjDepartment',
                        function ($departmentQuery) use ($search) {

                            $departmentQuery->where(
                                'department_name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PTJ Department Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ptj_id')) {

            $query->where(
                'ptj_id',
                $request->ptj_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Amount Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('amount')) {

            switch ($request->amount) {

                case 'below_10000':

                    $query->where(
                        'approved_amount',
                        '<',
                        10000
                    );

                    break;


                case '10000_30000':

                    $query->whereBetween(
                        'approved_amount',
                        [10000, 30000]
                    );

                    break;


                case '30001_50000':

                    $query->whereBetween(
                        'approved_amount',
                        [30001, 50000]
                    );

                    break;
            }
        }


        $pendingRequests = $query
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $departments = PtjDepartment::where('is_active', true)
            ->orderBy('department_name')
            ->get();

        return view(
            'approval.approvalPendingList',
            [
                'pendingRequests' => $pendingRequests,
                'approvalRole' => 'bendahari',
                'departments' => $departments,
            ]
        );
    }

    /**
     * Display procurement request for Bendahari review.
     */
    public function bendahariShow(
        ProcurementRequest $procurementRequest
    ): View {
        if (
            $procurementRequest->request_status
            !== 'Approved_KJ'
        ) {
            abort(
                403,
                'This procurement request is no longer pending Bendahari approval.'
            );
        }


        $procurementRequest->load([
            'user',
            'ptjDepartment',
            'items',
            'quotations.vendor',
            'selectedQuotation.vendor',
            'aiRecommendation',
            'approvals.user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Retrieve KJ Approval Record
        |--------------------------------------------------------------------------
        */

        $kjApproval = $procurementRequest
            ->approvals()
            ->where('approval_stage', 'KJ')
            ->where('decision', 'Approved')
            ->latest('created_at')
            ->first();


        return view(
            'approval.approvalDetails',
            [
                'procurementRequest' =>
                    $procurementRequest,

                'approvalRole' =>
                    'bendahari',

                'kjApproval' =>
                    $kjApproval,
            ]
        );
    }

    /**
     * Bendahari gives final approval to a KJ-approved request.
     */

    public function bendahariApprove(
        ProcurementRequest $procurementRequest
    ): RedirectResponse {
        $user = Auth::user();

        DB::transaction(function () use (
            $procurementRequest,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Procurement Request
            |--------------------------------------------------------------------------
            */

            $lockedRequest = ProcurementRequest::where(
                'procurement_request_id',
                $procurementRequest->procurement_request_id
            )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Status Validation
            |--------------------------------------------------------------------------
            */

            if (
                $lockedRequest->request_status
                !== 'Approved_KJ'
            ) {
                abort(
                    403,
                    'This procurement request has already been processed or is not awaiting Bendahari approval.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Validate KJ Selected Quotation
            |--------------------------------------------------------------------------
            */

            if (
                !$lockedRequest->selected_quotation_id
                ||
                $lockedRequest->approved_amount === null
            ) {
                abort(
                    422,
                    'The procurement request does not contain a valid KJ-selected quotation.'
                );
            }


            $selectedQuotation = $lockedRequest
                ->quotations()
                ->where(
                    'quotation_id',
                    $lockedRequest->selected_quotation_id
                )
                ->first();


            if (!$selectedQuotation) {
                abort(
                    422,
                    'The KJ-selected quotation does not belong to this procurement request.'
                );
            }


            $approvedAmount =
                (float) $lockedRequest->approved_amount;


            /*
            |--------------------------------------------------------------------------
            | Validate Approved Amount
            |--------------------------------------------------------------------------
            */

            if ($approvedAmount <= 0) {
                abort(
                    422,
                    'The approved procurement amount is invalid.'
                );
            }


            if ($approvedAmount > 50000) {
                abort(
                    422,
                    'The approved amount exceeds the RM50,000 Direct Purchase limit.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Confirm Approved Amount Matches Selected Quotation
            |--------------------------------------------------------------------------
            */

            if (
                abs(
                    $approvedAmount
                    -
                    (float) $selectedQuotation->total_amount
                ) > 0.01
            ) {
                abort(
                    422,
                    'The approved amount does not match the KJ-selected quotation.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Lock PTJ Budget
            |--------------------------------------------------------------------------
            */

            $budget = PtjBudget::where(
                'ptj_id',
                $lockedRequest->ptj_id
            )
                ->where(
                    'budget_year',
                    now()->year
                )
                ->lockForUpdate()
                ->first();


            if (!$budget) {
                abort(
                    422,
                    'No PTJ budget record was found for the current year.'
                );
            }


            $allocatedAmount =
                (float) $budget->allocated_amount;

            $usedAmount =
                (float) $budget->used_amount;

            $reservedAmount =
                (float) $budget->reserved_amount;


            /*
            |--------------------------------------------------------------------------
            | Final Budget Validation
            |--------------------------------------------------------------------------
            |
            | The KJ-approved amount is currently reserved.
            | Bendahari approval converts that reservation into used budget.
            |--------------------------------------------------------------------------
            */

            if ($reservedAmount < $approvedAmount) {
                abort(
                    422,
                    'The PTJ reserved budget is insufficient for this approved procurement amount.'
                );
            }


            $newReservedAmount =
                $reservedAmount - $approvedAmount;

            $newUsedAmount =
                $usedAmount + $approvedAmount;


            if (
                (
                    $newUsedAmount
                    +
                    $newReservedAmount
                )
                > $allocatedAmount
            ) {
                abort(
                    422,
                    'Insufficient PTJ budget for final Bendahari approval.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Budget
            |--------------------------------------------------------------------------
            */

            $budget->update([
                'reserved_amount' =>
                    $newReservedAmount,

                'used_amount' =>
                    $newUsedAmount,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Final Procurement Approval
            |--------------------------------------------------------------------------
            |
            | Do NOT generate the LO PDF here.
            | Approved_Bendahari makes the request available later
            | in the PTJ Active Order workflow.
            |--------------------------------------------------------------------------
            */

            $lockedRequest->update([
                'request_status' =>
                    'Approved_Bendahari',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Store Bendahari Approval
            |--------------------------------------------------------------------------
            */

            Approval::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'approval_stage' =>
                    'Bendahari',

                'decision' =>
                    'Approved',

                'remarks' =>
                    null,
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
                    'Bendahari Approved Procurement Request',

                'description' =>
                    'Bendahari gave final approval for quotation ID '
                    . $lockedRequest->selected_quotation_id
                    . ' with approved amount RM '
                    . number_format(
                        $approvedAmount,
                        2
                    )
                    . '.',
            ]);

        });


        return redirect()
            ->route(
                'bendahari.approvals.index'
            )
            ->with(
                'success',
                'Procurement request approved successfully. The request is now ready for the PTJ Active Order stage.'
            );
    }

    /**
     * Bendahari rejects a KJ-approved procurement request.
     */
    public function bendahariReject(
        Request $request,
        ProcurementRequest $procurementRequest
    ): RedirectResponse {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'remarks' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'remarks.required' =>
                'Please provide the Bendahari rejection reason.',
        ]);


        DB::transaction(function () use (
            $validated,
            $procurementRequest,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Procurement Request
            |--------------------------------------------------------------------------
            */

            $lockedRequest = ProcurementRequest::where(
                'procurement_request_id',
                $procurementRequest->procurement_request_id
            )
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Status Validation
            |--------------------------------------------------------------------------
            */

            if (
                $lockedRequest->request_status
                !== 'Approved_KJ'
            ) {
                abort(
                    403,
                    'This procurement request has already been processed or is not awaiting Bendahari approval.'
                );
            }


            if (
                !$lockedRequest->selected_quotation_id
                ||
                $lockedRequest->approved_amount === null
            ) {
                abort(
                    422,
                    'The procurement request does not contain a valid KJ-approved quotation.'
                );
            }


            $approvedAmount =
                (float) $lockedRequest->approved_amount;


            /*
            |--------------------------------------------------------------------------
            | Lock PTJ Budget
            |--------------------------------------------------------------------------
            */

            $budget = PtjBudget::where(
                'ptj_id',
                $lockedRequest->ptj_id
            )
                ->where(
                    'budget_year',
                    now()->year
                )
                ->lockForUpdate()
                ->first();


            if (!$budget) {
                abort(
                    422,
                    'No PTJ budget record was found for the current year.'
                );
            }


            $reservedAmount =
                (float) $budget->reserved_amount;


            /*
            |--------------------------------------------------------------------------
            | Release Reserved Budget
            |--------------------------------------------------------------------------
            */

            if ($reservedAmount < $approvedAmount) {
                abort(
                    422,
                    'The PTJ reserved budget is inconsistent with this procurement request.'
                );
            }


            $newReservedAmount =
                $reservedAmount - $approvedAmount;


            $budget->update([
                'reserved_amount' =>
                    $newReservedAmount,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Reject Procurement Request
            |--------------------------------------------------------------------------
            |
            | Keep selected_quotation_id and approved_amount.
            | They remain useful as historical evidence of the quotation
            | that KJ selected before Bendahari rejected the request.
            |--------------------------------------------------------------------------
            */

            $lockedRequest->update([
                'request_status' =>
                    'Rejected_Bendahari',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Store Bendahari Rejection
            |--------------------------------------------------------------------------
            */

            Approval::create([
                'procurement_request_id' =>
                    $lockedRequest->procurement_request_id,

                'user_id' =>
                    $user->user_id,

                'approval_stage' =>
                    'Bendahari',

                'decision' =>
                    'Rejected',

                'remarks' =>
                    $validated['remarks'],
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
                    'Bendahari Rejected Procurement Request',

                'description' =>
                    $validated['remarks'],
            ]);

        });


        return redirect()
            ->route(
                'bendahari.approvals.index'
            )
            ->with(
                'success',
                'Procurement request rejected successfully.'
            );
    }

    /**
 * Display logged-in Bendahari approval history.
 */
public function bendahariHistory(Request $request): View
{
    $user = Auth::user();

    $query = Approval::with([
        'procurementRequest.ptjDepartment',
        'procurementRequest.user',
        'procurementRequest.selectedQuotation.vendor',
    ])
        ->where('user_id', $user->user_id)
        ->where('approval_stage', 'Bendahari');


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->whereHas(
            'procurementRequest',
            function ($requestQuery) use ($search) {

                $requestQuery
                    ->where(
                        'request_title',
                        'like',
                        '%' . $search . '%'
                    );

                if (is_numeric($search)) {

                    $requestQuery->orWhere(
                        'procurement_request_id',
                        (int) $search
                    );
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Decision Filter
    |--------------------------------------------------------------------------
    |
    | Bendahari has only Approved / Rejected.
    | Return for Edit belongs to KJ only.
    |--------------------------------------------------------------------------
    */

    if (
        $request->filled('decision')
        &&
        in_array(
            $request->decision,
            ['Approved', 'Rejected'],
            true
        )
    ) {

        $query->where(
            'decision',
            $request->decision
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Retrieve History
    |--------------------------------------------------------------------------
    */

    $approvals = $query
        ->latest('created_at')
        ->paginate(10)
        ->withQueryString();


    return view(
        'approval.approvalHistory',
        [
            'approvals' => $approvals,
            'approvalRole' => 'bendahari',
        ]
    );
}


    /**
     * Display details of a previous Bendahari decision.
     */
    public function bendahariHistoryDetails(
        Approval $approval
    ): View {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (
            $approval->approval_stage !== 'Bendahari'
            ||
            (int) $approval->user_id
                !==
            (int) $user->user_id
        ) {
            abort(
                403,
                'You are not authorised to view this approval record.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Load Related Data
        |--------------------------------------------------------------------------
        */

        $approval->load([
            'procurementRequest.user',
            'procurementRequest.ptjDepartment',
            'procurementRequest.items',
            'procurementRequest.quotations.vendor',
            'procurementRequest.selectedQuotation.vendor',
        ]);


        $procurementRequest =
            $approval->procurementRequest;


        /*
        |--------------------------------------------------------------------------
        | Display Shared Approval Details View
        |--------------------------------------------------------------------------
        */

        return view(
            'approval.approvalDetails',
            [
                'procurementRequest' =>
                    $procurementRequest,

                'approvalRecord' =>
                    $approval,

                'historyMode' =>
                    true,

                'approvalRole' =>
                    'bendahari',
            ]
        );
    }


    /**
     * Prevent KJ from viewing requests belonging to another PTJ.
     */
    private function ensureSamePtj(
        ProcurementRequest $procurementRequest,
        $ptjId
    ): void {
        if (
            !$ptjId
            || (int) $procurementRequest->ptj_id
                !== (int) $ptjId
        ) {
            abort(
                403,
                'You are not authorised to access procurement requests from another PTJ.'
            );
        }
    }
}