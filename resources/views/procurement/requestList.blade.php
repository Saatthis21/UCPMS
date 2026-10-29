@extends('layouts.ucpms')

@section('title', 'Procurement Request List - UCPMS')


@section('page-style')

    .page-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .breadcrumb {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .breadcrumb a {
        color: var(--primary);
        font-weight: 600;
    }

    .page-title {
        font-size: 29px;
        line-height: 1.2;
        color: var(--navy);
        letter-spacing: -0.5px;
    }

    .page-description {
        margin-top: 7px;
        color: var(--muted);
        font-size: 13px;
    }

    .new-request-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;

        min-height: 46px;
        padding: 0 20px;

        border-radius: 12px;

        background: linear-gradient(
            135deg,
            var(--primary),
            var(--secondary)
        );

        color: #ffffff;
        font-size: 13px;
        font-weight: 700;

        box-shadow: var(--shadow);

        transition: 0.2s ease;
    }

    .new-request-button:hover {
        transform: translateY(-1px);
    }

    .new-request-button svg {
        width: 18px;
        height: 18px;
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .summary-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow);
    }

    a.summary-card {
        transition: 0.2s ease;
    }

    a.summary-card:hover {
        border-color: var(--primary);
        transform: translateY(-1px);
    }

    .summary-label {
        color: var(--muted);
        font-size: 12px;
        margin-bottom: 7px;
    }

    .summary-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--navy);
    }

    .summary-note {
        margin-top: 4px;
        color: var(--muted);
        font-size: 11px;
    }


    /* =========================================================
       REQUEST PANEL
    ========================================================= */

    .request-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .request-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;

        padding: 18px 20px;

        border-bottom: 1px solid var(--border);
    }

    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .filter-tab {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 9px 13px;

        border-radius: 9px;

        background: #f4f6f9;
        color: #56647b;

        font-size: 12px;
        font-weight: 600;
    }

    .filter-tab:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .filter-tab.active {
        background: var(--primary);
        color: #ffffff;
    }


    /* =========================================================
       SEARCH
    ========================================================= */

    .search-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-box {
        width: 220px;
        height: 40px;

        padding: 0 13px;

        border: 1px solid #dfe6ef;
        border-radius: 10px;

        outline: none;

        color: var(--text);
        background: #ffffff;
    }

    .search-box:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px rgba(23, 105, 232, 0.08);
    }

    .search-button {
        height: 40px;
        padding: 0 14px;

        border: none;
        border-radius: 10px;

        background: var(--primary);
        color: #ffffff;

        font-weight: 700;
        font-size: 12px;

        cursor: pointer;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;
    }

    .request-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .request-table th,
    .request-table td {
        padding: 17px 19px;

        text-align: left;

        border-bottom: 1px solid #edf1f6;

        font-size: 13px;
    }

    .request-table th {
        background: #fbfcfe;

        color: #59677e;

        font-size: 12px;
        font-weight: 700;
    }

    .request-id {
        color: var(--primary);
        font-weight: 800;
    }

    .request-title-text {
        color: var(--navy);
        font-weight: 650;
    }

    .amount-text {
        font-weight: 650;
        color: #35425a;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 7px 10px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .status-warning {
        background: var(--warning-soft);
        color: #ad6900;
    }

    .status-warning .status-dot {
        background: var(--warning);
    }

    .status-primary {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .status-primary .status-dot {
        background: var(--primary);
    }

    .status-success {
        background: var(--success-soft);
        color: var(--success);
    }

    .status-success .status-dot {
        background: var(--success);
    }

    .status-danger {
        background: var(--danger-soft);
        color: var(--danger);
    }

    .status-danger .status-dot {
        background: var(--danger);
    }

    .status-neutral {
        background: #f1f4f8;
        color: #647187;
    }

    .status-neutral .status-dot {
        background: #8290a6;
    }


    /* =========================================================
       ACTIONS
    ========================================================= */

    .action-group {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .action-button {
        min-width: 36px;
        height: 36px;

        padding: 0 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;

        border: 1px solid #dfe6ef;
        border-radius: 9px;

        background: #ffffff;
        color: var(--primary);

        font-size: 11px;
        font-weight: 700;

        transition: 0.2s;
    }

    .action-button:hover {
        background: var(--primary-soft);
        border-color: var(--primary);
    }

    .action-button.edit {
        color: #a46500;
    }

    .action-button.edit:hover {
        background: var(--warning-soft);
        border-color: var(--warning);
    }

    .action-button svg {
        width: 17px;
        height: 17px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;

        margin: 0 auto 14px;

        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--primary-soft);
        color: var(--primary);
    }

    .empty-icon svg {
        width: 28px;
        height: 28px;
    }

    .empty-title {
        color: var(--navy);
        font-weight: 700;
        margin-bottom: 5px;
    }

    .empty-text {
        color: var(--muted);
        font-size: 12px;
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 15px 20px;
    }

    .table-info {
        font-size: 12px;
        color: var(--muted);
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-button {
        min-height: 36px;
        padding: 0 12px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #dfe6ef;
        border-radius: 9px;

        background: #ffffff;
        color: #536179;

        font-size: 11px;
        font-weight: 600;
    }

    .pagination-button:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .pagination-button.disabled {
        opacity: 0.45;
        cursor: default;
    }

    .pagination-current {
        color: var(--muted);
        font-size: 11px;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .alert {
        margin-bottom: 18px;
        padding: 13px 15px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 600;
    }

    .alert-success {
        background: var(--success-soft);
        color: var(--success);
    }

    .alert-error {
        background: var(--danger-soft);
        color: var(--danger);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .request-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-form {
            width: 100%;
        }

        .search-box {
            flex: 1;
            width: auto;
        }
    }

    @media (max-width: 650px) {

        .page-heading {
            flex-direction: column;
            align-items: stretch;
        }

        .new-request-button {
            width: 100%;
        }

        .table-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }

@endsection


@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Request Status Display Configuration
    |--------------------------------------------------------------------------
    */

    $statusConfig = [

        'Draft' => [
            'label' => 'Draft',
            'class' => 'status-neutral',
        ],

        'Pending_KJ' => [
            'label' => 'Pending KJ Approval',
            'class' => 'status-warning',
        ],

        'Pending_Edit' => [
            'label' => 'Update Request',
            'class' => 'status-primary',
        ],

        'Approved_KJ' => [
            'label' => 'Pending Bendahari Approval',
            'class' => 'status-primary',
        ],

        'Rejected_KJ' => [
            'label' => 'Rejected by KJ',
            'class' => 'status-danger',
        ],

        'Approved_Bendahari' => [
            'label' => 'Approved by Bendahari',
            'class' => 'status-success',
        ],

        'Rejected_Bendahari' => [
            'label' => 'Rejected by Bendahari',
            'class' => 'status-danger',
        ],

        'LO_Generated' => [
            'label' => 'LO Generated',
            'class' => 'status-success',
        ],

        'Delivery_Received' => [
            'label' => 'Delivery Received',
            'class' => 'status-success',
        ],

        'Completed' => [
            'label' => 'Completed',
            'class' => 'status-success',
        ],
    ];

@endphp


{{-- =========================================================
     PAGE HEADER
========================================================= --}}

<div class="page-heading">

    <div>

        <div class="breadcrumb">
            <a href="{{ route('ptj.dashboard') }}">
                Dashboard
            </a>

            &nbsp;›&nbsp;

            Procurement Request
        </div>

        <h1 class="page-title">
            Procurement Request List
        </h1>

        <div class="page-description">
            View and track your submitted procurement requests.
        </div>

    </div>


    <a
        href="{{ route('ptj.procurement.create') }}"
        class="new-request-button"
    >

        <svg viewBox="0 0 24 24" fill="none">

            <path
                d="M12 5V19M5 12H19"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
            />

        </svg>

        New Procurement Request

    </a>

</div>


{{-- =========================================================
     NOTIFICATIONS
========================================================= --}}

@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if (session('error'))

    <div class="alert alert-error">
        {{ session('error') }}
    </div>

@endif


{{-- =========================================================
     SUMMARY
========================================================= --}}

<div class="summary-grid">

    <a
        href="{{ route('ptj.procurement.index') }}"
        class="summary-card"
    >

        <div class="summary-label">
            Total Procurement Requests
        </div>

        <div class="summary-value">
            {{ $totalRequests }}
        </div>

        <div class="summary-note">
            All submissions
        </div>

    </a>


    <a
        href="{{ route('ptj.procurement.index', ['status' => 'pending']) }}"
        class="summary-card"
    >

        <div class="summary-label">
            Pending Approval
        </div>

        <div class="summary-value">
            {{ $pendingRequests }}
        </div>

        <div class="summary-note">
            KJ or Bendahari
        </div>

    </a>


    <a
        href="{{ route('ptj.procurement.index', ['status' => 'update']) }}"
        class="summary-card"
    >

        <div class="summary-label">
            Update Request
        </div>

        <div class="summary-value">
            {{ $updateRequests }}
        </div>

        <div class="summary-note">
            Returned for amendment
        </div>

    </a>

</div>


{{-- =========================================================
     REQUEST LIST
========================================================= --}}

<div class="request-panel">


    {{-- Toolbar --}}
    <div class="request-toolbar">


        <div class="filter-tabs">

            <a
                href="{{ route('ptj.procurement.index') }}"
                class="filter-tab {{ $status === 'all' ? 'active' : '' }}"
            >
                All
            </a>

            <a
                href="{{ route('ptj.procurement.index', ['status' => 'draft']) }}"
                class="filter-tab {{ $status === 'draft' ? 'active' : '' }}"
            >
                Draft
            </a>

            <a
                href="{{ route('ptj.procurement.index', ['status' => 'pending-kj']) }}"
                class="filter-tab {{ $status === 'pending-kj' ? 'active' : '' }}"
            >
                Pending KJ Approval
            </a>

            <a
                href="{{ route('ptj.procurement.index', ['status' => 'pending-bendahari']) }}"
                class="filter-tab {{ $status === 'pending-bendahari' ? 'active' : '' }}"
            >
                Pending Bendahari Approval
            </a>

            <a
                href="{{ route('ptj.procurement.index', ['status' => 'update']) }}"
                class="filter-tab {{ $status === 'update' ? 'active' : '' }}"
            >
                Update Request
            </a>

             <a
                href="{{ route('ptj.procurement.index', ['status' => 'in-progress']) }}"
                class="filter-tab {{ $status === 'in-progress' ? 'active' : '' }}"
            >
                In Progress
            </a>

            <a
                href="{{ route('ptj.procurement.index', ['status' => 'completed']) }}"
                class="filter-tab {{ $status === 'completed' ? 'active' : '' }}"
            >
                Completed
            </a>

            <a
                href="{{ route('ptj.procurement.index', ['status' => 'rejected']) }}"
                class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}"
            >
                Rejected
            </a>

        </div>


        <form
            method="GET"
            action="{{ route('ptj.procurement.index') }}"
            class="search-form"
        >

            @if ($status !== 'all')

                <input
                    type="hidden"
                    name="status"
                    value="{{ $status }}"
                >

            @endif


            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="search-box"
                placeholder="Search title or request ID..."
            >


            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

        </form>

    </div>


    {{-- Table --}}
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

                        $displayStatus =
                            $statusConfig[$procurementRequest->request_status]
                            ?? [
                                'label' => $procurementRequest->request_status,
                                'class' => 'status-neutral',
                            ];

                    @endphp


                    <tr>

                        <td>

                            <span class="request-id">
                                REQ-{{ str_pad(
                                    $procurementRequest->procurement_request_id,
                                    4,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </span>

                        </td>


                        <td>

                            <span class="request-title-text">
                                {{ $procurementRequest->request_title }}
                            </span>

                        </td>


                        <td>

                            <span class="amount-text">
                                {{ number_format(
                                    (float) $procurementRequest->estimated_amount,
                                    2
                                ) }}
                            </span>

                        </td>


                        <td>

                            {{ $procurementRequest->created_at
                                ? $procurementRequest->created_at->format('d M Y')
                                : '-' }}

                        </td>


                        <td>

                            <span
                                class="
                                    status-badge
                                    {{ $displayStatus['class'] }}
                                "
                            >

                                <span class="status-dot"></span>

                                {{ $displayStatus['label'] }}

                            </span>

                        </td>


                        <td>

                            <div class="action-group">


                                {{-- B3: View Request Details --}}
                                <a
                                    href="{{ route(
                                        'ptj.procurement.show',
                                        $procurementRequest->procurement_request_id
                                    ) }}"
                                    class="action-button"
                                    title="View Request"
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

                                    View

                                </a>


                                {{-- B4: Edit and Resubmit --}}
                                @if ($procurementRequest->request_status === 'Pending_Edit')

                                    <a
                                        href="{{ route(
                                            'ptj.procurement.edit',
                                            $procurementRequest->procurement_request_id
                                        ) }}"
                                        class="action-button edit"
                                        title="Edit and Resubmit"
                                    >
                                        Edit
                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <rect
                                            x="5"
                                            y="3"
                                            width="14"
                                            height="18"
                                            rx="2"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            d="M8 8H16M8 12H16M8 16H13"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                    </svg>

                                </div>

                                <div class="empty-title">
                                    No procurement requests found
                                </div>

                                <div class="empty-text">
                                    Your procurement requests will appear here.
                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if ($procurementRequests->total() > 0)

        <div class="table-footer">

            <div class="table-info">

                Showing
                {{ $procurementRequests->firstItem() }}
                -
                {{ $procurementRequests->lastItem() }}

                of

                {{ $procurementRequests->total() }}

                request(s)

            </div>


            @if ($procurementRequests->hasPages())

                <div class="pagination">


                    @if ($procurementRequests->onFirstPage())

                        <span class="pagination-button disabled">
                            Previous
                        </span>

                    @else

                        <a
                            href="{{ $procurementRequests->previousPageUrl() }}"
                            class="pagination-button"
                        >
                            Previous
                        </a>

                    @endif


                    <span class="pagination-current">

                        Page
                        {{ $procurementRequests->currentPage() }}
                        of
                        {{ $procurementRequests->lastPage() }}

                    </span>


                    @if ($procurementRequests->hasMorePages())

                        <a
                            href="{{ $procurementRequests->nextPageUrl() }}"
                            class="pagination-button"
                        >
                            Next
                        </a>

                    @else

                        <span class="pagination-button disabled">
                            Next
                        </span>

                    @endif

                </div>

            @endif

        </div>

    @endif

</div>

@endsection