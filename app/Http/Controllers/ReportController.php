<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\LocalOrder;
use App\Models\ProcurementRequest;
use App\Models\PtjBudget;
use App\Models\PtjDepartment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    /**
     * Module 7 - Generate Report.
     *
     * Final focused report scope:
     * - PTJ: 1 operational report
     * - KJ: 1 department oversight report
     * - Bendahari: 1 operational/financial report + 1 audit report
     * - Admin: 1 user/system overview report + 1 audit report
     *
     * All report logic remains in this documented controller and the UI remains
     * in resources/views/report/reportGeneration.blade.php.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_if(!$user, 401);

        $reportTypes = match ($user->role) {
            'ptj' => [
                'department_procurement_order' =>
                    'Department Procurement & Order Report',
            ],

            'kj' => [
                'department_procurement_budget_approval' =>
                    'Department Procurement, Budget & Approval Report',
            ],

            'bendahari' => [
                'university_procurement_financial' =>
                    'University Procurement & Financial Operations Report',
                'procurement_approval_audit' =>
                    'Procurement & Approval Audit Report',
            ],

            'admin' => [
                'user_system_overview' =>
                    'User & System Overview Report',
                'system_audit' =>
                    'System Audit Report',
            ],

            default => [],
        };

        abort_if(
            empty($reportTypes),
            403,
            'You are not authorised to access UCPMS reports.'
        );

        $requestedReportType = (string) $request->query(
            'report_type',
            array_key_first($reportTypes)
        );

        $selectedReportType = $this->normaliseLegacyReportType(
            $user->role,
            $requestedReportType
        );

        abort_unless(
            array_key_exists($selectedReportType, $reportTypes),
            403,
            'You are not authorised to access this report type.'
        );

        $exportType = strtolower((string) $request->query('export', ''));

        abort_unless(
            $exportType === '' || in_array($exportType, ['pdf', 'excel'], true),
            422,
            'Please select a valid report export format.'
        );

        $forExport = $exportType !== '';
        $reportGenerated = (string) $request->query('generate') === '1' || $forExport;

        $viewData = [
            'reportTypes' => $reportTypes,
            'selectedReportType' => $selectedReportType,
            'reportGenerated' => $reportGenerated,
            'exportType' => $exportType,
        ];

        if ($user->role === 'ptj') {
            $viewData = array_merge(
                $viewData,
                $this->ptjDepartmentProcurementOrderReport($request, $user, $forExport)
            );
        } elseif ($user->role === 'kj') {
            $viewData = array_merge(
                $viewData,
                $this->kjDepartmentProcurementBudgetApprovalReport($request, $user, $forExport)
            );
        } elseif ($user->role === 'bendahari') {
            if ($selectedReportType === 'university_procurement_financial') {
                $viewData = array_merge(
                    $viewData,
                    $this->bendahariUniversityProcurementFinancialReport($request, $user, $forExport)
                );
            } else {
                $viewData = array_merge(
                    $viewData,
                    $this->bendahariProcurementApprovalAuditReport($request, $user, $forExport)
                );
            }
        } elseif ($user->role === 'admin') {
            if ($selectedReportType === 'user_system_overview') {
                $viewData = array_merge(
                    $viewData,
                    $this->adminUserSystemOverviewReport($request, $user, $forExport)
                );
            } else {
                $viewData = array_merge(
                    $viewData,
                    $this->adminSystemAuditReport($request, $user, $forExport)
                );
            }
        }

        if ($exportType === 'pdf') {
            return $this->exportReportPdf(
                $selectedReportType,
                $reportTypes[$selectedReportType],
                $viewData,
                $user
            );
        }

        if ($exportType === 'excel') {
            return $this->exportReportExcel(
                $selectedReportType,
                $reportTypes[$selectedReportType],
                $viewData,
                $user
            );
        }

        return view('report.reportGeneration', $viewData);
    }

    /**
     * Redirect old report query-string keys to the new focused report scope.
     * This avoids 403 pages when an older generated URL is refreshed.
     */
    private function normaliseLegacyReportType(
        string $role,
        string $reportType
    ): string {
        $legacyMap = [
            'ptj' => [
                'department_procurement_activity' => 'department_procurement_order',
                'department_procurement_budget' => 'department_procurement_order',
                'order_delivery' => 'department_procurement_order',
            ],
            'kj' => [
                'department_procurement_budget' => 'department_procurement_budget_approval',
                'approval' => 'department_procurement_budget_approval',
            ],
            'bendahari' => [
                'university_procurement' => 'university_procurement_financial',
                'ptj_budget_utilization' => 'university_procurement_financial',
                'order_delivery_payment' => 'university_procurement_financial',
                'vendor_performance' => 'university_procurement_financial',
                'approval' => 'procurement_approval_audit',
                'procurement_compliance_audit' => 'procurement_approval_audit',
                'approval_compliance_audit' => 'procurement_approval_audit',
            ],
            'admin' => [
                'procurement_overview' => 'user_system_overview',
                'user_account' => 'user_system_overview',
                'user_system_audit' => 'system_audit',
                'full_system_audit' => 'system_audit',
            ],
        ];

        return $legacyMap[$role][$reportType] ?? $reportType;
    }

    /**
     * PTJ - Department Procurement & Order Report.
     *
     * One focused report combines the PTJ's own procurement activity with the
     * later Local Order / delivery / payment position for the same procurement
     * request cohort. Date filtering is based on procurement request submission.
     */
    private function ptjDepartmentProcurementOrderReport(
        Request $request,
        User $user,
        bool $forExport = false
    ): array {
        abort_unless(
            $user->role === 'ptj',
            403,
            'You are not authorised to access the PTJ report.'
        );

        abort_if(
            !$user->ptj_id,
            403,
            'Your account is not assigned to a PTJ department.'
        );

        $department = $user->ptjDepartment;

        abort_if(
            !$department,
            403,
            'Your PTJ department could not be found.'
        );

        $statusLabels = $this->statusLabels();
        $finalApprovedStatuses = $this->finalApprovedStatuses();

        $validated = $request->validate(
            [
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
                'status' => [
                    'nullable',
                    Rule::in(array_merge(['all'], array_keys($statusLabels))),
                ],
            ],
            [
                'date_to.after_or_equal' =>
                    'Date To must be the same as or later than Date From.',
                'status.in' =>
                    'Please select a valid procurement status.',
            ]
        );

        $dateFrom = $validated['date_from']
            ?? now()->startOfYear()->toDateString();

        $dateTo = $validated['date_to']
            ?? now()->endOfYear()->toDateString();

        $status = $validated['status'] ?? 'all';

        $baseQuery = ProcurementRequest::query()
            ->where('ptj_id', $user->ptj_id)
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($status !== 'all') {
            $baseQuery->where('request_status', $status);
        }

        $totalRequests = (clone $baseQuery)->count();

        $finalApprovedValue = (float) (clone $baseQuery)
            ->whereIn('request_status', $finalApprovedStatuses)
            ->sum('approved_amount');

        $requestsRequiringAction = (clone $baseQuery)
            ->where('request_status', 'Pending_Edit')
            ->count();

        $completedRequests = (clone $baseQuery)
            ->where('request_status', 'Completed')
            ->count();

        $lifecycle = [
            'under_review' => (clone $baseQuery)
                ->whereIn('request_status', ['Pending_KJ', 'Approved_KJ'])
                ->count(),
            'amendment' => (clone $baseQuery)
                ->where('request_status', 'Pending_Edit')
                ->count(),
            'rejected' => (clone $baseQuery)
                ->whereIn('request_status', ['Rejected_KJ', 'Rejected_Bendahari'])
                ->count(),
            'execution' => (clone $baseQuery)
                ->whereIn(
                    'request_status',
                    ['Approved_Bendahari', 'LO_Generated', 'Delivery_Received']
                )
                ->count(),
            'completed' => $completedRequests,
        ];

        $orderBaseQuery = LocalOrder::query()
            ->whereHas(
                'procurementRequest',
                function ($query) use ($user, $dateFrom, $dateTo, $status) {
                    $query
                        ->where('ptj_id', $user->ptj_id)
                        ->whereDate('created_at', '>=', $dateFrom)
                        ->whereDate('created_at', '<=', $dateTo);

                    if ($status !== 'all') {
                        $query->where('request_status', $status);
                    }
                }
            );

        $localOrders = (clone $orderBaseQuery)->count();

        $awaitingDelivery = (clone $orderBaseQuery)
            ->doesntHave('deliveryOrder')
            ->count();

        $paymentPending = (clone $orderBaseQuery)
            ->whereHas(
                'deliveryOrder',
                fn ($query) => $query->where('payment_status', 'Pending')
            )
            ->count();

        $paidOrders = (clone $orderBaseQuery)
            ->whereHas(
                'deliveryOrder',
                fn ($query) => $query->where('payment_status', 'Paid')
            )
            ->count();

        $monthlyRecords = (clone $baseQuery)
            ->selectRaw(
                "DATE_FORMAT(created_at, '%Y-%m') as month_key,
                 COUNT(*) as total_requests,
                 SUM(CASE WHEN request_status IN (
                    'Approved_Bendahari',
                    'LO_Generated',
                    'Delivery_Received',
                    'Completed'
                 ) THEN 1 ELSE 0 END) as final_approved_requests"
            )
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get();

        $monthlyLabels = [];
        $monthlyRequestValues = [];
        $monthlyFinalApprovedValues = [];

        foreach ($monthlyRecords as $record) {
            $monthlyLabels[] = Carbon::createFromFormat('Y-m', $record->month_key)
                ->format('M Y');
            $monthlyRequestValues[] = (int) $record->total_requests;
            $monthlyFinalApprovedValues[] = (int) $record->final_approved_requests;
        }

        $recordsQuery = (clone $baseQuery)
            ->with([
                'user',
                'selectedQuotation.vendor',
                'localOrder.selectedQuotation.vendor',
                'localOrder.deliveryOrder',
            ])
            ->latest('created_at');

        $records = $forExport
            ? $recordsQuery->get()
            : $recordsQuery
                ->paginate(10, ['*'], 'ptj_report_page')
                ->withQueryString();

        return [
            'ptjDepartment' => $department,
            'ptjDateFrom' => $dateFrom,
            'ptjDateTo' => $dateTo,
            'ptjStatus' => $status,
            'ptjStatusLabels' => $statusLabels,
            'ptjFinalApprovedStatuses' => $finalApprovedStatuses,
            'ptjTotalRequests' => $totalRequests,
            'ptjFinalApprovedValue' => $finalApprovedValue,
            'ptjRequestsRequiringAction' => $requestsRequiringAction,
            'ptjCompletedRequests' => $completedRequests,
            'ptjLifecycle' => $lifecycle,
            'ptjLocalOrders' => $localOrders,
            'ptjAwaitingDelivery' => $awaitingDelivery,
            'ptjPaymentPending' => $paymentPending,
            'ptjPaidOrders' => $paidOrders,
            'ptjMonthlyLabels' => $monthlyLabels,
            'ptjMonthlyRequestValues' => $monthlyRequestValues,
            'ptjMonthlyFinalApprovedValues' => $monthlyFinalApprovedValues,
            'ptjReportRecords' => $records,
        ];
    }

    /**
     * KJ - Department Procurement, Budget & Approval Report.
     *
     * Combines the two important KJ responsibilities in one report:
     * department procurement/budget oversight and the logged-in KJ's approval
     * decisions. The same date range is applied to request submission dates and
     * approval decision dates, while the budget snapshot is annual.
     */
    private function kjDepartmentProcurementBudgetApprovalReport(
        Request $request,
        User $user,
        bool $forExport = false
    ): array {
        abort_unless(
            $user->role === 'kj',
            403,
            'You are not authorised to access the KJ report.'
        );

        abort_if(
            !$user->ptj_id,
            403,
            'Your account is not assigned to a PTJ department.'
        );

        $department = $user->ptjDepartment;

        abort_if(
            !$department,
            403,
            'Your PTJ department could not be found.'
        );

        $statusLabels = $this->statusLabels();
        $finalApprovedStatuses = $this->finalApprovedStatuses();
        $decisionLabels = [
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
            'Returned' => 'Returned for Edit',
        ];

        $validated = $request->validate(
            [
                'budget_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
                'status' => [
                    'nullable',
                    Rule::in(array_merge(['all'], array_keys($statusLabels))),
                ],
                'decision' => [
                    'nullable',
                    Rule::in(array_merge(['all'], array_keys($decisionLabels))),
                ],
            ],
            [
                'date_to.after_or_equal' =>
                    'Date To must be the same as or later than Date From.',
                'status.in' =>
                    'Please select a valid procurement status.',
                'decision.in' =>
                    'Please select a valid approval decision.',
            ]
        );

        $budgetYears = PtjBudget::query()
            ->where('ptj_id', $user->ptj_id)
            ->orderByDesc('budget_year')
            ->pluck('budget_year')
            ->unique()
            ->values();

        $budgetYear = (int) (
            $validated['budget_year']
            ?? $budgetYears->first()
            ?? now()->year
        );

        $dateFrom = $validated['date_from'] ?? $budgetYear . '-01-01';
        $dateTo = $validated['date_to'] ?? $budgetYear . '-12-31';
        $status = $validated['status'] ?? 'all';
        $decision = $validated['decision'] ?? 'all';

        $budget = PtjBudget::query()
            ->where('ptj_id', $user->ptj_id)
            ->where('budget_year', $budgetYear)
            ->first();

        $allocated = $budget ? (float) $budget->allocated_amount : 0;
        $used = $budget ? (float) $budget->used_amount : 0;
        $reserved = $budget ? (float) $budget->reserved_amount : 0;
        $available = $allocated - $used - $reserved;

        $utilization = $allocated > 0
            ? round(($used / $allocated) * 100, 2)
            : 0;

        $commitment = $allocated > 0
            ? round((($used + $reserved) / $allocated) * 100, 2)
            : 0;

        $procurementBase = ProcurementRequest::query()
            ->where('ptj_id', $user->ptj_id)
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($status !== 'all') {
            $procurementBase->where('request_status', $status);
        }

        $totalRequests = (clone $procurementBase)->count();
        $finalApprovedValue = (float) (clone $procurementBase)
            ->whereIn('request_status', $finalApprovedStatuses)
            ->sum('approved_amount');
        $completed = (clone $procurementBase)
            ->where('request_status', 'Completed')
            ->count();

        $statusDistribution = (clone $procurementBase)
            ->select('request_status', DB::raw('COUNT(*) as total'))
            ->groupBy('request_status')
            ->orderBy('request_status')
            ->get();

        $statusChartLabels = [];
        $statusChartValues = [];

        foreach ($statusDistribution as $record) {
            $statusChartLabels[] = $statusLabels[$record->request_status]
                ?? $record->request_status;
            $statusChartValues[] = (int) $record->total;
        }

        $approvalBase = Approval::query()
            ->where('user_id', $user->user_id)
            ->where('approval_stage', 'KJ')
            ->whereHas(
                'procurementRequest',
                fn ($query) => $query->where('ptj_id', $user->ptj_id)
            )
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($decision !== 'all') {
            $approvalBase->where('decision', $decision);
        }

        $totalDecisions = (clone $approvalBase)->count();
        $approvedDecisions = (clone $approvalBase)
            ->where('decision', 'Approved')
            ->count();
        $rejectedDecisions = (clone $approvalBase)
            ->where('decision', 'Rejected')
            ->count();
        $returnedDecisions = (clone $approvalBase)
            ->where('decision', 'Returned')
            ->count();

        $approvalRate = $totalDecisions > 0
            ? round(($approvedDecisions / $totalDecisions) * 100, 2)
            : 0;

        $monthlyDecisionRecords = (clone $approvalBase)
            ->selectRaw(
                "DATE_FORMAT(created_at, '%Y-%m') as month_key,
                 decision,
                 COUNT(*) as total"
            )
            ->groupBy('month_key', 'decision')
            ->orderBy('month_key')
            ->get()
            ->groupBy('month_key');

        $monthlyLabels = [];
        $monthlyApproved = [];
        $monthlyRejected = [];
        $monthlyReturned = [];

        foreach ($monthlyDecisionRecords as $monthKey => $records) {
            $monthlyLabels[] = Carbon::createFromFormat('Y-m', $monthKey)
                ->format('M Y');
            $monthlyApproved[] = (int) ($records->firstWhere('decision', 'Approved')?->total ?? 0);
            $monthlyRejected[] = (int) ($records->firstWhere('decision', 'Rejected')?->total ?? 0);
            $monthlyReturned[] = (int) ($records->firstWhere('decision', 'Returned')?->total ?? 0);
        }

        $procurementRecordsQuery = (clone $procurementBase)
            ->with([
                'user',
                'selectedQuotation.vendor',
            ])
            ->latest('created_at');

        $approvalRecordsQuery = (clone $approvalBase)
            ->with([
                'procurementRequest.user',
                'procurementRequest.ptjDepartment',
            ])
            ->latest('created_at');

        $procurementRecords = $forExport
            ? $procurementRecordsQuery->get()
            : $procurementRecordsQuery
                ->paginate(8, ['*'], 'kj_procurement_page')
                ->withQueryString();

        $approvalRecords = $forExport
            ? $approvalRecordsQuery->get()
            : $approvalRecordsQuery
                ->paginate(8, ['*'], 'kj_approval_page')
                ->withQueryString();

        return [
            'kjDepartment' => $department,
            'kjBudgetYears' => $budgetYears,
            'kjBudgetYear' => $budgetYear,
            'kjDateFrom' => $dateFrom,
            'kjDateTo' => $dateTo,
            'kjStatus' => $status,
            'kjStatusLabels' => $statusLabels,
            'kjDecision' => $decision,
            'kjDecisionLabels' => $decisionLabels,
            'kjFinalApprovedStatuses' => $finalApprovedStatuses,
            'kjBudgetRecord' => $budget,
            'kjAllocatedAmount' => $allocated,
            'kjUsedAmount' => $used,
            'kjReservedAmount' => $reserved,
            'kjAvailableBalance' => $available,
            'kjUtilizationPercentage' => $utilization,
            'kjCommitmentPercentage' => $commitment,
            'kjTotalRequests' => $totalRequests,
            'kjFinalApprovedValue' => $finalApprovedValue,
            'kjCompletedProcurements' => $completed,
            'kjStatusChartLabels' => $statusChartLabels,
            'kjStatusChartValues' => $statusChartValues,
            'kjTotalDecisions' => $totalDecisions,
            'kjApprovedDecisions' => $approvedDecisions,
            'kjRejectedDecisions' => $rejectedDecisions,
            'kjReturnedDecisions' => $returnedDecisions,
            'kjApprovalRate' => $approvalRate,
            'kjApprovalMonthlyLabels' => $monthlyLabels,
            'kjApprovalMonthlyApproved' => $monthlyApproved,
            'kjApprovalMonthlyRejected' => $monthlyRejected,
            'kjApprovalMonthlyReturned' => $monthlyReturned,
            'kjProcurementRecords' => $procurementRecords,
            'kjApprovalRecords' => $approvalRecords,
        ];
    }

    /**
     * Bendahari - University Procurement & Financial Operations Report.
     *
     * One university-wide operational report combines the most important
     * procurement, annual PTJ budget and order/payment information. It avoids
     * vendor/audit detail because those do not belong in this executive report.
     */
    private function bendahariUniversityProcurementFinancialReport(
        Request $request,
        User $user,
        bool $forExport = false
    ): array {
        abort_unless(
            $user->role === 'bendahari',
            403,
            'You are not authorised to access Bendahari reports.'
        );

        $departments = $this->departments();
        $statusLabels = $this->statusLabels();
        $finalApprovedStatuses = $this->finalApprovedStatuses();

        $budgetYears = PtjBudget::query()
            ->orderByDesc('budget_year')
            ->pluck('budget_year')
            ->unique()
            ->values();

        $validated = $request->validate(
            [
                'ptj_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('ptj_departments', 'ptj_id'),
                ],
                'budget_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            ],
            [
                'ptj_id.exists' => 'Please select a valid PTJ department.',
                'date_to.after_or_equal' =>
                    'Date To must be the same as or later than Date From.',
            ]
        );

        $ptjId = isset($validated['ptj_id'])
            ? (int) $validated['ptj_id']
            : null;

        $budgetYear = (int) (
            $validated['budget_year']
            ?? $budgetYears->first()
            ?? now()->year
        );

        $dateFrom = $validated['date_from']
            ?? $budgetYear . '-01-01';

        $dateTo = $validated['date_to']
            ?? $budgetYear . '-12-31';

        /* -------------------------------------------------------------
         | Procurement portfolio - date basis: request submission date.
         ------------------------------------------------------------- */

        $procurementBase = ProcurementRequest::query()
            ->whereDate('procurement_requests.created_at', '>=', $dateFrom)
            ->whereDate('procurement_requests.created_at', '<=', $dateTo);

        if ($ptjId) {
            $procurementBase->where('procurement_requests.ptj_id', $ptjId);
        }

        $totalRequests = (clone $procurementBase)->count();
        $finalApprovedValue = (float) (clone $procurementBase)
            ->whereIn('procurement_requests.request_status', $finalApprovedStatuses)
            ->sum('procurement_requests.approved_amount');
        $finalApprovedRequests = (clone $procurementBase)
            ->whereIn('procurement_requests.request_status', $finalApprovedStatuses)
            ->count();
        $completedRequests = (clone $procurementBase)
            ->where('procurement_requests.request_status', 'Completed')
            ->count();
        $activePtjs = (clone $procurementBase)
            ->distinct()
            ->count('procurement_requests.ptj_id');

        $lifecycle = [
            'under_review' => (clone $procurementBase)
                ->whereIn(
                    'procurement_requests.request_status',
                    ['Pending_KJ', 'Approved_KJ']
                )
                ->count(),
            'amendment' => (clone $procurementBase)
                ->where('procurement_requests.request_status', 'Pending_Edit')
                ->count(),
            'rejected' => (clone $procurementBase)
                ->whereIn(
                    'procurement_requests.request_status',
                    ['Rejected_KJ', 'Rejected_Bendahari']
                )
                ->count(),
            'execution' => (clone $procurementBase)
                ->whereIn(
                    'procurement_requests.request_status',
                    ['Approved_Bendahari', 'LO_Generated', 'Delivery_Received']
                )
                ->count(),
            'completed' => $completedRequests,
        ];

        $monthlyRecords = (clone $procurementBase)
            ->selectRaw(
                "DATE_FORMAT(procurement_requests.created_at, '%Y-%m') as month_key,
                 COUNT(*) as total_requests,
                 SUM(CASE WHEN procurement_requests.request_status IN (
                    'Approved_Bendahari',
                    'LO_Generated',
                    'Delivery_Received',
                    'Completed'
                 ) THEN 1 ELSE 0 END) as final_approved_requests"
            )
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get();

        $monthlyLabels = [];
        $monthlyRequestValues = [];
        $monthlyFinalApprovedValues = [];

        foreach ($monthlyRecords as $record) {
            $monthlyLabels[] = Carbon::createFromFormat('Y-m', $record->month_key)
                ->format('M Y');
            $monthlyRequestValues[] = (int) $record->total_requests;
            $monthlyFinalApprovedValues[] = (int) $record->final_approved_requests;
        }

        /* -------------------------------------------------------------
         | Annual PTJ budget position - date basis: selected budget year.
         ------------------------------------------------------------- */

        $budgetQuery = PtjBudget::query()
            ->join(
                'ptj_departments',
                'ptj_budgets.ptj_id',
                '=',
                'ptj_departments.ptj_id'
            )
            ->where('ptj_budgets.budget_year', $budgetYear);

        if ($ptjId) {
            $budgetQuery->where('ptj_budgets.ptj_id', $ptjId);
        }

        $budgetRecords = $budgetQuery
            ->select(
                'ptj_budgets.ptj_id',
                'ptj_budgets.allocated_amount',
                'ptj_budgets.used_amount',
                'ptj_budgets.reserved_amount',
                'ptj_departments.department_name',
                'ptj_departments.department_code'
            )
            ->orderBy('ptj_departments.department_name')
            ->get()
            ->map(function ($record) {
                $allocated = (float) $record->allocated_amount;
                $used = (float) $record->used_amount;
                $reserved = (float) $record->reserved_amount;
                $record->available_amount = $allocated - $used - $reserved;
                $record->utilization_percentage = $allocated > 0
                    ? round(($used / $allocated) * 100, 2)
                    : 0;
                $record->commitment_percentage = $allocated > 0
                    ? round((($used + $reserved) / $allocated) * 100, 2)
                    : 0;
                return $record;
            });

        $totalAllocated = (float) $budgetRecords->sum('allocated_amount');
        $totalUsed = (float) $budgetRecords->sum('used_amount');
        $totalReserved = (float) $budgetRecords->sum('reserved_amount');
        $totalAvailable = (float) $budgetRecords->sum('available_amount');
        $overallUtilization = $totalAllocated > 0
            ? round(($totalUsed / $totalAllocated) * 100, 2)
            : 0;

        /* -------------------------------------------------------------
         | Order / delivery / payment - date basis: Local Order date.
         ------------------------------------------------------------- */

        $orderBase = LocalOrder::query()
            ->whereDate('local_orders.lo_date', '>=', $dateFrom)
            ->whereDate('local_orders.lo_date', '<=', $dateTo);

        if ($ptjId) {
            $orderBase->whereHas(
                'procurementRequest',
                fn ($query) => $query->where('ptj_id', $ptjId)
            );
        }

        $totalOrders = (clone $orderBase)->count();
        $awaitingDelivery = (clone $orderBase)
            ->doesntHave('deliveryOrder')
            ->count();
        $paymentPending = (clone $orderBase)
            ->whereHas(
                'deliveryOrder',
                fn ($query) => $query->where('payment_status', 'Pending')
            )
            ->count();
        $paymentPendingValue = (float) (clone $orderBase)
            ->whereHas(
                'deliveryOrder',
                fn ($query) => $query->where('payment_status', 'Pending')
            )
            ->sum('total_amount');
        $paidOrders = (clone $orderBase)
            ->whereHas(
                'deliveryOrder',
                fn ($query) => $query->where('payment_status', 'Paid')
            )
            ->count();
        $paidValue = (float) (clone $orderBase)
            ->whereHas(
                'deliveryOrder',
                fn ($query) => $query->where('payment_status', 'Paid')
            )
            ->sum('total_amount');

        /* -------------------------------------------------------------
         | PTJ executive portfolio - one focused comparison table.
         ------------------------------------------------------------- */

        $procurementPortfolioQuery = ProcurementRequest::query()
            ->whereDate('procurement_requests.created_at', '>=', $dateFrom)
            ->whereDate('procurement_requests.created_at', '<=', $dateTo);

        if ($ptjId) {
            $procurementPortfolioQuery->where('procurement_requests.ptj_id', $ptjId);
        }

        $procurementPortfolio = $procurementPortfolioQuery
            ->select('procurement_requests.ptj_id')
            ->selectRaw('COUNT(*) as request_count')
            ->selectRaw(
                "SUM(CASE WHEN procurement_requests.request_status IN (
                    'Approved_Bendahari',
                    'LO_Generated',
                    'Delivery_Received',
                    'Completed'
                ) THEN COALESCE(procurement_requests.approved_amount, 0)
                ELSE 0 END) as final_approved_value"
            )
            ->selectRaw(
                "SUM(CASE WHEN procurement_requests.request_status = 'Completed'
                    THEN 1 ELSE 0 END) as completed_count"
            )
            ->groupBy('procurement_requests.ptj_id')
            ->get()
            ->keyBy('ptj_id');

        $orderPortfolio = DB::table('local_orders')
            ->join(
                'procurement_requests',
                'local_orders.procurement_request_id',
                '=',
                'procurement_requests.procurement_request_id'
            )
            ->leftJoin(
                'delivery_orders',
                'local_orders.local_order_id',
                '=',
                'delivery_orders.local_order_id'
            )
            ->whereDate('local_orders.lo_date', '>=', $dateFrom)
            ->whereDate('local_orders.lo_date', '<=', $dateTo);

        if ($ptjId) {
            $orderPortfolio->where('procurement_requests.ptj_id', $ptjId);
        }

        $orderPortfolio = $orderPortfolio
            ->select('procurement_requests.ptj_id')
            ->selectRaw('COUNT(local_orders.local_order_id) as order_count')
            ->selectRaw(
                "SUM(CASE WHEN delivery_orders.payment_status = 'Pending'
                    THEN local_orders.total_amount ELSE 0 END) as pending_payment_value"
            )
            ->selectRaw(
                "SUM(CASE WHEN delivery_orders.payment_status = 'Paid'
                    THEN local_orders.total_amount ELSE 0 END) as paid_value"
            )
            ->groupBy('procurement_requests.ptj_id')
            ->get()
            ->keyBy('ptj_id');

        $budgetByPtj = $budgetRecords->keyBy('ptj_id');

        $scopeDepartments = $departments
            ->when(
                $ptjId,
                fn (Collection $collection) =>
                    $collection->where('ptj_id', $ptjId)
            )
            ->values();

        $ptjPortfolio = $scopeDepartments
            ->map(function ($department) use (
                $procurementPortfolio,
                $orderPortfolio,
                $budgetByPtj
            ) {
                $procurement = $procurementPortfolio->get($department->ptj_id);
                $order = $orderPortfolio->get($department->ptj_id);
                $budget = $budgetByPtj->get($department->ptj_id);

                return (object) [
                    'ptj_id' => $department->ptj_id,
                    'department_name' => $department->department_name,
                    'department_code' => $department->department_code,
                    'allocated_amount' => (float) ($budget->allocated_amount ?? 0),
                    'used_amount' => (float) ($budget->used_amount ?? 0),
                    'reserved_amount' => (float) ($budget->reserved_amount ?? 0),
                    'available_amount' => (float) ($budget->available_amount ?? 0),
                    'utilization_percentage' => (float) ($budget->utilization_percentage ?? 0),
                    'request_count' => (int) ($procurement->request_count ?? 0),
                    'final_approved_value' => (float) ($procurement->final_approved_value ?? 0),
                    'completed_count' => (int) ($procurement->completed_count ?? 0),
                    'order_count' => (int) ($order->order_count ?? 0),
                    'pending_payment_value' => (float) ($order->pending_payment_value ?? 0),
                    'paid_value' => (float) ($order->paid_value ?? 0),
                ];
            })
            ->filter(function ($row) {
                return $row->allocated_amount > 0
                    || $row->used_amount > 0
                    || $row->reserved_amount > 0
                    || $row->request_count > 0
                    || $row->order_count > 0
                    || $row->pending_payment_value > 0
                    || $row->paid_value > 0;
            })
            ->sortByDesc('final_approved_value')
            ->values();

        $procurementRecordsQuery = (clone $procurementBase)
            ->with([
                'user',
                'ptjDepartment',
            ])
            ->latest('procurement_requests.created_at');

        $procurementRecords = $forExport
            ? $procurementRecordsQuery->get(['procurement_requests.*'])
            : $procurementRecordsQuery
                ->paginate(10, ['procurement_requests.*'], 'bend_financial_page')
                ->withQueryString();

        return [
            'bendDepartments' => $departments,
            'bendPtjId' => $ptjId,
            'bendBudgetYears' => $budgetYears,
            'bendBudgetYear' => $budgetYear,
            'bendDateFrom' => $dateFrom,
            'bendDateTo' => $dateTo,
            'bendStatusLabels' => $statusLabels,
            'bendFinalApprovedStatuses' => $finalApprovedStatuses,
            'bendTotalRequests' => $totalRequests,
            'bendFinalApprovedValue' => $finalApprovedValue,
            'bendFinalApprovedRequests' => $finalApprovedRequests,
            'bendCompletedRequests' => $completedRequests,
            'bendActivePtjs' => $activePtjs,
            'bendLifecycle' => $lifecycle,
            'bendMonthlyLabels' => $monthlyLabels,
            'bendMonthlyRequestValues' => $monthlyRequestValues,
            'bendMonthlyFinalApprovedValues' => $monthlyFinalApprovedValues,
            'bendBudgetRecords' => $budgetRecords,
            'bendTotalAllocated' => $totalAllocated,
            'bendTotalUsed' => $totalUsed,
            'bendTotalReserved' => $totalReserved,
            'bendTotalAvailable' => $totalAvailable,
            'bendOverallUtilization' => $overallUtilization,
            'bendTotalOrders' => $totalOrders,
            'bendAwaitingDelivery' => $awaitingDelivery,
            'bendPaymentPending' => $paymentPending,
            'bendPaymentPendingValue' => $paymentPendingValue,
            'bendPaidOrders' => $paidOrders,
            'bendPaidValue' => $paidValue,
            'bendPtjPortfolio' => $ptjPortfolio,
            'bendProcurementRecords' => $procurementRecords,
        ];
    }

    /**
     * Bendahari - Procurement & Approval Audit Report.
     *
     * This is deliberately formal and evidence-focused. It combines the
     * Bendahari approval decision register with current-record compliance checks
     * and timestamped procurement audit logs.
     */
    private function bendahariProcurementApprovalAuditReport(
        Request $request,
        User $user,
        bool $forExport = false
    ): array {
        abort_unless(
            $user->role === 'bendahari',
            403,
            'You are not authorised to access the Bendahari audit report.'
        );

        $departments = $this->departments();
        $decisionLabels = [
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
        ];
        $finalApprovedStatuses = $this->finalApprovedStatuses();

        $validated = $request->validate(
            [
                'ptj_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('ptj_departments', 'ptj_id'),
                ],
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
                'decision' => [
                    'nullable',
                    Rule::in(array_merge(['all'], array_keys($decisionLabels))),
                ],
            ],
            [
                'ptj_id.exists' => 'Please select a valid PTJ department.',
                'date_to.after_or_equal' =>
                    'Date To must be the same as or later than Date From.',
                'decision.in' =>
                    'Please select a valid Bendahari decision.',
            ]
        );

        $ptjId = isset($validated['ptj_id'])
            ? (int) $validated['ptj_id']
            : null;

        $dateFrom = $validated['date_from']
            ?? now()->startOfYear()->toDateString();

        $dateTo = $validated['date_to']
            ?? now()->endOfYear()->toDateString();

        $decision = $validated['decision'] ?? 'all';

        $approvalBase = Approval::query()
            ->where('user_id', $user->user_id)
            ->where('approval_stage', 'Bendahari')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($ptjId) {
            $approvalBase->whereHas(
                'procurementRequest',
                fn ($query) => $query->where('ptj_id', $ptjId)
            );
        }

        if ($decision !== 'all') {
            $approvalBase->where('decision', $decision);
        }

        $totalDecisions = (clone $approvalBase)->count();

        $requestIds = (clone $approvalBase)
            ->pluck('procurement_request_id')
            ->unique()
            ->values();

        $complianceRecords = $requestIds->isEmpty()
            ? collect()
            : ProcurementRequest::query()
                ->whereIn('procurement_request_id', $requestIds)
                ->withCount('quotations')
                ->with('ptjDepartment')
                ->get();

        $directPurchaseExceptions = $complianceRecords->filter(
            function ($record) {
                return (float) $record->estimated_amount > 50000
                    || (
                        $record->approved_amount !== null
                        && (float) $record->approved_amount > 50000
                    );
            }
        );

        $quotationExceptions = $complianceRecords->filter(
            fn ($record) => (int) $record->quotations_count < 3
        );

        $integrityExceptions = $complianceRecords->filter(
            function ($record) use ($finalApprovedStatuses) {
                return in_array(
                    $record->request_status,
                    $finalApprovedStatuses,
                    true
                )
                    && (
                        !$record->selected_quotation_id
                        || $record->approved_amount === null
                    );
            }
        );

        $exceptionIds = collect()
            ->merge($directPurchaseExceptions->pluck('procurement_request_id'))
            ->merge($quotationExceptions->pluck('procurement_request_id'))
            ->merge($integrityExceptions->pluck('procurement_request_id'))
            ->unique()
            ->values();

        $decisionRecordsQuery = (clone $approvalBase)
            ->with([
                'procurementRequest.user',
                'procurementRequest.ptjDepartment',
                'procurementRequest.selectedQuotation.vendor',
            ])
            ->latest('created_at');

        $decisionRecords = $forExport
            ? $decisionRecordsQuery->get()
            : $decisionRecordsQuery
                ->paginate(8, ['*'], 'bend_audit_decision_page')
                ->withQueryString();

        $auditQuery = DB::table('audit_logs')
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->leftJoin(
                'procurement_requests',
                'audit_logs.procurement_request_id',
                '=',
                'procurement_requests.procurement_request_id'
            )
            ->leftJoin(
                'ptj_departments',
                'procurement_requests.ptj_id',
                '=',
                'ptj_departments.ptj_id'
            )
            ->whereNotNull('audit_logs.procurement_request_id')
            ->whereDate('audit_logs.created_at', '>=', $dateFrom)
            ->whereDate('audit_logs.created_at', '<=', $dateTo);

        if ($requestIds->isNotEmpty()) {
            $auditQuery->whereIn('audit_logs.procurement_request_id', $requestIds);
        } else {
            $auditQuery->whereRaw('1 = 0');
        }

        if ($ptjId) {
            $auditQuery->where('procurement_requests.ptj_id', $ptjId);
        }

        $auditRecordsQuery = $auditQuery
            ->select(
                'audit_logs.audit_log_id',
                'audit_logs.procurement_request_id',
                'audit_logs.action',
                'audit_logs.description',
                'audit_logs.created_at',
                'users.user_name as actor_name',
                'users.staff_id as actor_staff_id',
                'users.role as actor_role',
                'procurement_requests.request_title',
                'ptj_departments.department_name'
            )
            ->orderByDesc('audit_logs.created_at');

        $auditRecords = $forExport
            ? $auditRecordsQuery->get()
            : $auditRecordsQuery
                ->paginate(10, ['*'], 'bend_audit_log_page')
                ->withQueryString();

        return [
            'bendAuditDepartments' => $departments,
            'bendAuditPtjId' => $ptjId,
            'bendAuditDateFrom' => $dateFrom,
            'bendAuditDateTo' => $dateTo,
            'bendAuditDecision' => $decision,
            'bendAuditDecisionLabels' => $decisionLabels,
            'bendAuditTotalDecisions' => $totalDecisions,
            'bendAuditCheckedRequestCount' => $complianceRecords->count(),
            'bendAuditDirectPurchaseExceptions' => $directPurchaseExceptions,
            'bendAuditQuotationExceptions' => $quotationExceptions,
            'bendAuditIntegrityExceptions' => $integrityExceptions,
            'bendAuditExceptionCount' => $exceptionIds->count(),
            'bendAuditDecisionRecords' => $decisionRecords,
            'bendAuditRecords' => $auditRecords,
        ];
    }

    /**
     * Admin - User & System Overview Report.
     *
     * Focuses only on current user/account structure. Audit evidence is kept in
     * the separate System Audit Report so the normal Admin report stays concise.
     */
    private function adminUserSystemOverviewReport(
        Request $request,
        User $user,
        bool $forExport = false
    ): array {
        abort_unless(
            $user->role === 'admin',
            403,
            'You are not authorised to access the Admin overview report.'
        );

        $departments = $this->departments();
        $roleLabels = $this->roleLabels();

        $validated = $request->validate(
            [
                'role_filter' => [
                    'nullable',
                    Rule::in(array_merge(['all'], array_keys($roleLabels))),
                ],
                'account_status' => [
                    'nullable',
                    Rule::in(['all', 'active', 'inactive']),
                ],
                'ptj_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('ptj_departments', 'ptj_id'),
                ],
            ],
            [
                'role_filter.in' => 'Please select a valid user role.',
                'account_status.in' => 'Please select a valid account status.',
                'ptj_id.exists' => 'Please select a valid PTJ department.',
            ]
        );

        $role = $validated['role_filter'] ?? 'all';
        $accountStatus = $validated['account_status'] ?? 'all';
        $ptjId = isset($validated['ptj_id'])
            ? (int) $validated['ptj_id']
            : null;

        $baseQuery = User::query();

        if ($role !== 'all') {
            $baseQuery->where('role', $role);
        }

        if ($accountStatus === 'active') {
            $baseQuery->where('is_active', true);
        } elseif ($accountStatus === 'inactive') {
            $baseQuery->where('is_active', false);
        }

        if ($ptjId) {
            $baseQuery->where('ptj_id', $ptjId);
        }

        $totalUsers = (clone $baseQuery)->count();
        $activeUsers = (clone $baseQuery)->where('is_active', true)->count();
        $inactiveUsers = (clone $baseQuery)->where('is_active', false)->count();
        $protectedAdminUsers = (clone $baseQuery)->where('role', 'admin')->count();
        $activeRate = $totalUsers > 0
            ? round(($activeUsers / $totalUsers) * 100, 2)
            : 0;

        $roleDistribution = (clone $baseQuery)
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderBy('role')
            ->get()
            ->map(function ($record) use ($roleLabels, $totalUsers) {
                $record->label = $roleLabels[$record->role] ?? $record->role;
                $record->percentage = $totalUsers > 0
                    ? round(((int) $record->total / $totalUsers) * 100, 2)
                    : 0;
                return $record;
            });

        $departmentDistributionQuery = User::query()
            ->join(
                'ptj_departments',
                'users.ptj_id',
                '=',
                'ptj_departments.ptj_id'
            );

        if ($role !== 'all') {
            $departmentDistributionQuery->where('users.role', $role);
        }

        if ($accountStatus === 'active') {
            $departmentDistributionQuery->where('users.is_active', true);
        } elseif ($accountStatus === 'inactive') {
            $departmentDistributionQuery->where('users.is_active', false);
        }

        if ($ptjId) {
            $departmentDistributionQuery->where('users.ptj_id', $ptjId);
        }

        $departmentDistribution = $departmentDistributionQuery
            ->select(
                'ptj_departments.ptj_id',
                'ptj_departments.department_name',
                'ptj_departments.department_code'
            )
            ->selectRaw('COUNT(users.user_id) as total_users')
            ->selectRaw(
                "SUM(CASE WHEN users.role = 'ptj' THEN 1 ELSE 0 END) as ptj_users"
            )
            ->selectRaw(
                "SUM(CASE WHEN users.role = 'kj' THEN 1 ELSE 0 END) as kj_users"
            )
            ->groupBy(
                'ptj_departments.ptj_id',
                'ptj_departments.department_name',
                'ptj_departments.department_code'
            )
            ->orderBy('ptj_departments.department_name')
            ->get();

        $usersQuery = (clone $baseQuery)
            ->with('ptjDepartment')
            ->orderBy('role')
            ->orderBy('user_name');

        $users = $forExport
            ? $usersQuery->get()
            : $usersQuery
                ->paginate(12, ['*'], 'admin_users_page')
                ->withQueryString();

        return [
            'adminDepartments' => $departments,
            'adminRoleLabels' => $roleLabels,
            'adminRoleFilter' => $role,
            'adminAccountStatus' => $accountStatus,
            'adminPtjId' => $ptjId,
            'adminTotalUsers' => $totalUsers,
            'adminActiveUsers' => $activeUsers,
            'adminInactiveUsers' => $inactiveUsers,
            'adminProtectedAdminUsers' => $protectedAdminUsers,
            'adminActiveRate' => $activeRate,
            'adminRoleDistribution' => $roleDistribution,
            'adminDepartmentDistribution' => $departmentDistribution,
            'adminUsers' => $users,
        ];
    }

    /**
     * Admin - System Audit Report.
     *
     * Read-only evidence of significant system actions. The category filter is
     * based on the audit action strings currently written by UCPMS modules.
     */
    private function adminSystemAuditReport(
        Request $request,
        User $user,
        bool $forExport = false
    ): array {
        abort_unless(
            $user->role === 'admin',
            403,
            'You are not authorised to access the System Audit Report.'
        );

        $roleLabels = $this->roleLabels();

        $categoryLabels = [
            'all' => 'All Audit Events',
            'user_management' => 'User Management',
            'procurement' => 'Procurement Requests',
            'approval' => 'Approvals',
            'execution' => 'Order & Delivery',
            'payment' => 'Payment',
        ];

        $categoryActions = [
            'user_management' => [
                'User Account Created',
                'User Assignment Updated',
                'User Account Reactivated',
                'User Account Deactivated',
            ],
            'procurement' => [
                'Procurement Request Submitted',
                'Procurement Request Resubmitted',
            ],
            'approval' => [
                'KJ Approved Procurement Request',
                'KJ Rejected Procurement Request',
                'KJ Returned Procurement Request for Edit',
                'Bendahari Approved Procurement Request',
                'Bendahari Rejected Procurement Request',
            ],
            'execution' => [
                'PTJ Generated Local Order',
                'PTJ Confirmed Delivery',
            ],
            'payment' => [
                'Bendahari Marked Payment as Paid',
            ],
        ];

        $validated = $request->validate(
            [
                'date_from' => ['nullable', 'date'],
                'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
                'audit_category' => [
                    'nullable',
                    Rule::in(array_keys($categoryLabels)),
                ],
            ],
            [
                'date_to.after_or_equal' =>
                    'Date To must be the same as or later than Date From.',
                'audit_category.in' =>
                    'Please select a valid audit category.',
            ]
        );

        $dateFrom = $validated['date_from']
            ?? now()->startOfYear()->toDateString();
        $dateTo = $validated['date_to']
            ?? now()->endOfYear()->toDateString();
        $category = $validated['audit_category'] ?? 'all';

        $baseQuery = DB::table('audit_logs')
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->leftJoin(
                'procurement_requests',
                'audit_logs.procurement_request_id',
                '=',
                'procurement_requests.procurement_request_id'
            )
            ->leftJoin(
                'ptj_departments',
                'procurement_requests.ptj_id',
                '=',
                'ptj_departments.ptj_id'
            )
            ->whereDate('audit_logs.created_at', '>=', $dateFrom)
            ->whereDate('audit_logs.created_at', '<=', $dateTo);

        if ($category !== 'all') {
            $baseQuery->whereIn(
                'audit_logs.action',
                $categoryActions[$category] ?? []
            );
        }

        $totalEvents = (clone $baseQuery)->count();

        $userManagementEvents = (clone $baseQuery)
            ->whereIn('audit_logs.action', $categoryActions['user_management'])
            ->count();

        $approvalEvents = (clone $baseQuery)
            ->whereIn('audit_logs.action', $categoryActions['approval'])
            ->count();

        $executionEvents = (clone $baseQuery)
            ->whereIn('audit_logs.action', $categoryActions['execution'])
            ->count();

        $paymentEvents = (clone $baseQuery)
            ->whereIn('audit_logs.action', $categoryActions['payment'])
            ->count();

        $actionDistribution = (clone $baseQuery)
            ->select('audit_logs.action', DB::raw('COUNT(*) as total'))
            ->groupBy('audit_logs.action')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $maxActionCount = (int) ($actionDistribution->max('total') ?? 0);

        $recordsQuery = (clone $baseQuery)
            ->select(
                'audit_logs.audit_log_id',
                'audit_logs.procurement_request_id',
                'audit_logs.action',
                'audit_logs.description',
                'audit_logs.created_at',
                'users.user_name as actor_name',
                'users.staff_id as actor_staff_id',
                'users.role as actor_role',
                'procurement_requests.request_title',
                'ptj_departments.department_name'
            )
            ->orderByDesc('audit_logs.created_at');

        $records = $forExport
            ? $recordsQuery->get()
            : $recordsQuery
                ->paginate(14, ['*'], 'admin_audit_page')
                ->withQueryString();

        return [
            'adminAuditRoleLabels' => $roleLabels,
            'adminAuditCategoryLabels' => $categoryLabels,
            'adminAuditCategory' => $category,
            'adminAuditDateFrom' => $dateFrom,
            'adminAuditDateTo' => $dateTo,
            'adminAuditTotalEvents' => $totalEvents,
            'adminAuditUserManagementEvents' => $userManagementEvents,
            'adminAuditApprovalEvents' => $approvalEvents,
            'adminAuditExecutionEvents' => $executionEvents,
            'adminAuditPaymentEvents' => $paymentEvents,
            'adminAuditActionDistribution' => $actionDistribution,
            'adminAuditMaxActionCount' => $maxActionCount,
            'adminAuditRecords' => $records,
        ];
    }

    /**
     * Export the currently filtered report as a formal PDF.
     *
     * No extra Blade view is introduced: export HTML is assembled here so
     * Module 7 continues to use only ReportController.php and
     * reportGeneration.blade.php as requested.
     */
    private function exportReportPdf(
        string $reportType,
        string $reportTitle,
        array $data,
        User $user
    ) {
        $definition = $this->reportExportDefinition(
            $reportType,
            $reportTitle,
            $data,
            $user
        );

        $html = $this->buildReportPdfHtml($definition);
        $filename = $this->reportExportFilename($reportTitle, 'pdf');

        return Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    /**
     * Export the currently filtered report as an Excel workbook.
     *
     * The workbook contains a summary sheet plus one sheet per detailed
     * register. All records matching the filters are exported, not only the
     * current paginated browser page.
     */
    private function exportReportExcel(
        string $reportType,
        string $reportTitle,
        array $data,
        User $user
    ) {
        $definition = $this->reportExportDefinition(
            $reportType,
            $reportTitle,
            $data,
            $user
        );

        $spreadsheet = new Spreadsheet();

        /*
        |--------------------------------------------------------------------------
        | Formal Excel Register Export
        |--------------------------------------------------------------------------
        |
        | Excel exports are intentionally register-focused rather than dashboard-
        | focused. Each meaningful report table receives its own worksheet with
        | the report identity, applied filters and the complete filtered register.
        | No separate KPI/summary worksheet is created.
        |--------------------------------------------------------------------------
        */

        $tables = $definition['tables'];

        if (empty($tables)) {
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Report');
            $this->writeExcelEmptyReportSheet($sheet, $definition);
        } else {
            foreach ($tables as $index => $table) {
                $sheet = $index === 0
                    ? $spreadsheet->getActiveSheet()
                    : $spreadsheet->createSheet();

                $sheet->setTitle($this->excelSheetTitle($table['title']));
                $this->writeExcelTableSheet($sheet, $table, $definition);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        $filename = $this->reportExportFilename($reportTitle, 'xlsx');

        return response()->streamDownload(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                $spreadsheet->disconnectWorksheets();
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    /**
     * Build one neutral export definition that is shared by PDF and Excel.
     * This avoids duplicating report calculations in two different exporters.
     */
    private function reportExportDefinition(
        string $reportType,
        string $reportTitle,
        array $data,
        User $user
    ): array {
        $roleLabel = $this->roleLabels()[$user->role] ?? ucfirst($user->role);

        $definition = [
            'title' => $reportTitle,
            'role' => $roleLabel,
            'generated_by' => $user->user_name . ' (' . $user->staff_id . ')',
            'generated_at' => now()->format('d M Y, h:i A'),
            'filters' => [],
            'summary' => [],
            'tables' => [],
        ];

        if ($reportType === 'department_procurement_order') {
            $statusLabel = $data['ptjStatus'] === 'all'
                ? 'All Statuses'
                : ($data['ptjStatusLabels'][$data['ptjStatus']] ?? $data['ptjStatus']);

            $definition['filters'] = [
                'PTJ Department' => $data['ptjDepartment']->department_name,
                'Date Range' => $this->dateRangeLabel($data['ptjDateFrom'], $data['ptjDateTo']),
                'Procurement Status' => $statusLabel,
            ];

            $definition['summary'] = [
                ['label' => 'Procurement Requests', 'value' => $data['ptjTotalRequests'], 'type' => 'integer'],
                ['label' => 'Final Approved Value', 'value' => $data['ptjFinalApprovedValue'], 'type' => 'currency'],
                ['label' => 'Amendment Required', 'value' => $data['ptjRequestsRequiringAction'], 'type' => 'integer'],
                ['label' => 'Completed', 'value' => $data['ptjCompletedRequests'], 'type' => 'integer'],
                ['label' => 'Local Orders', 'value' => $data['ptjLocalOrders'], 'type' => 'integer'],
                ['label' => 'Awaiting Delivery', 'value' => $data['ptjAwaitingDelivery'], 'type' => 'integer'],
                ['label' => 'Payment Pending', 'value' => $data['ptjPaymentPending'], 'type' => 'integer'],
                ['label' => 'Paid Orders', 'value' => $data['ptjPaidOrders'], 'type' => 'integer'],
            ];

            $rows = [];
            foreach ($data['ptjReportRecords'] as $record) {
                $isFinal = in_array(
                    $record->request_status,
                    $data['ptjFinalApprovedStatuses'],
                    true
                );

                $localOrder = $record->localOrder;
                $deliveryOrder = $localOrder?->deliveryOrder;

                if (!$localOrder) {
                    $execution = 'Not Yet Ordered';
                } elseif (!$deliveryOrder) {
                    $execution = 'Awaiting Delivery';
                } elseif ($deliveryOrder->payment_status === 'Paid') {
                    $execution = 'Paid';
                } else {
                    $execution = 'Payment Pending';
                }

                $rows[] = [
                    'request' => 'REQ-' . str_pad($record->procurement_request_id, 4, '0', STR_PAD_LEFT),
                    'submitted' => $record->created_at?->format('d M Y'),
                    'title' => $record->request_title,
                    'estimated' => (float) $record->estimated_amount,
                    'approved' => $isFinal && $record->approved_amount !== null
                        ? (float) $record->approved_amount
                        : null,
                    'vendor' => $record->selectedQuotation?->vendor?->company_name ?? 'Not Selected',
                    'local_order' => $localOrder?->lo_number,
                    'execution' => $execution,
                    'status' => $data['ptjStatusLabels'][$record->request_status]
                        ?? $record->request_status,
                ];
            }

            $definition['tables'][] = [
                'title' => 'Procurement & Order Register',
                'columns' => [
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'submitted', 'label' => 'Submitted', 'type' => 'text'],
                    ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['key' => 'estimated', 'label' => 'Estimated Amount', 'type' => 'currency'],
                    ['key' => 'approved', 'label' => 'Final Approved', 'type' => 'currency'],
                    ['key' => 'vendor', 'label' => 'Selected Vendor', 'type' => 'text'],
                    ['key' => 'local_order', 'label' => 'Local Order', 'type' => 'text'],
                    ['key' => 'execution', 'label' => 'Execution / Payment', 'type' => 'text'],
                    ['key' => 'status', 'label' => 'Workflow Status', 'type' => 'text'],
                ],
                'rows' => $rows,
            ];
        }

        if ($reportType === 'department_procurement_budget_approval') {
            $statusLabel = $data['kjStatus'] === 'all'
                ? 'All Statuses'
                : ($data['kjStatusLabels'][$data['kjStatus']] ?? $data['kjStatus']);
            $decisionLabel = $data['kjDecision'] === 'all'
                ? 'All Decisions'
                : ($data['kjDecisionLabels'][$data['kjDecision']] ?? $data['kjDecision']);

            $definition['filters'] = [
                'PTJ Department' => $data['kjDepartment']->department_name,
                'Budget Year' => (string) $data['kjBudgetYear'],
                'Date Range' => $this->dateRangeLabel($data['kjDateFrom'], $data['kjDateTo']),
                'Procurement Status' => $statusLabel,
                'Approval Decision' => $decisionLabel,
            ];

            $definition['summary'] = [
                ['label' => 'Allocated Budget', 'value' => $data['kjAllocatedAmount'], 'type' => 'currency'],
                ['label' => 'Used Budget', 'value' => $data['kjUsedAmount'], 'type' => 'currency'],
                ['label' => 'Reserved Budget', 'value' => $data['kjReservedAmount'], 'type' => 'currency'],
                ['label' => 'Available Budget', 'value' => $data['kjAvailableBalance'], 'type' => 'currency'],
                ['label' => 'Budget Utilization', 'value' => $data['kjUtilizationPercentage'], 'type' => 'percentage'],
                ['label' => 'Procurement Requests', 'value' => $data['kjTotalRequests'], 'type' => 'integer'],
                ['label' => 'Final Approved Value', 'value' => $data['kjFinalApprovedValue'], 'type' => 'currency'],
                ['label' => 'KJ Decisions', 'value' => $data['kjTotalDecisions'], 'type' => 'integer'],
                ['label' => 'Approval Rate', 'value' => $data['kjApprovalRate'], 'type' => 'percentage'],
            ];

            $procurementRows = [];
            foreach ($data['kjProcurementRecords'] as $record) {
                $isFinal = in_array(
                    $record->request_status,
                    $data['kjFinalApprovedStatuses'],
                    true
                );

                $procurementRows[] = [
                    'request' => 'REQ-' . str_pad($record->procurement_request_id, 4, '0', STR_PAD_LEFT),
                    'submitted' => $record->created_at?->format('d M Y'),
                    'title' => $record->request_title,
                    'requester' => $record->user?->user_name ?? 'N/A',
                    'estimated' => (float) $record->estimated_amount,
                    'approved' => $isFinal && $record->approved_amount !== null
                        ? (float) $record->approved_amount
                        : null,
                    'vendor' => $record->selectedQuotation?->vendor?->company_name ?? 'Not Selected',
                    'status' => $data['kjStatusLabels'][$record->request_status]
                        ?? $record->request_status,
                ];
            }

            $decisionRows = [];
            foreach ($data['kjApprovalRecords'] as $approval) {
                $requestRecord = $approval->procurementRequest;
                $decisionRows[] = [
                    'request' => $requestRecord
                        ? 'REQ-' . str_pad($requestRecord->procurement_request_id, 4, '0', STR_PAD_LEFT)
                        : 'N/A',
                    'date' => $approval->created_at?->format('d M Y'),
                    'title' => $requestRecord?->request_title ?? 'N/A',
                    'requester' => $requestRecord?->user?->user_name ?? 'N/A',
                    'decision' => $data['kjDecisionLabels'][$approval->decision]
                        ?? $approval->decision,
                    'remarks' => $approval->remarks ?: 'No remarks recorded',
                    'status' => $requestRecord
                        ? ($data['kjStatusLabels'][$requestRecord->request_status]
                            ?? $requestRecord->request_status)
                        : 'N/A',
                ];
            }

            $definition['tables'][] = [
                'title' => 'Department Procurement Register',
                'columns' => [
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'submitted', 'label' => 'Submitted', 'type' => 'text'],
                    ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['key' => 'requester', 'label' => 'Requester', 'type' => 'text'],
                    ['key' => 'estimated', 'label' => 'Estimated Amount', 'type' => 'currency'],
                    ['key' => 'approved', 'label' => 'Final Approved', 'type' => 'currency'],
                    ['key' => 'vendor', 'label' => 'Selected Vendor', 'type' => 'text'],
                    ['key' => 'status', 'label' => 'Status', 'type' => 'text'],
                ],
                'rows' => $procurementRows,
            ];

            $definition['tables'][] = [
                'title' => 'KJ Decision Register',
                'columns' => [
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'date', 'label' => 'Decision Date', 'type' => 'text'],
                    ['key' => 'title', 'label' => 'Request Title', 'type' => 'text'],
                    ['key' => 'requester', 'label' => 'Requester', 'type' => 'text'],
                    ['key' => 'decision', 'label' => 'Decision', 'type' => 'text'],
                    ['key' => 'remarks', 'label' => 'Remarks', 'type' => 'text'],
                    ['key' => 'status', 'label' => 'Current Status', 'type' => 'text'],
                ],
                'rows' => $decisionRows,
            ];
        }

        if ($reportType === 'university_procurement_financial') {
            $selectedDepartment = $data['bendPtjId']
                ? $data['bendDepartments']->firstWhere('ptj_id', $data['bendPtjId'])
                : null;

            $definition['filters'] = [
                'PTJ Department' => $selectedDepartment?->department_name ?? 'All PTJ Departments',
                'Budget Year' => (string) $data['bendBudgetYear'],
                'Activity Date Range' => $this->dateRangeLabel($data['bendDateFrom'], $data['bendDateTo']),
            ];

            $definition['summary'] = [
                ['label' => 'Final Approved Procurement Value', 'value' => $data['bendFinalApprovedValue'], 'type' => 'currency'],
                ['label' => 'Procurement Requests', 'value' => $data['bendTotalRequests'], 'type' => 'integer'],
                ['label' => 'Completed Procurements', 'value' => $data['bendCompletedRequests'], 'type' => 'integer'],
                ['label' => 'PTJs with Activity', 'value' => $data['bendActivePtjs'], 'type' => 'integer'],
                ['label' => 'Allocated Budget', 'value' => $data['bendTotalAllocated'], 'type' => 'currency'],
                ['label' => 'Used Budget', 'value' => $data['bendTotalUsed'], 'type' => 'currency'],
                ['label' => 'Reserved Budget', 'value' => $data['bendTotalReserved'], 'type' => 'currency'],
                ['label' => 'Available Budget', 'value' => $data['bendTotalAvailable'], 'type' => 'currency'],
                ['label' => 'Budget Utilization', 'value' => $data['bendOverallUtilization'], 'type' => 'percentage'],
                ['label' => 'Local Orders', 'value' => $data['bendTotalOrders'], 'type' => 'integer'],
                ['label' => 'Payment Pending Value', 'value' => $data['bendPaymentPendingValue'], 'type' => 'currency'],
                ['label' => 'Paid Value', 'value' => $data['bendPaidValue'], 'type' => 'currency'],
            ];

            $visibleExportPortfolio = $data['bendPtjPortfolio']
                ->filter(function ($row) {
                    return (float) $row->used_amount > 0
                        || (float) $row->reserved_amount > 0
                        || (int) $row->request_count > 0
                        || (float) $row->final_approved_value > 0
                        || (int) $row->completed_count > 0
                        || (int) $row->order_count > 0
                        || (float) $row->pending_payment_value > 0
                        || (float) $row->paid_value > 0;
                })
                ->values();

            $portfolioRows = [];
            foreach ($visibleExportPortfolio as $row) {
                $portfolioRows[] = [
                    'ptj' => $row->department_name,
                    'allocated' => $row->allocated_amount,
                    'used' => $row->used_amount,
                    'reserved' => $row->reserved_amount,
                    'available' => $row->available_amount,
                    'utilization' => $row->utilization_percentage,
                    'requests' => $row->request_count,
                    'approved_value' => $row->final_approved_value,
                    'completed' => $row->completed_count,
                    'orders' => $row->order_count,
                    'pending_payment' => $row->pending_payment_value,
                    'paid_value' => $row->paid_value,
                ];
            }

            $procurementRows = [];
            foreach ($data['bendProcurementRecords'] as $record) {
                $isFinal = in_array(
                    $record->request_status,
                    $data['bendFinalApprovedStatuses'],
                    true
                );

                $procurementRows[] = [
                    'request' => 'REQ-' . str_pad($record->procurement_request_id, 4, '0', STR_PAD_LEFT),
                    'submitted' => $record->created_at?->format('d M Y'),
                    'ptj' => $record->ptjDepartment?->department_name ?? 'N/A',
                    'title' => $record->request_title,
                    'requester' => $record->user?->user_name ?? 'N/A',
                    'estimated' => (float) $record->estimated_amount,
                    'approved' => $isFinal && $record->approved_amount !== null
                        ? (float) $record->approved_amount
                        : null,
                    'status' => $data['bendStatusLabels'][$record->request_status]
                        ?? $record->request_status,
                ];
            }

            $definition['tables'][] = [
                'title' => 'PTJ Financial & Operations Portfolio',
                'columns' => [
                    ['key' => 'ptj', 'label' => 'PTJ Department', 'type' => 'text'],
                    ['key' => 'allocated', 'label' => 'Allocated', 'type' => 'currency'],
                    ['key' => 'used', 'label' => 'Used', 'type' => 'currency'],
                    ['key' => 'reserved', 'label' => 'Reserved', 'type' => 'currency'],
                    ['key' => 'available', 'label' => 'Available', 'type' => 'currency'],
                    ['key' => 'utilization', 'label' => 'Utilization', 'type' => 'percentage'],
                    ['key' => 'requests', 'label' => 'Requests', 'type' => 'integer'],
                    ['key' => 'approved_value', 'label' => 'Final Approved Value', 'type' => 'currency'],
                    ['key' => 'completed', 'label' => 'Completed', 'type' => 'integer'],
                    ['key' => 'orders', 'label' => 'Orders', 'type' => 'integer'],
                    ['key' => 'pending_payment', 'label' => 'Pending Payment', 'type' => 'currency'],
                    ['key' => 'paid_value', 'label' => 'Paid Value', 'type' => 'currency'],
                ],
                'rows' => $portfolioRows,
            ];

            $definition['tables'][] = [
                'title' => 'University Procurement Register',
                'columns' => [
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'submitted', 'label' => 'Submitted', 'type' => 'text'],
                    ['key' => 'ptj', 'label' => 'PTJ', 'type' => 'text'],
                    ['key' => 'title', 'label' => 'Request Title', 'type' => 'text'],
                    ['key' => 'requester', 'label' => 'Requester', 'type' => 'text'],
                    ['key' => 'estimated', 'label' => 'Estimated', 'type' => 'currency'],
                    ['key' => 'approved', 'label' => 'Final Approved', 'type' => 'currency'],
                    ['key' => 'status', 'label' => 'Status', 'type' => 'text'],
                ],
                'rows' => $procurementRows,
            ];
        }

        if ($reportType === 'procurement_approval_audit') {
            $selectedDepartment = $data['bendAuditPtjId']
                ? $data['bendAuditDepartments']->firstWhere('ptj_id', $data['bendAuditPtjId'])
                : null;
            $decisionLabel = $data['bendAuditDecision'] === 'all'
                ? 'All Decisions'
                : ($data['bendAuditDecisionLabels'][$data['bendAuditDecision']]
                    ?? $data['bendAuditDecision']);

            $definition['filters'] = [
                'PTJ Department' => $selectedDepartment?->department_name ?? 'All PTJ Departments',
                'Date Range' => $this->dateRangeLabel($data['bendAuditDateFrom'], $data['bendAuditDateTo']),
                'Decision' => $decisionLabel,
            ];

            $definition['summary'] = [
                ['label' => 'Bendahari Decisions', 'value' => $data['bendAuditTotalDecisions'], 'type' => 'integer'],
                ['label' => 'Requests Checked', 'value' => $data['bendAuditCheckedRequestCount'], 'type' => 'integer'],
                ['label' => 'Compliance Exceptions', 'value' => $data['bendAuditExceptionCount'], 'type' => 'integer'],
            ];

            $definition['tables'][] = [
                'title' => 'Compliance Control Verification',
                'columns' => [
                    ['key' => 'control', 'label' => 'System Control', 'type' => 'text'],
                    ['key' => 'rule', 'label' => 'Verification Rule', 'type' => 'text'],
                    ['key' => 'result', 'label' => 'Result', 'type' => 'text'],
                ],
                'rows' => [
                    [
                        'control' => 'Direct Purchase Limit',
                        'rule' => 'Estimated or approved value must not exceed RM50,000.',
                        'result' => $data['bendAuditDirectPurchaseExceptions']->count() . ' exception(s)',
                    ],
                    [
                        'control' => 'Minimum Quotations',
                        'rule' => 'Procurement requests must retain at least three quotation records.',
                        'result' => $data['bendAuditQuotationExceptions']->count() . ' exception(s)',
                    ],
                    [
                        'control' => 'Final Approval Integrity',
                        'rule' => 'Final-approved records must contain a selected quotation and approved amount.',
                        'result' => $data['bendAuditIntegrityExceptions']->count() . ' exception(s)',
                    ],
                ],
            ];

            $decisionRows = [];
            foreach ($data['bendAuditDecisionRecords'] as $approval) {
                $requestRecord = $approval->procurementRequest;
                $decisionRows[] = [
                    'request' => $requestRecord
                        ? 'REQ-' . str_pad($requestRecord->procurement_request_id, 4, '0', STR_PAD_LEFT)
                        : 'N/A',
                    'date' => $approval->created_at?->format('d M Y'),
                    'ptj' => $requestRecord?->ptjDepartment?->department_name ?? 'N/A',
                    'title' => $requestRecord?->request_title ?? 'N/A',
                    'requester' => $requestRecord?->user?->user_name ?? 'N/A',
                    'decision' => $data['bendAuditDecisionLabels'][$approval->decision]
                        ?? $approval->decision,
                    'vendor' => $requestRecord?->selectedQuotation?->vendor?->company_name
                        ?? 'Not Selected',
                    'remarks' => $approval->remarks ?: 'No remarks recorded',
                ];
            }

            $auditRows = [];
            foreach ($data['bendAuditRecords'] as $log) {
                $auditRows[] = [
                    'datetime' => Carbon::parse($log->created_at)->format('d M Y, h:i A'),
                    'request' => $log->procurement_request_id
                        ? 'REQ-' . str_pad($log->procurement_request_id, 4, '0', STR_PAD_LEFT)
                        : '—',
                    'ptj' => $log->department_name ?? 'N/A',
                    'actor' => trim(($log->actor_name ?? 'Unknown') . ' ' . ($log->actor_staff_id ? '(' . $log->actor_staff_id . ')' : '')),
                    'role' => $log->actor_role ? ucfirst($log->actor_role) : 'N/A',
                    'action' => $log->action,
                    'description' => $log->description,
                ];
            }

            $definition['tables'][] = [
                'title' => 'Bendahari Decision Register',
                'columns' => [
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'date', 'label' => 'Decision Date', 'type' => 'text'],
                    ['key' => 'ptj', 'label' => 'PTJ', 'type' => 'text'],
                    ['key' => 'title', 'label' => 'Request Title', 'type' => 'text'],
                    ['key' => 'requester', 'label' => 'Requester', 'type' => 'text'],
                    ['key' => 'decision', 'label' => 'Decision', 'type' => 'text'],
                    ['key' => 'vendor', 'label' => 'Selected Vendor', 'type' => 'text'],
                    ['key' => 'remarks', 'label' => 'Remarks', 'type' => 'text'],
                ],
                'rows' => $decisionRows,
            ];

            $definition['tables'][] = [
                'title' => 'Procurement Audit Trail',
                'columns' => [
                    ['key' => 'datetime', 'label' => 'Date / Time', 'type' => 'text'],
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'ptj', 'label' => 'PTJ', 'type' => 'text'],
                    ['key' => 'actor', 'label' => 'Actor', 'type' => 'text'],
                    ['key' => 'role', 'label' => 'Role', 'type' => 'text'],
                    ['key' => 'action', 'label' => 'Action', 'type' => 'text'],
                    ['key' => 'description', 'label' => 'Description', 'type' => 'text'],
                ],
                'rows' => $auditRows,
            ];
        }

        if ($reportType === 'user_system_overview') {
            $selectedDepartment = $data['adminPtjId']
                ? $data['adminDepartments']->firstWhere('ptj_id', $data['adminPtjId'])
                : null;
            $roleFilterLabel = $data['adminRoleFilter'] === 'all'
                ? 'All Roles'
                : ($data['adminRoleLabels'][$data['adminRoleFilter']] ?? $data['adminRoleFilter']);
            $accountStatusLabel = match ($data['adminAccountStatus']) {
                'active' => 'Active',
                'inactive' => 'Inactive',
                default => 'All Accounts',
            };

            $definition['filters'] = [
                'User Role' => $roleFilterLabel,
                'Account Status' => $accountStatusLabel,
                'PTJ Department' => $selectedDepartment?->department_name ?? 'All / Not Applicable',
            ];

            $definition['summary'] = [
                ['label' => 'Total Users', 'value' => $data['adminTotalUsers'], 'type' => 'integer'],
                ['label' => 'Active Users', 'value' => $data['adminActiveUsers'], 'type' => 'integer'],
                ['label' => 'Inactive Users', 'value' => $data['adminInactiveUsers'], 'type' => 'integer'],
                ['label' => 'Protected Admin Accounts', 'value' => $data['adminProtectedAdminUsers'], 'type' => 'integer'],
                ['label' => 'Active Account Rate', 'value' => $data['adminActiveRate'], 'type' => 'percentage'],
            ];

            $roleRows = [];
            foreach ($data['adminRoleDistribution'] as $row) {
                $roleRows[] = [
                    'role' => $row->label,
                    'accounts' => $row->total,
                    'percentage' => $row->percentage,
                ];
            }

            $departmentRows = [];
            foreach ($data['adminDepartmentDistribution'] as $row) {
                $departmentRows[] = [
                    'ptj' => $row->department_name,
                    'code' => $row->department_code,
                    'ptj_staff' => $row->ptj_users,
                    'kj' => $row->kj_users,
                    'total' => $row->total_users,
                ];
            }

            $userRows = [];
            foreach ($data['adminUsers'] as $account) {
                $userRows[] = [
                    'staff_id' => $account->staff_id,
                    'name' => $account->user_name,
                    'email' => $account->email,
                    'role' => $data['adminRoleLabels'][$account->role] ?? $account->role,
                    'ptj' => $account->ptjDepartment?->department_name ?? 'Not Applicable',
                    'status' => $account->is_active ? 'Active' : 'Inactive',
                    'registered' => $account->created_at?->format('d M Y'),
                ];
            }

            $definition['tables'][] = [
                'title' => 'Role Distribution',
                'columns' => [
                    ['key' => 'role', 'label' => 'Role', 'type' => 'text'],
                    ['key' => 'accounts', 'label' => 'Accounts', 'type' => 'integer'],
                    ['key' => 'percentage', 'label' => 'Share', 'type' => 'percentage'],
                ],
                'rows' => $roleRows,
            ];

            $definition['tables'][] = [
                'title' => 'PTJ Account Mapping',
                'columns' => [
                    ['key' => 'ptj', 'label' => 'PTJ Department', 'type' => 'text'],
                    ['key' => 'code', 'label' => 'Code', 'type' => 'text'],
                    ['key' => 'ptj_staff', 'label' => 'PTJ Staff', 'type' => 'integer'],
                    ['key' => 'kj', 'label' => 'KJ', 'type' => 'integer'],
                    ['key' => 'total', 'label' => 'Total', 'type' => 'integer'],
                ],
                'rows' => $departmentRows,
            ];

            $definition['tables'][] = [
                'title' => 'User Account Register',
                'columns' => [
                    ['key' => 'staff_id', 'label' => 'Staff ID', 'type' => 'text'],
                    ['key' => 'name', 'label' => 'User Name', 'type' => 'text'],
                    ['key' => 'email', 'label' => 'Email', 'type' => 'text'],
                    ['key' => 'role', 'label' => 'Role', 'type' => 'text'],
                    ['key' => 'ptj', 'label' => 'PTJ Department', 'type' => 'text'],
                    ['key' => 'status', 'label' => 'Account Status', 'type' => 'text'],
                    ['key' => 'registered', 'label' => 'Registered', 'type' => 'text'],
                ],
                'rows' => $userRows,
            ];
        }

        if ($reportType === 'system_audit') {
            $categoryLabel = $data['adminAuditCategoryLabels'][$data['adminAuditCategory']]
                ?? $data['adminAuditCategory'];

            $definition['filters'] = [
                'Date Range' => $this->dateRangeLabel($data['adminAuditDateFrom'], $data['adminAuditDateTo']),
                'Audit Category' => $categoryLabel,
            ];

            $definition['summary'] = [
                ['label' => 'Total Audit Events', 'value' => $data['adminAuditTotalEvents'], 'type' => 'integer'],
                ['label' => 'User Management Actions', 'value' => $data['adminAuditUserManagementEvents'], 'type' => 'integer'],
                ['label' => 'Approval Actions', 'value' => $data['adminAuditApprovalEvents'], 'type' => 'integer'],
                ['label' => 'Execution Actions', 'value' => $data['adminAuditExecutionEvents'], 'type' => 'integer'],
                ['label' => 'Payment Actions', 'value' => $data['adminAuditPaymentEvents'], 'type' => 'integer'],
            ];

            $actionRows = [];
            foreach ($data['adminAuditActionDistribution'] as $row) {
                $actionRows[] = [
                    'action' => $row->action,
                    'events' => (int) $row->total,
                ];
            }

            $auditRows = [];
            foreach ($data['adminAuditRecords'] as $log) {
                $auditRows[] = [
                    'datetime' => Carbon::parse($log->created_at)->format('d M Y, h:i A'),
                    'actor' => trim(($log->actor_name ?? 'Unknown') . ' ' . ($log->actor_staff_id ? '(' . $log->actor_staff_id . ')' : '')),
                    'role' => $log->actor_role
                        ? ($data['adminAuditRoleLabels'][$log->actor_role] ?? ucfirst($log->actor_role))
                        : 'N/A',
                    'request' => $log->procurement_request_id
                        ? 'REQ-' . str_pad($log->procurement_request_id, 4, '0', STR_PAD_LEFT)
                        : '—',
                    'ptj' => $log->department_name ?? '—',
                    'action' => $log->action,
                    'description' => $log->description,
                ];
            }

            $definition['tables'][] = [
                'title' => 'Most Frequent Audit Actions',
                'columns' => [
                    ['key' => 'action', 'label' => 'Action', 'type' => 'text'],
                    ['key' => 'events', 'label' => 'Events', 'type' => 'integer'],
                ],
                'rows' => $actionRows,
            ];

            $definition['tables'][] = [
                'title' => 'System Audit Trail',
                'columns' => [
                    ['key' => 'datetime', 'label' => 'Date / Time', 'type' => 'text'],
                    ['key' => 'actor', 'label' => 'Actor', 'type' => 'text'],
                    ['key' => 'role', 'label' => 'Role', 'type' => 'text'],
                    ['key' => 'request', 'label' => 'Request', 'type' => 'text'],
                    ['key' => 'ptj', 'label' => 'PTJ', 'type' => 'text'],
                    ['key' => 'action', 'label' => 'Action', 'type' => 'text'],
                    ['key' => 'description', 'label' => 'Description', 'type' => 'text'],
                ],
                'rows' => $auditRows,
            ];
        }

        return $definition;
    }

    private function buildReportPdfHtml(array $definition): string
    {
        $filters = '';
        foreach ($definition['filters'] as $label => $value) {
            $filters .= '<tr><th>'
                . e($label)
                . '</th><td>'
                . e((string) $value)
                . '</td></tr>';
        }

        $tableHtml = '';

        foreach ($definition['tables'] as $table) {
            $tableHtml .= '<div class="section"><h2>' . e($table['title']) . '</h2>';

            if (empty($table['rows'])) {
                $tableHtml .= '<div class="empty">No records match the selected report criteria.</div></div>';
                continue;
            }

            $tableHtml .= '<table class="data-table"><thead><tr>';

            foreach ($table['columns'] as $column) {
                $tableHtml .= '<th>' . e($column['label']) . '</th>';
            }

            $tableHtml .= '</tr></thead><tbody>';

            foreach ($table['rows'] as $row) {
                $tableHtml .= '<tr>';

                foreach ($table['columns'] as $column) {
                    $value = $row[$column['key']] ?? null;
                    $tableHtml .= '<td>'
                        . e($this->exportDisplayValue($value, $column['type'] ?? 'text'))
                        . '</td>';
                }

                $tableHtml .= '</tr>';
            }

            $tableHtml .= '</tbody></table></div>';
        }

        return '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 22px 24px 28px; }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: DejaVu Sans, sans-serif;
        color: #243047;
        font-size: 8px;
        line-height: 1.35;
    }
    .header {
        border-bottom: 3px solid #173a67;
        padding-bottom: 10px;
        margin-bottom: 12px;
    }
    .system-name {
        font-size: 9px;
        font-weight: 700;
        color: #53708f;
        letter-spacing: .6px;
        text-transform: uppercase;
    }
    h1 {
        margin: 4px 0 2px;
        font-size: 19px;
        color: #173a67;
    }
    .meta {
        color: #67758a;
        font-size: 8px;
    }
    .scope-title {
        margin: 0 0 5px;
        font-size: 9px;
        font-weight: 700;
        color: #173a67;
    }
    .scope-table {
        width: 100%;
        margin: 0 0 13px;
        border-collapse: collapse;
    }
    .scope-table th,
    .scope-table td {
        padding: 5px 7px;
        border: 1px solid #dbe4ef;
        text-align: left;
        vertical-align: top;
    }
    .scope-table th {
        width: 18%;
        background: #f5f8fc;
        color: #66758b;
        font-weight: 700;
    }
    .scope-table td {
        background: #ffffff;
        color: #243047;
        font-weight: 600;
    }
    .section {
        margin-top: 14px;
        page-break-inside: auto;
    }
    .section h2 {
        margin: 0 0 6px;
        padding-bottom: 4px;
        border-bottom: 1px solid #dbe4ef;
        font-size: 11px;
        color: #173a67;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
    }
    .data-table thead { display: table-header-group; }
    .data-table tr { page-break-inside: avoid; }
    .data-table th {
        padding: 5px 4px;
        background: #173a67;
        color: #ffffff;
        border: 1px solid #173a67;
        text-align: left;
        font-size: 6.6px;
        font-weight: 700;
    }
    .data-table td {
        padding: 5px 4px;
        border: 1px solid #dbe4ef;
        vertical-align: top;
        word-wrap: break-word;
    }
    .data-table tbody tr:nth-child(even) td {
        background: #f8fafc;
    }
    .empty {
        padding: 12px;
        border: 1px solid #dbe4ef;
        background: #f8fafc;
        color: #6b7890;
    }
    .footer {
        position: fixed;
        bottom: -17px;
        left: 0;
        right: 0;
        border-top: 1px solid #dbe4ef;
        padding-top: 5px;
        color: #7a8799;
        font-size: 6.5px;
        text-align: center;
    }
