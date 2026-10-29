@extends('layouts.ucpms')

@section('title', 'Pending Approvals - UCPMS')

@php

    $approvalRole = $approvalRole ?? 'kj';

    $isBendahari =
        $approvalRole === 'bendahari';

    $pageTitle =
        $isBendahari
            ? 'Pending Bendahari Approvals'
            : 'Pending KJ Approvals';

    $pageDescription =
        $isBendahari
            ? 'Review procurement requests approved by KJ and awaiting final Bendahari decision.'
            : 'Review procurement requests currently awaiting KJ decision.';

    $queueLabel =
        $isBendahari
            ? 'Bendahari Review Queue'
            : 'KJ Review Queue';

    $queueStatus =
        $isBendahari
            ? 'Pending Bendahari Approval'
            : 'Pending KJ Approval';
            
    $reviewRoute =
        $isBendahari
            ? 'bendahari.approvals.show'
            : 'kj.approvals.show';

@endphp


@section('page-style')



<style>

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .approval-page-header {
        margin-bottom: 26px;
    }

    .approval-page-title {
        font-size: 29px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--navy);
    }

    .approval-page-description {
        margin-top: 7px;
        font-size: 13px;
        color: var(--muted);
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .alert {
        margin-bottom: 20px;
        padding: 14px 17px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .alert-success {
        background: var(--success-soft);
        color: var(--success);
        border: 1px solid rgba(15, 159, 110, 0.18);
    }

    .alert-error {
        background: var(--danger-soft);
        color: var(--danger);
        border: 1px solid rgba(220, 53, 69, 0.18);
    }


    /* =========================================================
       SUMMARY CARD
    ========================================================= */

    .queue-summary {
        margin-bottom: 22px;

        padding: 22px 24px;

        border: 1px solid var(--border);
        border-radius: 16px;

        background:
            linear-gradient(
                135deg,
                var(--surface),
                var(--primary-soft)
            );

        box-shadow: var(--shadow);

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }

    .queue-summary-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .queue-icon {
        width: 52px;
        height: 52px;

        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--primary-soft-strong);
        color: var(--primary);

        flex-shrink: 0;
    }

    .queue-icon svg {
        width: 27px;
        height: 27px;
    }

    .queue-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--navy);
    }

    .queue-description {
        margin-top: 5px;
        font-size: 12px;
        color: var(--muted);
    }

    .queue-count {
        min-width: 110px;

        padding: 12px 17px;

        border-radius: 12px;

        background: #ffffff;
        border: 1px solid var(--border);

        text-align: center;
    }

    .queue-count-number {
        display: block;

        font-size: 22px;
        font-weight: 800;

        color: var(--primary);
    }

    .queue-count-label {
        margin-top: 2px;

        display: block;

        font-size: 11px;
        color: var(--muted);
    }


    /* =========================================================
       FILTER CARD
    ========================================================= */

    .filter-card {
        margin-bottom: 22px;

        padding: 18px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 15px;

        box-shadow: var(--shadow);
    }

    .filter-form {
        display: grid;
        gap: 12px;
        align-items: center;
    }

    .filter-form.kj-filter {
        grid-template-columns:
            minmax(260px, 1fr)
            200px
            auto;
    }

    .filter-form.bendahari-filter {
        grid-template-columns:
            minmax(240px, 1fr)
            220px
            200px
            auto;
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper svg {
        position: absolute;

        width: 18px;
        height: 18px;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #8b98aa;

        pointer-events: none;
    }

    .form-control {
        width: 100%;
        height: 44px;

        padding: 0 14px;

        border: 1px solid #dfe5ee;
        border-radius: 10px;

        outline: none;

        background: #ffffff;
        color: var(--text);

        font-size: 13px;

        transition: 0.2s;
    }

    .search-wrapper .form-control {
        padding-left: 42px;
    }

    .form-control:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px var(--primary-soft);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .button {
        height: 44px;

        padding: 0 17px;

        border: none;
        border-radius: 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        cursor: pointer;

        font-size: 13px;
        font-weight: 700;

        transition: 0.2s;
    }

    .button svg {
        width: 17px;
        height: 17px;
    }

    .button-primary {
        background: var(--primary);
        color: #ffffff;
    }

    .button-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .button-secondary {
        background: #f6f8fb;
        color: #5c6a80;

        border: 1px solid var(--border);
    }

    .button-secondary:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }


    /* =========================================================
       TABLE CARD
    ========================================================= */

    .approval-card {
        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 16px;

        overflow: hidden;

        box-shadow: var(--shadow);
    }

    .card-heading {
        padding: 20px 22px;

        border-bottom: 1px solid var(--border);

        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 16px;
    }

    .card-heading-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--navy);
    }

    .card-heading-description {
        margin-top: 4px;

        font-size: 12px;
        color: var(--muted);
    }

    .pending-badge {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 7px 10px;

        border-radius: 999px;

        background: var(--warning-soft);
        color: #a96a00;

        font-size: 11px;
        font-weight: 800;

        white-space: nowrap;
    }

    .pending-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: var(--warning);
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;
    }

    .approval-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .approval-table thead {
        background: #fafbfd;
    }

    .approval-table th {
        padding: 14px 17px;

        border-bottom: 1px solid var(--border);

        text-align: left;

        font-size: 11px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.4px;

        color: #7b8799;
    }

    .approval-table td {
        padding: 17px;

        border-bottom: 1px solid #edf1f6;

        vertical-align: middle;

        font-size: 13px;
    }

    .approval-table tbody tr {
        transition: 0.2s;
    }

    .approval-table tbody tr:hover {
        background: #fbfcfe;
    }

    .approval-table tbody tr:last-child td {
        border-bottom: none;
    }

    .request-id {
        font-weight: 800;
        color: var(--primary);
    }

    .request-title {
        font-weight: 700;
        color: var(--navy);
    }

    .request-description-small {
        max-width: 260px;

        margin-top: 4px;

        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;

        font-size: 11px;
        color: var(--muted);
    }

    .requester-name {
        font-weight: 600;
        color: var(--text);
    }

    .requester-id {
        margin-top: 3px;

        font-size: 11px;
        color: var(--muted);
    }

    .amount {
        font-weight: 800;
        color: var(--navy);
    }

    .date-main {
        color: var(--text);
        font-weight: 600;
    }

    .date-time {
        margin-top: 3px;

        font-size: 11px;
        color: var(--muted);
    }

    .review-button {
        padding: 9px 15px;

        display: inline-flex;
        align-items: center;

        gap: 6px;

        border-radius: 9px;

        background: var(--primary);
        color: #ffffff;

        font-size: 12px;
        font-weight: 700;

        transition: 0.2s;
    }

    .review-button:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .review-button svg {
        width: 16px;
        height: 16px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 62px 25px;

        text-align: center;
    }

    .empty-icon {
        width: 66px;
        height: 66px;

        margin: 0 auto 15px;

        border-radius: 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--primary-soft);
        color: var(--primary);
    }

    .empty-icon svg {
        width: 32px;
        height: 32px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--navy);
    }

    .empty-description {
        margin-top: 6px;

        font-size: 12px;
        color: var(--muted);
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-area {
        padding: 17px 20px;

        border-top: 1px solid var(--border);

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;
    }

    .pagination-info {
        font-size: 12px;
        color: var(--muted);
    }

    .pagination-links {
        display: flex;
        align-items: center;

        gap: 6px;
    }

    .page-link {
        min-width: 35px;
        height: 35px;

        padding: 0 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        border: 1px solid var(--border);

        background: #ffffff;
        color: #5e6b80;

        font-size: 12px;
        font-weight: 700;
    }

    .page-link:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .page-link.active {
        border-color: var(--primary);

        background: var(--primary);
        color: #ffffff;
    }

    .page-link.disabled {
        opacity: 0.45;
        pointer-events: none;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .filter-form {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .button {
            flex: 1;
        }

        .queue-summary {
            align-items: flex-start;
        }
    }


    @media (max-width: 600px) {

        .approval-page-title {
            font-size: 24px;
        }

        .queue-summary {
            flex-direction: column;
        }

        .queue-count {
            width: 100%;
        }

        .pagination-area {
            flex-direction: column;
            align-items: flex-start;
        }

        .pagination-links {
            flex-wrap: wrap;
        }
    }

</style>

@endsection


@section('content')


{{-- =========================================================
     PAGE HEADER
========================================================= --}}

<section class="approval-page-header">

    <h1 class="approval-page-title">
        {{ $pageTitle }}
    </h1>

    <p class="approval-page-description">
        {{ $pageDescription }}
    </p>

</section>



{{-- =========================================================
     SUCCESS / ERROR MESSAGES
========================================================= --}}

@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


@if ($errors->any())

    <div class="alert alert-error">

        {{ $errors->first() }}

    </div>

@endif



{{-- =========================================================
     QUEUE SUMMARY
========================================================= --}}

<section class="queue-summary">

    <div class="queue-summary-left">

        <div class="queue-icon">

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


        <div>

            <div class="queue-title">
                {{ $queueLabel }}
            </div>

            <div class="queue-description">

                {{ $isBendahari
                    ? 'Requests approved by KJ from all PTJ departments and awaiting final Bendahari review.'
                    : 'Requests submitted by PTJ staff from your assigned department.' }}

            </div>

        </div>

    </div>


    <div class="queue-count">

        <span class="queue-count-number">
            {{ $pendingRequests->total() }}
        </span>

        <span class="queue-count-label">
            {{ Str::plural('Request', $pendingRequests->total()) }}
        </span>

    </div>

</section>



{{-- =========================================================
     SEARCH / FILTER
========================================================= --}}

<section class="filter-card">

    <form
        method="GET"
        action="{{ $isBendahari
            ? route('bendahari.approvals.index')
            : route('kj.approvals.index') }}"
        class="filter-form {{ $isBendahari ? 'bendahari-filter' : 'kj-filter' }}"
    >

        {{-- SEARCH --}}
        <div class="filter-group">

            <label class="filter-label">
                Search Request
            </label>

            <div class="search-wrapper">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M16.5 16.5L21 21"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search request ID, title or requester..."
                >

            </div>

        </div>


        {{-- BENDahari ONLY: PTJ DEPARTMENT --}}
        @if ($isBendahari)

            <div class="filter-group">

                <label class="filter-label">
                    PTJ Department
                </label>

                <select
                    name="ptj_id"
                    class="form-control"
                >

                    <option value="">
                        All Departments
                    </option>

                    @foreach ($departments as $department)

                        <option
                            value="{{ $department->ptj_id }}"
                            {{ (string) request('ptj_id')
                                === (string) $department->ptj_id
                                    ? 'selected'
                                    : '' }}
                        >
                            {{ $department->department_name }}
                        </option>

                    @endforeach

                </select>

            </div>

        @endif


        {{-- AMOUNT FILTER --}}
        <div class="filter-group">

            <label class="filter-label">
                Amount
            </label>

            <select
                name="amount"
                class="form-control"
            >

                <option value="">
                    All Amounts
                </option>

                <option
                    value="below_10000"
                    {{ request('amount') === 'below_10000'
                        ? 'selected'
                        : '' }}
                >
                    Below RM10,000
                </option>

                <option
                    value="10000_30000"
                    {{ request('amount') === '10000_30000'
                        ? 'selected'
                        : '' }}
                >
                    RM10,000 - RM30,000
                </option>

                <option
                    value="30001_50000"
                    {{ request('amount') === '30001_50000'
                        ? 'selected'
                        : '' }}
                >
                    RM30,001 - RM50,000
                </option>

            </select>

        </div>


        {{-- BUTTONS --}}
        <div class="filter-actions">

            <button
                type="submit"
                class="button button-primary"
            >

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M4 6H20M7 12H17M10 18H14"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                </svg>

                Filter

            </button>


            @if (
                request()->filled('search')
                || request()->filled('amount')
                || request()->filled('ptj_id')
            )

                <a
                    href="{{ $isBendahari
                        ? route('bendahari.approvals.index')
                        : route('kj.approvals.index') }}"
                    class="button button-secondary"
                >
                    Clear
                </a>

            @endif

        </div>

    </form>

