@extends('layouts.ucpms')

@section('title', 'Payment History - UCPMS')


@section('page-style')

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-heading {
        margin-bottom: 20px;
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
       PAYMENT TABS
    ========================================================= */

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
       HISTORY SUMMARY
    ========================================================= */

    .history-summary {
        margin-bottom: 22px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 19px 22px;

        border: 1px solid var(--border);
        border-radius: 16px;

        background:
            linear-gradient(
                135deg,
                #ffffff,
                var(--primary-soft)
            );

        box-shadow: var(--shadow);
    }

    .summary-left {
        display: flex;
        align-items: center;

        gap: 14px;
    }

    .summary-icon {
        width: 46px;
        height: 46px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: var(--success-soft);

        color: var(--success);
    }

    .summary-icon svg {
        width: 22px;
        height: 22px;
    }

    .summary-label {
        color: var(--muted);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.35px;
    }

    .summary-value {
        margin-top: 4px;

        color: var(--navy);

        font-size: 22px;
        font-weight: 800;
    }

    .summary-amount {
        text-align: right;
    }

    .summary-amount-value {
        margin-top: 4px;

        color: var(--success);

        font-size: 25px;
        font-weight: 850;
    }


    /* =========================================================
       HISTORY PANEL
    ========================================================= */

    .history-panel {
        border: 1px solid var(--border);
        border-radius: 17px;

        background: var(--surface);

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .history-toolbar {
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
        width: 290px;
        min-height: 40px;

        padding: 0 13px;

        border: 1px solid #dfe6ef;
        border-radius: 10px;

        outline: none;

        background: #ffffff;

        color: var(--text);

        font-size: 12px;
    }

    .search-input:focus {
        border-color: var(--primary);
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
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;
    }

    .history-table {
        width: 100%;

        min-width: 1120px;

        border-collapse: collapse;
    }

    .history-table thead th {
        padding: 15px 16px;

        border-bottom: 1px solid var(--border);

        background: #fafbfd;

        color: #536179;

        font-size: 10px;
        font-weight: 750;

        text-align: left;

        text-transform: uppercase;
    }

    .history-table tbody td {
        padding: 17px 16px;

        border-bottom: 1px solid var(--border);

        vertical-align: middle;

        color: var(--text);

        font-size: 11px;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-table tbody tr:hover {
        background: #fbfcfe;
    }

    .request-id {
        color: var(--primary);

        font-weight: 800;
    }

    .main-value {
        color: var(--navy);

        font-weight: 700;
    }

    .sub-value {
        margin-top: 4px;

        color: var(--muted);

        font-size: 9px;
    }

    .money-value {
        color: var(--navy);

        font-weight: 800;

        white-space: nowrap;
    }


    /* =========================================================
       PAID STATUS
    ========================================================= */

    .paid-badge {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 6px 10px;

        border-radius: 999px;

        background: var(--success-soft);

        color: var(--success);

        font-size: 10px;
        font-weight: 750;
    }

    .paid-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: currentColor;
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

        background: #ffffff;

        color: var(--primary);

        font-size: 10px;
        font-weight: 700;

        transition: 0.2s;
    }

    .view-button:hover {
        background: var(--primary);

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

        background: var(--success-soft);

        color: var(--success);
    }

    .empty-title {
        color: var(--navy);

        font-size: 14px;
        font-weight: 750;
    }

    .empty-text {
        margin-top: 6px;

        color: var(--muted);

        font-size: 11px;
    }


    /* =========================================================
       TABLE FOOTER
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

        border: 1px solid var(--border);
        border-radius: 8px;

        background: #ffffff;

        color: #59677e;

        font-size: 10px;
        font-weight: 650;
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

    @media (max-width: 850px) {

        .history-summary {
            align-items: flex-start;

            flex-direction: column;
        }

        .summary-amount {
            text-align: left;
        }

        .history-toolbar {
            align-items: stretch;

            flex-direction: column;
        }

        .search-form,
        .search-input {
            width: 100%;
        }

        .search-input {
            flex: 1;
        }
    }

@endsection



@section('content')


<div class="page-heading">

    <div class="breadcrumb">

        <a href="{{ route('bendahari.dashboard') }}">
            Dashboard
        </a>

        &nbsp;›&nbsp;

        Payment History

    </div>


    <h1 class="page-title">
        Payment History
    </h1>


    <div class="page-description">

        Review payment records that have already been
        processed and marked as Paid.

    </div>

</div>



{{-- PAYMENT NAVIGATION --}}

<div class="payment-tabs">

    <a
        href="{{ route('bendahari.payments.index') }}"
        class="payment-tab"
    >
        Pending Payments
    </a>


    <a
        href="{{ route('bendahari.payments.history') }}"
        class="payment-tab active"
    >
        Payment History
    </a>

</div>



{{-- HISTORY SUMMARY --}}

<div class="history-summary">

    <div class="summary-left">

        <div class="summary-icon">

            <svg viewBox="0 0 24 24" fill="none">

                <path
                    d="M5 12L10 17L19 7"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

            </svg>

        </div>


        <div>

            <div class="summary-label">
                Processed Payments
            </div>

            <div class="summary-value">
                {{ $totalPaidPayments }}
            </div>

        </div>

    </div>


    <div class="summary-amount">

        <div class="summary-label">
            Total Paid Value
        </div>

        <div class="summary-amount-value">

            RM {{
                number_format(
                    (float) $totalPaidAmount,
                    2
                )
            }}

        </div>

    </div>

</div>



{{-- HISTORY TABLE --}}

<div class="history-panel">


    <div class="history-toolbar">

        <div>

            <div class="toolbar-title">
                Processed Payment Records
            </div>

            <div class="toolbar-description">
                Historical Bendahari payment records.
            </div>

        </div>


        <form
            method="GET"
            action="{{ route('bendahari.payments.history') }}"
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

        <table class="history-table">

            <thead>

                <tr>

                    <th>Request ID</th>

                    <th>PTJ Department</th>

                    <th>Vendor</th>

                    <th>Paid Amount (RM)</th>

                    <th>Delivery Date</th>

                    <th>Payment Processed</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                @forelse ($paymentHistory as $payment)

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

                            <div class="request-id">

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

                        </td>


                        <td>

                            <div class="main-value">

                                {{
                                    $payment
                                        ->updated_at
                                        ->format(
                                            'd M Y, h:i A'
                                        )
                                }}

                            </div>

                        </td>


                        <td>

                            <span class="paid-badge">

                                <span class="paid-dot"></span>

                                Paid

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

                        <td colspan="8">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    ✓
                                </div>

                                <div class="empty-title">
                                    No processed payments yet
                                </div>

                                <div class="empty-text">

                                    Payments marked as Paid
                                    will appear here.

                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    @if ($paymentHistory->total() > 0)

        <div class="table-footer">

            <div class="table-info">

                Showing
                {{ $paymentHistory->firstItem() }}
                -
                {{ $paymentHistory->lastItem() }}
                of
                {{ $paymentHistory->total() }}
                payment record(s)

            </div>


            @if ($paymentHistory->hasPages())

                <div class="pagination">

                    @if ($paymentHistory->onFirstPage())

                        <span
                            class="pagination-button disabled"
                        >
                            Previous
                        </span>

                    @else

                        <a
                            href="{{
                                $paymentHistory
                                    ->previousPageUrl()
                            }}"
                            class="pagination-button"
                        >
                            Previous
                        </a>

                    @endif


                    <span class="pagination-current">

                        Page
                        {{ $paymentHistory->currentPage() }}
                        of
                        {{ $paymentHistory->lastPage() }}

                    </span>


                    @if ($paymentHistory->hasMorePages())

                        <a
                            href="{{
                                $paymentHistory
                                    ->nextPageUrl()
                            }}"
                            class="pagination-button"
                        >
                            Next
                        </a>

                    @else

                        <span
                            class="pagination-button disabled"
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