</style>
</head>
<body>
    <div class="header">
        <div class="system-name">University Centralised Procurement Management System (UCPMS)</div>
        <h1>' . e($definition['title']) . '</h1>
        <div class="meta">Role: ' . e($definition['role'])
            . ' &nbsp;|&nbsp; Generated by: ' . e($definition['generated_by'])
            . ' &nbsp;|&nbsp; Generated: ' . e($definition['generated_at']) . '</div>
    </div>

    <div class="scope-title">Report Scope</div>
    <table class="scope-table">' . $filters . '</table>

    ' . $tableHtml . '

    <div class="footer">UCPMS · Formal filtered report export · ' . e($definition['generated_at']) . '</div>
</body>
</html>';
    }

    /**
     * Write one formal Excel register sheet.
     *
     * The sheet starts with report identity and applied filters, followed by the
     * detailed register. Dashboard KPI blocks are intentionally excluded from
     * exported workbooks.
     */
    private function writeExcelTableSheet(
        $sheet,
        array $table,
        array $definition
    ): void {
        $columnCount = max(2, count($table['columns']));
        $lastColumn = Coordinate::stringFromColumnIndex($columnCount);

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'UCPMS - ' . $definition['title']);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF173A67');
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', $table['title']);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setARGB('FF173A67');
        $sheet->getRowDimension(2)->setRowHeight(21);

        $row = 4;

        $metadata = [
            'Role' => $definition['role'],
            'Generated By' => $definition['generated_by'],
            'Generated At' => $definition['generated_at'],
        ];

        foreach ($definition['filters'] as $label => $value) {
            $metadata[$label] = $value;
        }

        foreach ($metadata as $label => $value) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", $value);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F6FA');
            $row++;
        }

        $headerRow = $row + 1;

        foreach ($table['columns'] as $index => $column) {
            $cell = Coordinate::stringFromColumnIndex($index + 1) . $headerRow;
            $sheet->setCellValue($cell, $column['label']);
        }

        $headerRange = "A{$headerRow}:{$lastColumn}{$headerRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF173A67');
        $sheet->getStyle($headerRange)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(24);

        $rowNumber = $headerRow + 1;

        if (empty($table['rows'])) {
            $sheet->mergeCells("A{$rowNumber}:{$lastColumn}{$rowNumber}");
            $sheet->setCellValue("A{$rowNumber}", 'No records match the selected report criteria.');
            $rowNumber++;
        } else {
            foreach ($table['rows'] as $rowData) {
                foreach ($table['columns'] as $index => $column) {
                    $cell = Coordinate::stringFromColumnIndex($index + 1) . $rowNumber;
                    $value = $rowData[$column['key']] ?? null;

                    if ($value === null || $value === '') {
                        $sheet->setCellValue($cell, '—');
                    } else {
                        $sheet->setCellValue($cell, $value);
                        $this->applyExcelValueFormat($sheet, $cell, $column['type'] ?? 'text');
                    }
                }

                $rowNumber++;
            }
        }

        $dataRange = "A{$headerRow}:{$lastColumn}" . max($headerRow, $rowNumber - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFDCE3ED');
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);

        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setAutoFilter($headerRange);

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(28);

        foreach (range(1, count($table['columns'])) as $index) {
            $columnLetter = Coordinate::stringFromColumnIndex($index);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }
    }

    /**
     * Fallback sheet used only if a report definition has no register table.
     */
    private function writeExcelEmptyReportSheet($sheet, array $definition): void
    {
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'UCPMS - ' . $definition['title']);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF173A67');
        $sheet->setCellValue('A3', 'No report register is available for the selected criteria.');
    }

    private function applyExcelValueFormat($sheet, string $cell, string $type): void
    {
        if ($type === 'currency') {
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('"RM" #,##0.00');
        } elseif ($type === 'percentage') {
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0.00"%"');
        } elseif ($type === 'integer') {
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0');
        }
    }

    private function exportDisplayValue($value, string $type): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        return match ($type) {
            'currency' => 'RM ' . number_format((float) $value, 2),
            'percentage' => number_format((float) $value, 2) . '%',
            'integer' => number_format((int) $value),
            default => (string) $value,
        };
    }

    private function reportExportFilename(string $reportTitle, string $extension): string
    {
        return 'UCPMS_' . Str::slug($reportTitle, '_') . '_' . now()->format('Ymd_His') . '.' . $extension;
    }

    private function excelSheetTitle(string $title): string
    {
        $title = preg_replace('/[\\\\\/?*\[\]:]/', ' ', $title) ?: 'Report';
        return Str::limit(trim($title), 31, '');
    }

    private function dateRangeLabel(string $from, string $to): string
    {
        return Carbon::parse($from)->format('d M Y')
            . ' - '
            . Carbon::parse($to)->format('d M Y');
    }

    private function statusLabels(): array
    {
        return [
            'Draft' => 'Draft',
            'Pending_KJ' => 'Pending KJ Approval',
            'Pending_Edit' => 'Amendment Required',
            'Approved_KJ' => 'Pending Bendahari Approval',
            'Rejected_KJ' => 'Rejected by KJ',
            'Approved_Bendahari' => 'Approved by Bendahari',
            'Rejected_Bendahari' => 'Rejected by Bendahari',
            'LO_Generated' => 'LO Generated',
            'Delivery_Received' => 'Delivery Received',
            'Completed' => 'Completed',
        ];
    }

    private function finalApprovedStatuses(): array
    {
        return [
            'Approved_Bendahari',
            'LO_Generated',
            'Delivery_Received',
            'Completed',
        ];
    }

    private function roleLabels(): array
    {
        return [
            'ptj' => 'PTJ Administrative Staff',
            'kj' => 'Ketua Jabatan (KJ)',
            'bendahari' => 'Bendahari',
            'admin' => 'System Administrator',
        ];
    }

    private function departments(): Collection
    {
        return PtjDepartment::query()
            ->orderBy('department_name')
            ->get();
    }
}