</section>



{{-- =========================================================
     PENDING APPROVAL TABLE
========================================================= --}}

<section class="approval-card">

    <div class="card-heading">

        <div>

            <div class="card-heading-title">
                Procurement Requests
            </div>

            <div class="card-heading-description">
                Only requests currently waiting for your approval are displayed.
            </div>

        </div>


        <div class="pending-badge">

            <span class="pending-dot"></span>

            {{ $queueStatus }}

        </div>

    </div>


    @if ($pendingRequests->count() > 0)

        <div class="table-wrapper">

            <table class="approval-table">

                <thead>

                    <tr>

                        <th>
                            Request ID
                        </th>

                        <th>
                            Request Title
                        </th>

                        <th>
                            PTJ Department
                        </th>

                        <th>
                            {{ $isBendahari
                                ? 'Approved Amount'
                                : 'Estimated Amount' }}
                        </th>
                        <th>
                            Submitted
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($pendingRequests as $procurementRequest)

                        <tr>

                            <td>

                                <span class="request-id">
                                    PR-{{ str_pad(
                                        $procurementRequest->procurement_request_id,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}
                                </span>

                            </td>


                            <td>

                                <div class="request-title">
                                    {{ $procurementRequest->request_title }}
                                </div>

                                @if ($procurementRequest->request_description)

                                    <div class="request-description-small">
                                        {{ $procurementRequest->request_description }}
                                    </div>

                                @endif

                            </td>


                            <td>

                                <div class="requester-name">
                                    {{ $procurementRequest->ptjDepartment->department_name ?? '-' }}
                                </div>

                                <div class="requester-id">

                                    Staff ID:
                                    {{ $procurementRequest->user->staff_id ?? '-' }}

                                </div>

                            </td>


                            <td>

                                <span class="amount">

                                    RM
                                    {{ number_format(
                                        (float) (
                                            $isBendahari
                                                ? $procurementRequest->approved_amount
                                                : $procurementRequest->estimated_amount
                                        ),
                                        2
                                    ) }}

                                </span>

                            </td>


                            <td>

                                <div class="date-main">

                                    {{ optional(
                                        $procurementRequest->created_at
                                    )->format('d M Y') ?? '-' }}

                                </div>

                                <div class="date-time">

                                    {{ optional(
                                        $procurementRequest->created_at
                                    )->format('h:i A') ?? '' }}

                                </div>

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        $reviewRoute,
                                        $procurementRequest
                                    ) }}"
                                    class="review-button"
                                >

                                    Review

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <path
                                            d="M9 6L15 12L9 18"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />

                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             CUSTOM PAGINATION
        ====================================================== --}}

        @if ($pendingRequests->hasPages())

            <div class="pagination-area">

                <div class="pagination-info">

                    Showing

                    {{ $pendingRequests->firstItem() }}

                    to

                    {{ $pendingRequests->lastItem() }}

                    of

                    {{ $pendingRequests->total() }}

                    requests

                </div>


                <div class="pagination-links">


                    {{-- Previous --}}

                    @if ($pendingRequests->onFirstPage())

                        <span class="page-link disabled">
                            Previous
                        </span>

                    @else

                        <a
                            href="{{ $pendingRequests->previousPageUrl() }}"
                            class="page-link"
                        >
                            Previous
                        </a>

                    @endif


                    {{-- Page Numbers --}}

                    @for (
                        $page = 1;
                        $page <= $pendingRequests->lastPage();
                        $page++
                    )

                        @if ($page === $pendingRequests->currentPage())

                            <span class="page-link active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $pendingRequests->url($page) }}"
                                class="page-link"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    {{-- Next --}}

                    @if ($pendingRequests->hasMorePages())

                        <a
                            href="{{ $pendingRequests->nextPageUrl() }}"
                            class="page-link"
                        >
                            Next
                        </a>

                    @else

                        <span class="page-link disabled">
                            Next
                        </span>

                    @endif


                </div>

            </div>

        @endif


    @else

        <div class="empty-state">

            <div class="empty-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M5 12L10 17L19 8"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </div>


            <div class="empty-title">

                @if (
                    request()->filled('search') ||
                    request()->filled('amount') ||
                    request()->filled('ptj_id')
                )

                    No matching requests found

                @else

                    No pending approvals

                @endif

            </div>


            <div class="empty-description">

                @if (
                    request()->filled('search') ||
                    request()->filled('amount')
                )

                    Try changing or clearing the current filters.

                @else

                    @if ($isBendahari)

                        There are currently no KJ-approved procurement requests
                        waiting for Bendahari review.

                    @else

                        There are currently no procurement requests
                        waiting for your KJ decision.

                    @endif

                @endif

            </div>

        </div>

    @endif

</section>


@endsection