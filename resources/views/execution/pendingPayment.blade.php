@extends('layouts.ucpms')

@section('title', 'Pending Payments - UCPMS')


@section('page-style')

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-heading {
        margin-bottom: 22px;

        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 20px;
    }

    .breadcrumb {
        margin-bottom: 7px;

        color: var(--muted);

        font-size: 12px;
    }

    .breadcrumb a {
        color: var(--primary);

        font-weight: 650;
    }

    .page-title {
        color: var(--navy);

        font-size: 29px;
        line-height: 1.2;

        letter-spacing: -0.5px;
    }

    .page-description {
        margin-top: 7px;

        color: var(--muted);

        font-size: 13px;
    }


    /* =========================================================
       FINANCE OVERVIEW
    ========================================================= */

    .finance-overview {
        margin-bottom: 22px;

        display: grid;

        grid-template-columns:
            minmax(230px, 0.7fr)
            minmax(280px, 1.3fr);

        border: 1px solid var(--border);
        border-radius: 17px;

        background: var(--surface);

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .finance-overview-item {
        padding: 21px 23px;

        display: flex;
        align-items: center;

        gap: 15px;
    }

    .finance-overview-item + .finance-overview-item {
        border-left: 1px solid var(--border);
    }

    .finance-icon {
        width: 48px;
        height: 48px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .finance-icon svg {
        width: 23px;
        height: 23px;
    }

    .finance-label {
        margin-bottom: 5px;

        color: var(--muted);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .finance-value {
        color: var(--navy);

        font-size: 26px;
        font-weight: 800;

        line-height: 1;
    }

    .finance-amount {
        color: var(--primary);
    }

    .finance-note {
        margin-top: 6px;

        color: var(--muted);

        font-size: 10px;
    }


    /* =========================================================
       PAYMENT LIST PANEL
    ========================================================= */

    .payment-panel {
        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 17px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .payment-toolbar {
        padding: 18px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 18px;

        border-bottom: 1px solid var(--border);
    }

    .toolbar-title {
        color: var(--navy);

        font-size: 14px;
        font-weight: 750;
    }

    .toolbar-description {
        margin-top: 4px;

        color: var(--muted);

        font-size: 10px;
    }


    /* =========================================================
       SEARCH
    ========================================================= */

    .search-form {
        display: flex;

        gap: 8px;
    }

    .search-input {
        width: 275px;
        min-height: 40px;

        padding: 0 13px;

        border: 1px solid #dfe6ef;
        border-radius: 10px;

        outline: none;

        background: #ffffff;

        color: var(--text);

        font-size: 12px;

        transition: 0.2s;
    }

    .search-input:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px
            color-mix(
                in srgb,
                var(--primary) 10%,
                transparent
            );
    }

    .search-button {
        min-height: 40px;

        padding: 0 16px;

        border: none;
        border-radius: 10px;

        background: var(--primary);

        color: #ffffff;

        font-size: 11px;
        font-weight: 700;

        cursor: pointer;

        transition: 0.2s;
    }

    .search-button:hover {
        transform: translateY(-1px);
    }


    /* =========================================================
       ALERTS
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
       TABLE
    ========================================================= */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;
    }

    .payment-table {
        width: 100%;

        border-collapse: collapse;

        min-width: 1050px;
    }

    .payment-table thead th {
        padding: 15px 16px;

        border-bottom: 1px solid var(--border);

        background: #fafbfd;

        color: #536179;

        font-size: 10px;
        font-weight: 750;

        text-align: left;

        text-transform: uppercase;
        letter-spacing: 0.2px;
    }

    .payment-table tbody td {
        padding: 17px 16px;

        border-bottom: 1px solid var(--border);

        color: var(--text);

        font-size: 11px;

        vertical-align: middle;
    }

    .payment-table tbody tr:last-child td {
        border-bottom: none;
    }

    .payment-table tbody tr:hover {
        background: #fbfcfe;
    }

    .request-link {
        color: var(--primary);

        font-size: 11px;
        font-weight: 750;
    }

    .main-value {
        color: var(--navy);

        font-weight: 700;

        line-height: 1.45;
    }

    .sub-value {
        margin-top: 4px;

        color: var(--muted);

        font-size: 9px;

        line-height: 1.4;
    }

    .money-value {
        color: var(--navy);

        font-weight: 800;

        white-space: nowrap;
    }


    /* =========================================================
       PAYMENT BADGE
    ========================================================= */

    .payment-badge {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 6px 10px;

        border-radius: 999px;

        background: #fff7db;

        color: #a36a00;

        font-size: 10px;
        font-weight: 700;

        white-space: nowrap;
    }

    .payment-badge-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: #f2aa00;
    }

    .payment-tabs {
        margin-bottom: 20px;

        display: inline-flex;
        align-items: center;

        padding: 4px;

        border: 1px solid var(--border);
        border-radius: 11px;

        background: #ffffff;

        box-shadow: var(--shadow);
    }

    .payment-tab {
        min-height: 37px;

        padding: 0 14px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        border-radius: 8px;

        color: #66758d;

        font-size: 11px;
        font-weight: 700;

        transition: 0.2s;
    }

    .payment-tab:hover {
        color: var(--primary);
    }

    .payment-tab.active {
        background: var(--primary);

        color: #ffffff;
    }

    .payment-tab svg {
        width: 15px;
        height: 15px;
    }


    /* =========================================================
       ACTION
    ========================================================= */

    .view-button {
        min-height: 36px;

        padding: 0 12px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        border: 1px solid var(--primary);
        border-radius: 9px;

        background: var(--primary);

        color: #ffffff;

        font-size: 10px;
        font-weight: 700;

        white-space: nowrap;

        transition: 0.2s;
    }

    .view-button:hover {
        transform: translateY(-1px);

        color: #ffffff;
    }

    .view-button svg {
        width: 15px;
        height: 15px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 60px 20px;

        text-align: center;
    }

    .empty-icon {
        width: 56px;
        height: 56px;

        margin: 0 auto 13px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .empty-icon svg {
        width: 27px;
        height: 27px;
    }

    .empty-title {
        color: var(--navy);

        font-size: 14px;
        font-weight: 750;
    }

    .empty-text {
        max-width: 420px;

        margin: 6px auto 0;

        color: var(--muted);

        font-size: 11px;
        line-height: 1.5;
    }


    /* =========================================================
       FOOTER / PAGINATION
    ========================================================= */

    .table-footer {
        padding: 14px 18px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        border-top: 1px solid var(--border);
    }

    .table-info {
        color: var(--muted);

        font-size: 10px;
    }

    .pagination {
        display: flex;
        align-items: center;

        gap: 8px;
    }

    .pagination-button {
        min-height: 32px;

        padding: 0 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #dfe6ef;
        border-radius: 8px;

        background: #ffffff;

        color: #59677e;

        font-size: 10px;
        font-weight: 650;
    }

    .pagination-button:hover {
        border-color: var(--primary);

        color: var(--primary);
    }

    .pagination-button.disabled {
        opacity: 0.45;

        pointer-events: none;
    }

    .pagination-current {
        color: var(--muted);

        font-size: 10px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .finance-overview {
            grid-template-columns: 1fr;
        }

        .finance-overview-item + .finance-overview-item {
            border-left: none;
            border-top: 1px solid var(--border);
        }

        .payment-toolbar {
            align-items: stretch;

            flex-direction: column;
        }

        .search-input {
            width: 100%;
        }

        .search-form {
            width: 100%;
        }

        .search-form .search-input {
            flex: 1;
        }
    }


    @media (max-width: 650px) {

        .page-heading {
            align-items: stretch;

            flex-direction: column;
        }

        .finance-value {
            font-size: 22px;
        }

        .table-footer {
            align-items: flex-start;

            flex-direction: column;
        }
    }

@endsection



@section('content')


{{-- =========================================================
     PAGE HEADER
========================================================= --}}

<div class="page-heading">

    <div>

        <div class="breadcrumb">

            <a href="{{ route('bendahari.dashboard') }}">
                Dashboard
            </a>

            &nbsp;›&nbsp;

            Pending Payments

        </div>


        <h1 class="page-title">
            Pending Payment Orders
        </h1>


        <div class="page-description">

            Review completed deliveries that are awaiting
            Bendahari payment processing.

        </div>

    </div>

</div>



{{-- =========================================================
     ALERTS
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
     PAYMENT NAVIGATION
========================================================= --}}

<div class="payment-tabs">

    <a
        href="{{ route('bendahari.payments.index') }}"
        class="payment-tab active"
    >

        <svg viewBox="0 0 24 24" fill="none">

            <path
                d="M6 3H15L19 7V21H6V3Z"
                stroke="currentColor"
                stroke-width="1.7"
            />

            <path
                d="M15 3V8H19"
                stroke="currentColor"
                stroke-width="1.7"
            />

        </svg>

        Pending Payments

    </a>


    <a
        href="{{ route('bendahari.payments.history') }}"
        class="payment-tab"
    >

        <svg viewBox="0 0 24 24" fill="none">

            <path
                d="M3 12A9 9 0 1 0 6 5.3"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
            />

            <path
                d="M3 4V10H9"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

        </svg>

        Payment History

    </a>

</div>



{{-- =========================================================
     FINANCE OVERVIEW
========================================================= --}}

<div class="finance-overview">


    <div class="finance-overview-item">

        <div class="finance-icon">

            <svg viewBox="0 0 24 24" fill="none">

                <path
                    d="M6 3H15L19 7V21H6V3Z"
                    stroke="currentColor"
                    stroke-width="1.7"
                />

                <path
                    d="M15 3V8H19"
                    stroke="currentColor"
                    stroke-width="1.7"
                />

                <path
                    d="M9 12H16M9 16H14"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                />

            </svg>

        </div>


        <div>

            <div class="finance-label">
                Active Pending Payments
            </div>

            <div class="finance-value">
                {{ $totalPendingPayments }}
            </div>

            <div class="finance-note">
                Payment simulation required
            </div>

        </div>

    </div>



    <div class="finance-overview-item">

        <div class="finance-icon">

            <svg viewBox="0 0 24 24" fill="none">

                <rect
                    x="3"
                    y="6"
                    width="18"
                    height="13"
                    rx="2"
                    stroke="currentColor"
                    stroke-width="1.7"
                />

                <path
                    d="M3 10H21"
                    stroke="currentColor"
                    stroke-width="1.7"
                />

                <path
                    d="M7 15H11"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                />

            </svg>

        </div>


        <div>

            <div class="finance-label">
                Total Pending Value
            </div>

            <div class="finance-value finance-amount">

                RM {{
                    number_format(
                        (float) $totalPendingAmount,
                        2
                    )
                }}

            </div>

            <div class="finance-note">
                Combined value currently awaiting payment
            </div>

        </div>

    </div>


</div>



{{-- =========================================================
     PAYMENT LIST
========================================================= --}}

<div class="payment-panel">


    <div class="payment-toolbar">


        <div>

            <div class="toolbar-title">
                Payment Queue
            </div>

            <div class="toolbar-description">

                Completed PTJ deliveries with
                payment status Pending.

            </div>

        </div>



        <form
            method="GET"
            action="{{ route('bendahari.payments.index') }}"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="search-input"
                placeholder="Search request, LO, DO, vendor or PTJ..."
            >

            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

        </form>


    </div>



    <div class="table-wrapper">


        <table class="payment-table">


            <thead>

                <tr>

                    <th>
                        Request ID
                    </th>

                    <th>
                        PTJ Department
                    </th>

                    <th>
                        Vendor
                    </th>

                    <th>
                        Approved Total (RM)
                    </th>

                    <th>
                        Completed Date (PTJ)
                    </th>

                    <th>
                        Payment Status
                    </th>

                    <th>
                        Actions
                    </th>

                </tr>

            </thead>



            <tbody>


                @forelse ($pendingPayments as $payment)

                    @php

                        $localOrder =
                            $payment->localOrder;

                        $procurementRequest =
                            $localOrder
                                ?->procurementRequest;

                        $vendor =
                            $localOrder
                                ?->selectedQuotation
                                ?->vendor;

                    @endphp


                    <tr>


                        <td>

                            <div class="request-link">

                                REQ-{{
                                    str_pad(
                                        $procurementRequest
                                            ->procurement_request_id,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    )
                                }}

                            </div>

                            <div class="sub-value">
                                {{ $localOrder->lo_number }}
                            </div>

                        </td>



                        <td>

                            <div class="main-value">

                                {{
                                    $procurementRequest
                                        ->ptjDepartment
                                        ->department_name
                                    ?? '-'
                                }}

                            </div>

                        </td>



                        <td>

                            <div class="main-value">
                                {{ $vendor->company_name ?? '-' }}
                            </div>

                            <div class="sub-value">
                                DO: {{ $payment->do_number }}
                            </div>

                        </td>



                        <td>

                            <div class="money-value">

                                {{
                                    number_format(
                                        (float)
                                        $localOrder->total_amount,
                                        2
                                    )
                                }}

                            </div>

                        </td>



                        <td>

                            <div class="main-value">

                                {{
                                    $payment
                                        ->delivery_date
                                        ->format('d M Y')
                                }}

                            </div>

                            <div class="sub-value">
                                Physical delivery confirmed
                            </div>

                        </td>



                        <td>

                            <span class="payment-badge">

                                <span
                                    class="payment-badge-dot"
                                ></span>

                                Pending

                            </span>

                        </td>



                        <td>

                            <a
                                href="{{
                                    route(
                                        'bendahari.payments.show',
                                        $payment
                                            ->delivery_order_id
                                    )
                                }}"
                                class="view-button"
                            >

                                View Details

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >

                                    <path
                                        d="M5 12H19M14 7L19 12L14 17"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />

                                </svg>

                            </a>

                        </td>


                    </tr>


                @empty

                    <tr>

                        <td colspan="7">

                            <div class="empty-state">

                                <div class="empty-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >

                                        <path
                                            d="M5 12L10 17L19 7"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />

                                    </svg>

                                </div>


                                <div class="empty-title">
                                    No pending payments
                                </div>


                                <div class="empty-text">

                                    There are currently no completed
                                    deliveries waiting for Bendahari
                                    payment processing.

                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse


            </tbody>


        </table>


    </div>



    @if ($pendingPayments->total() > 0)

        <div class="table-footer">


            <div class="table-info">

                Showing

                {{ $pendingPayments->firstItem() }}

                -

                {{ $pendingPayments->lastItem() }}

                of

                {{ $pendingPayments->total() }}

                pending payment(s)

            </div>



            @if ($pendingPayments->hasPages())

                <div class="pagination">


                    @if ($pendingPayments->onFirstPage())

                        <span
                            class="
                                pagination-button
                                disabled
                            "
                        >
                            Previous
                        </span>

                    @else

                        <a
                            href="{{
                                $pendingPayments
                                    ->previousPageUrl()
                            }}"
                            class="pagination-button"
                        >
                            Previous
                        </a>

                    @endif



                    <span class="pagination-current">

                        Page
                        {{ $pendingPayments->currentPage() }}
                        of
                        {{ $pendingPayments->lastPage() }}

                    </span>



                    @if ($pendingPayments->hasMorePages())

                        <a
                            href="{{
                                $pendingPayments
                                    ->nextPageUrl()
                            }}"
                            class="pagination-button"
                        >
                            Next
                        </a>

                    @else

                        <span
                            class="
                                pagination-button
                                disabled
                            "
                        >
                            Next
                        </span>

                    @endif


                </div>

            @endif


        </div>

    @endif


</div>

@endsection