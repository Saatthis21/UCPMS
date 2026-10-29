@extends('layouts.ucpms')

@section('title', 'PTJ Dashboard - UCPMS')

@section('page-style')

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e8edf5;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(30, 64, 175, 0.07);
        min-height: 150px;
        position: relative;
        overflow: hidden;
    }

    .summary-card.primary {
        background: linear-gradient(135deg, #1d63e8, #20b8bb);
        color: #ffffff;
        border: none;
    }

    .summary-card.primary .summary-label,
    .summary-card.primary .summary-subtext,
    .summary-card.primary .summary-value {
        color: #ffffff;
    }

    .summary-card.action-card {
        background: linear-gradient(135deg, #1d63e8, #20b8bb);
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .summary-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef4ff;
        color: #1769e8;
        margin-bottom: 18px;
    }

    .summary-card.primary .summary-icon,
    .summary-card.action-card .summary-icon {
        background: rgba(255, 255, 255, 0.92);
        color: #1769e8;
    }

    .summary-icon svg {
        width: 25px;
        height: 25px;
    }

    .summary-label {
        font-size: 14px;
        color: #5f6d85;
        margin-bottom: 5px;
    }

    .summary-value {
        font-size: 31px;
        font-weight: 800;
        color: #17233c;
        margin-bottom: 5px;
        letter-spacing: -0.5px;
    }

    .summary-subtext {
        font-size: 13px;
        color: #7a879c;
    }

    .action-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .action-description {
        font-size: 12px;
        opacity: 0.88;
    }

    .action-arrow {
        font-size: 28px;
    }

    .request-panel {
        background: #ffffff;
        border: 1px solid #e8edf5;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(30, 64, 175, 0.07);
        overflow: hidden;
    }

    .request-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 20px;
        border-bottom: 1px solid #edf1f6;
    }

    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-tab {
        border: none;
        background: #f4f6f9;
        color: #4c5a72;
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 12px;
        cursor: pointer;
    }

    .filter-tab.active {
        background: #1769e8;
        color: #ffffff;
        font-weight: 700;
    }

    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-box {
        width: 230px;
        height: 40px;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        padding: 0 14px;
        outline: none;
    }

    .search-box:focus {
        border-color: #1769e8;
        box-shadow: 0 0 0 3px rgba(23, 105, 232, 0.08);
    }

    .filter-button {
        height: 40px;
        padding: 0 14px;
        border-radius: 10px;
        border: 1px solid #dfe6ef;
        background: #ffffff;
        color: #4c5a72;
        cursor: pointer;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .request-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .request-table th,
    .request-table td {
        padding: 17px 20px;
        text-align: left;
        border-bottom: 1px solid #edf1f6;
        font-size: 13px;
    }

    .request-table th {
        background: #fbfcfe;
        color: #5b6880;
        font-weight: 700;
    }

    .request-id {
        color: #1769e8;
        font-weight: 700;
    }

    .request-title {
        font-weight: 600;
        color: #25324a;
    }

    .request-subtitle {
        display: block;
        font-size: 11px;
        color: #8a96a9;
        margin-top: 3px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff4d8;
        color: #b56c00;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        background: #f59e0b;
        border-radius: 50%;
    }

    .view-button {
        width: 36px;
        height: 36px;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #ffffff;
        color: #1769e8;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .view-button svg {
        width: 18px;
        height: 18px;
    }

    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
    }

    .table-info {
        font-size: 12px;
        color: #758199;
    }

    .pagination {
        display: flex;
        gap: 7px;
    }

    .page-button {
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border: 1px solid #dfe6ef;
        background: #ffffff;
        border-radius: 9px;
        color: #526079;
        cursor: pointer;
    }

    .page-button.active {
        background: #1769e8;
        color: #ffffff;
        border-color: #1769e8;
    }

    .dashboard-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        padding: 10px 2px 5px;
        font-size: 11px;
        color: #7c889d;
    }

    @media (max-width: 1000px) {
        .dashboard-grid {
            grid-template-columns: 1fr 1fr;
        }

        .summary-card.action-card {
            grid-column: span 2;
        }

        .request-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .toolbar-right {
            width: 100%;
        }

        .search-box {
            flex: 1;
            width: auto;
        }
    }

    @media (max-width: 650px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .summary-card.action-card {
            grid-column: span 1;
        }

        .toolbar-right {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box,
        .filter-button {
            width: 100%;
        }

        .table-footer,
        .dashboard-footer {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
    }
@endsection


@section('content')

<div class="welcome-section">

    <div>
        <div class="welcome-small">
            {{ $greeting }},
        </div>

        <h1 class="welcome-title">
            {{ auth()->user()->user_name }} 👋
        </h1>

        <div class="welcome-description">
            Here's what's happening with your procurement requests today.
        </div>
    </div>

    <div class="welcome-decoration">
        <div class="welcome-circle-one"></div>
        <div class="welcome-circle-two"></div>
        <div class="welcome-circle-three"></div>
    </div>

</div>


<div class="dashboard-grid">

    {{-- =========================================================
         AVAILABLE DEPARTMENT BUDGET
    ========================================================== --}}
    <div class="summary-card primary">

        <div class="summary-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <path
                    d="M12 3V21M16 7.5C16 5.8 14.6 5 12.3 5H10.8C8.7 5 7.5 6 7.5 7.5C7.5 9.1 8.7 10 10.8 10.5L13.2 11.1C15.4 11.6 16.5 12.6 16.5 14.3C16.5 16.2 15 17.5 12.5 17.5H10.7C8.2 17.5 6.8 16.4 6.8 14.5"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                />
            </svg>
        </div>

        <div class="summary-label">
            Available Department Budget - {{ $budgetYear }}
        </div>

        <div class="summary-value">
            RM {{ number_format($availableBudget, 2) }}
        </div>

        <div class="summary-subtext">
            {{ $department->department_name ?? 'PTJ Department' }}
            @if (!$budget)
                · No {{ $budgetYear }} budget record found
            @endif
        </div>

    </div>


    {{-- =========================================================
         PENDING APPROVAL COUNT
    ========================================================== --}}
    <div class="summary-card">

        <div class="summary-icon" style="color:#f59e0b; background:#fff4df;">
            <svg viewBox="0 0 24 24" fill="none">
                <circle
                    cx="12"
                    cy="12"
                    r="8"
                    stroke="currentColor"
                    stroke-width="1.8"
                />

                <path
                    d="M12 7V12L15 14"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                />
            </svg>
        </div>

        <div class="summary-label">
            Requests Pending Approval
        </div>

        <div class="summary-value">
            {{ number_format($pendingApprovalCount) }}
        </div>

        <div class="summary-subtext">
            Pending KJ or Bendahari approval
        </div>

    </div>


    {{-- =========================================================
         NEW PROCUREMENT REQUEST
    ========================================================== --}}
    <a
        href="{{ route('ptj.procurement.create') }}"
        class="summary-card action-card"
        style="text-decoration:none;"
    >

        <div>

            <div class="summary-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12 5V19M5 12H19"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <div class="action-title">
                New Procurement Request
            </div>

            <div class="action-description">
                Create a new procurement request
            </div>

        </div>

        <div class="action-arrow">
            →
        </div>

    </a>

</div>


{{-- =========================================================
     REAL-TIME REQUEST LIST
========================================================= --}}
<div class="request-panel">

    <div class="request-toolbar">

        <div class="filter-tabs">

            @php
                $tabs = [
                    'all' => 'All',
                    'draft' => 'Draft',
                    'pending-kj' => 'Pending KJ Approval',
                    'pending-bendahari' => 'Pending Bendahari Approval',
                    'update' => 'Update Request',
                ];
            @endphp

            @foreach ($tabs as $tabValue => $tabLabel)
                <a
                    href="{{ route('ptj.dashboard', ['status' => $tabValue, 'search' => $search ?: null]) }}"
                    class="filter-tab {{ $status === $tabValue ? 'active' : '' }}"
                    style="text-decoration:none; display:inline-flex; align-items:center;"
                >
                    {{ $tabLabel }}
                </a>
            @endforeach

        </div>


        <form
            method="GET"
            action="{{ route('ptj.dashboard') }}"
            class="toolbar-right"
        >
            <input type="hidden" name="status" value="{{ $status }}">

            <input
                type="text"
                name="search"
                class="search-box"
                placeholder="Search request..."
                value="{{ $search }}"
            >

            <button type="submit" class="filter-button">
                Filter
            </button>

        </form>

    </div>


    <div class="table-wrapper">

        <table class="request-table">

            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Title</th>
                    <th>Estimated Amount (RM)</th>
                    <th>Date Submitted</th>
                    <th>Current Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($procurementRequests as $procurementRequest)

                    @php
                        $statusStyle = match ($procurementRequest->request_status) {
                            'Rejected_KJ', 'Rejected_Bendahari'
                                => 'background:#fdecef; color:#cc2337;',

                            'Completed', 'Approved_Bendahari', 'Delivery_Received', 'DO_Received'
                                => 'background:#e6f7ef; color:#0d9964;',

                            'Pending_Edit'
                                => 'background:#f1e8ff; color:#6d28d9;',

                            'LO_Generated', 'Awaiting_Delivery', 'Invoice_Recorded'
                                => 'background:#e8f1ff; color:#1769e8;',

                            default
                                => 'background:#fff4d8; color:#b56c00;',
                        };
                    @endphp

                    <tr>
                        <td class="request-id">
                            REQ-{{ str_pad(
                                $procurementRequest->procurement_request_id,
                                4,
                                '0',
                                STR_PAD_LEFT
                            ) }}
                        </td>

                        <td>
                            <span class="request-title">
                                {{ $procurementRequest->request_title }}
                            </span>

                            <span class="request-subtitle">
                                {{ $department->department_name ?? 'PTJ Department' }}
                            </span>
                        </td>

                        <td>
                            {{ number_format((float) $procurementRequest->estimated_amount, 2) }}
                        </td>

                        <td>
                            {{ $procurementRequest->created_at->format('d M Y') }}
                        </td>

                        <td>
                            <span class="status-badge" style="{{ $statusStyle }}">
                                <span class="status-dot" style="background:currentColor;"></span>
                                {{ $statusLabels[$procurementRequest->request_status]
                                    ?? str_replace('_', ' ', $procurementRequest->request_status) }}
                            </span>
                        </td>

                        <td>
                            <a
                                href="{{ route('ptj.procurement.show', $procurementRequest) }}"
                                class="view-button"
                                title="View Request"
                                style="text-decoration:none;"
                            >
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M2 12C4.5 7.5 7.8 5.5 12 5.5C16.2 5.5 19.5 7.5 22 12C19.5 16.5 16.2 18.5 12 18.5C7.8 18.5 4.5 16.5 2 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.5"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />
                                </svg>
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align:center; padding:34px; color:#7a879c;">
                            No procurement requests found for the selected filter.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="table-footer">

        <div class="table-info">
            @if ($procurementRequests->total() > 0)
                Showing {{ $procurementRequests->firstItem() }} to
                {{ $procurementRequests->lastItem() }} of
                {{ $procurementRequests->total() }} requests
            @else
                Showing 0 requests
            @endif
        </div>

        @if ($procurementRequests->hasPages())
            <div class="pagination">

                @if ($procurementRequests->onFirstPage())
                    <span class="page-button" style="opacity:.5; cursor:not-allowed; display:inline-flex; align-items:center; justify-content:center;">
                        Previous
                    </span>
                @else
                    <a
                        href="{{ $procurementRequests->previousPageUrl() }}"
                        class="page-button"
                        style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;"
                    >
                        Previous
                    </a>
                @endif

                @foreach (
                    $procurementRequests->getUrlRange(
                        max(1, $procurementRequests->currentPage() - 2),
                        min($procurementRequests->lastPage(), $procurementRequests->currentPage() + 2)
                    ) as $page => $url
                )
                    <a
                        href="{{ $url }}"
                        class="page-button {{ $page === $procurementRequests->currentPage() ? 'active' : '' }}"
                        style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;"
                    >
                        {{ $page }}
                    </a>
                @endforeach

                @if ($procurementRequests->hasMorePages())
                    <a
                        href="{{ $procurementRequests->nextPageUrl() }}"
                        class="page-button"
                        style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;"
                    >
                        Next
                    </a>
                @else
                    <span class="page-button" style="opacity:.5; cursor:not-allowed; display:inline-flex; align-items:center; justify-content:center;">
                        Next
                    </span>
                @endif

            </div>
        @endif

    </div>

</div>


<div class="dashboard-footer">

    <div>
        © {{ now()->year }} UCPMS. All rights reserved.
    </div>

    <div>
        Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA)
    </div>

</div>

@endsection