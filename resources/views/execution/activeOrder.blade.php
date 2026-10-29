@extends('layouts.ucpms')

@section('title', 'Active Orders - UCPMS')


@section('page-style')

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        margin-bottom: 24px;
    }

    .breadcrumb {
        margin-bottom: 8px;

        font-size: 12px;
        color: var(--muted);
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

    .confirm-delivery-button {
    min-height: 36px;

    padding: 0 11px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    border: 1px solid var(--primary);
    border-radius: 9px;

    background: var(--primary);

    color: #ffffff;

    font-size: 11px;
    font-weight: 700;

    white-space: nowrap;

    transition: 0.2s;
}

.confirm-delivery-button:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

.confirm-delivery-button svg {
    width: 16px;
    height: 16px;
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
        color: var(--navy);

        font-size: 28px;
        font-weight: 800;
    }

    .summary-note {
        margin-top: 4px;

        color: var(--muted);

        font-size: 11px;
    }


    /* =========================================================
       ORDER PANEL
    ========================================================= */

    .order-panel {
        background: var(--surface);

        border: 1px solid var(--border);

        border-radius: 18px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }


    /* =========================================================
       TOOLBAR
    ========================================================= */

    .order-toolbar {
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

        transition: 0.2s ease;
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
        width: 245px;
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

        font-size: 12px;
        font-weight: 700;

        cursor: pointer;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;
    }

    .order-table {
        width: 100%;

        min-width: 1050px;

        border-collapse: collapse;
    }

    .order-table th,
    .order-table td {
        padding: 17px 19px;

        text-align: left;

        border-bottom: 1px solid #edf1f6;

        font-size: 13px;

        vertical-align: middle;
    }

    .order-table th {
        background: #fbfcfe;

        color: #59677e;

        font-size: 12px;
        font-weight: 700;
    }

    .request-id {
        color: var(--primary);

        font-weight: 800;

        white-space: nowrap;
    }

    .request-title-text {
        color: var(--navy);

        font-weight: 650;
    }

    .vendor-name {
        color: #35425a;

        font-weight: 650;
    }

    .amount-text {
        color: #35425a;

        font-weight: 700;

        white-space: nowrap;
    }

    .lo-reference {
        margin-top: 4px;

        color: var(--muted);

        font-size: 11px;
    }


    /* =========================================================
       STATUS BADGES
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

    .status-success {
        background: var(--success-soft);

        color: var(--success);
    }

    .status-success .status-dot {
        background: var(--success);
    }


    /* =========================================================
       ACTIONS
    ========================================================= */

    .action-group {
        display: flex;
        align-items: center;

        gap: 8px;

        flex-wrap: wrap;
    }

    .action-button {
        min-height: 38px;

        padding: 0 13px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        border: 1px solid #dfe6ef;

        border-radius: 9px;

        background: #ffffff;

        color: var(--primary);

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;

        transition: 0.2s ease;
    }

    .action-button:hover {
        background: var(--primary-soft);

        border-color: var(--primary);
    }

    .action-button.primary {
        border-color: transparent;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );

        color: #ffffff;
    }

    .action-button.primary:hover {
        transform: translateY(-1px);
    }

    .action-button svg {
        width: 16px;
        height: 16px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 65px 20px;

        text-align: center;
    }

    .empty-icon {
        width: 60px;
        height: 60px;

        margin: 0 auto 14px;

        border-radius: 17px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .empty-icon svg {
        width: 29px;
        height: 29px;
    }

    .empty-title {
        margin-bottom: 5px;

        color: var(--navy);

        font-weight: 700;
    }

    .empty-text {
        color: var(--muted);

        font-size: 12px;

        line-height: 1.6;
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
        color: var(--muted);

        font-size: 12px;
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

    .alert-info {
        background: var(--primary-soft);

        color: var(--primary);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .order-toolbar {
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

        .table-footer {
            flex-direction: column;

            align-items: flex-start;

            gap: 12px;
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

            <a href="{{ route('ptj.dashboard') }}">
                Dashboard
            </a>

            &nbsp;›&nbsp;

            Active Orders

        </div>


        <h1 class="page-title">
            Active Orders
        </h1>


        <div class="page-description">
            Prepare Local Orders for approved procurement requests
            and view generated Local Order documents.
        </div>

    </div>

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


@if (session('info'))

    <div class="alert alert-info">
        {{ session('info') }}
    </div>

@endif



{{-- =========================================================
     SUMMARY
========================================================= --}}

<div class="summary-grid">


    <a
        href="{{ route('ptj.orders.index') }}"
        class="summary-card"
    >

        <div class="summary-label">
            Total Active Orders
        </div>

        <div class="summary-value">
            {{ $totalActiveOrders }}
        </div>

        <div class="summary-note">
            Orders currently under execution
        </div>

    </a>



    <a
        href="{{ route(
            'ptj.orders.index',
            ['stage' => 'ready']
        ) }}"
        class="summary-card"
    >

        <div class="summary-label">
            Ready for Local Order
        </div>

        <div class="summary-value">
            {{ $readyForLO }}
        </div>

        <div class="summary-note">
            Bendahari-approved requests
        </div>

    </a>



    <a
        href="{{ route(
            'ptj.orders.index',
            ['stage' => 'generated']
        ) }}"
        class="summary-card"
    >

        <div class="summary-label">
            LO Generated
        </div>

        <div class="summary-value">
            {{ $generatedLO }}
        </div>

        <div class="summary-note">
            Waiting for vendor delivery
        </div>

    </a>

</div>



{{-- =========================================================
     ACTIVE ORDER LIST
========================================================= --}}

<div class="order-panel">


    {{-- =====================================================
         TOOLBAR
    ====================================================== --}}

    <div class="order-toolbar">


        <div class="filter-tabs">

            <a
                href="{{ route('ptj.orders.index') }}"
                class="filter-tab {{
                    $stage === 'all'
                        ? 'active'
                        : ''
                }}"
            >
                All
            </a>


            <a
                href="{{ route(
                    'ptj.orders.index',
                    ['stage' => 'ready']
                ) }}"
                class="filter-tab {{
                    $stage === 'ready'
                        ? 'active'
                        : ''
                }}"
            >
                Ready for LO
            </a>


            <a
                href="{{ route(
                    'ptj.orders.index',
                    ['stage' => 'generated']
                ) }}"
                class="filter-tab {{
                    $stage === 'generated'
                        ? 'active'
                        : ''
                }}"
            >
                LO Generated
            </a>

        </div>



        <form
            method="GET"
            action="{{ route('ptj.orders.index') }}"
            class="search-form"
        >

            @if ($stage !== 'all')

                <input
                    type="hidden"
                    name="stage"
                    value="{{ $stage }}"
                >

            @endif


            <input
                type="text"
                name="search"
                value="{{ $search }}"
                class="search-box"
                placeholder="Search request or vendor..."
            >


            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

        </form>

    </div>



    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="table-wrapper">

        <table class="order-table">

            <thead>

                <tr>

                    <th>Request ID</th>

                    <th>Procurement Request</th>

                    <th>Winning Vendor</th>

                    <th>Approved Amount (RM)</th>

                    <th>Order Stage</th>

                    <th>Actions</th>

                </tr>

            </thead>



            <tbody>

                @forelse ($activeOrders as $activeOrder)

                    <tr>


                        {{-- Request ID --}}
                        <td>

                            <span class="request-id">

                                REQ-{{
                                    str_pad(
                                        $activeOrder
                                            ->procurement_request_id,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    )
                                }}

                            </span>

                        </td>



                        {{-- Procurement Request --}}
                        <td>

                            <div class="request-title-text">
                                {{ $activeOrder->request_title }}
                            </div>


                            @if (
                                $activeOrder->localOrder
                                && $activeOrder
                                    ->localOrder
                                    ->lo_number
                            )

                                <div class="lo-reference">

                                    LO:
                                    {{
                                        $activeOrder
                                            ->localOrder
                                            ->lo_number
                                    }}

                                </div>

                            @endif

                        </td>



                        {{-- Winning Vendor --}}
                        <td>

                            <span class="vendor-name">

                                {{
                                    $activeOrder
                                        ->selectedQuotation
                                        ?->vendor
                                        ?->company_name
                                    ?? 'Unavailable'
                                }}

                            </span>

                        </td>



                        {{-- Approved Amount --}}
                        <td>

                            <span class="amount-text">

                                {{
                                    number_format(
                                        (float)
                                        $activeOrder
                                            ->approved_amount,
                                        2
                                    )
                                }}

                            </span>

                        </td>



                        {{-- Order Stage --}}
                        <td>

                            @if (
                                $activeOrder->request_status
                                === 'Approved_Bendahari'
                            )

                                <span
                                    class="
                                        status-badge
                                        status-warning
                                    "
                                >

                                    <span
                                        class="status-dot"
                                    ></span>

                                    Ready for LO

                                </span>


                            @elseif (
                                $activeOrder->request_status
                                === 'LO_Generated'
                            )

                                <span
                                    class="
                                        status-badge
                                        status-success
                                    "
                                >

                                    <span
                                        class="status-dot"
                                    ></span>

                                    LO Generated

                                </span>

                            @endif

                        </td>



                        {{-- Actions --}}
                        <td>

                            <div class="action-group">


                                {{-- ==========================================
                                     Ready for Local Order
                                =========================================== --}}

                                @if (
                                    $activeOrder->request_status
                                        === 'Approved_Bendahari'
                                    && !$activeOrder->localOrder
                                )

                                    <a
                                        href="{{ route(
                                            'ptj.orders.draft-lo',
                                            $activeOrder
                                                ->procurement_request_id
                                        ) }}"
                                        class="
                                            action-button
                                            primary
                                        "
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >

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
                                            />

                                        </svg>

                                        Draft LO

                                    </a>


                                {{-- ==========================================
                                    Generated Local Order
                                =========================================== --}}

                                @elseif ($activeOrder->localOrder)

                                    <a
                                        href="{{
                                            route(
                                                'ptj.orders.local-order',
                                                $activeOrder
                                                    ->localOrder
                                                    ->local_order_id
                                            )
                                        }}"
                                        class="action-button"
                                        target="_blank"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >

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

                                        View LO

                                    </a>


                                    @if ($activeOrder->request_status === 'LO_Generated')

                                        <a
                                            href="{{
                                                route(
                                                    'ptj.orders.confirm-delivery',
                                                    $activeOrder
                                                        ->localOrder
                                                        ->local_order_id
                                                )
                                            }}"
                                            class="confirm-delivery-button"
                                        >

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >

                                                <path
                                                    d="M3 7H14V17H3V7Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />

                                                <path
                                                    d="M14 10H18L21 13V17H14V10Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />

                                                <path
                                                    d="M7 12L9 14L12 10"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />

                                            </svg>

                                            Confirm Delivery

                                        </a>

                                    @endif

                                @endif

                            </div>

                        </td>


                    </tr>


                @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">


                                <div class="empty-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >

                                        <path
                                            d="M4 8L12 4L20 8L12 12L4 8Z"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            d="M4 8V16L12 20L20 16V8"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            d="M12 12V20"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                    </svg>

                                </div>


                                <div class="empty-title">
                                    No active orders found
                                </div>


                                <div class="empty-text">

                                    Bendahari-approved procurement
                                    requests will appear here when
                                    they are ready for Local Order
                                    preparation.

                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if ($activeOrders->total() > 0)

        <div class="table-footer">


            <div class="table-info">

                Showing

                {{ $activeOrders->firstItem() }}

                -

                {{ $activeOrders->lastItem() }}

                of

                {{ $activeOrders->total() }}

                active order(s)

            </div>



            @if ($activeOrders->hasPages())

                <div class="pagination">


                    @if ($activeOrders->onFirstPage())

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
                                $activeOrders
                                    ->previousPageUrl()
                            }}"
                            class="pagination-button"
                        >
                            Previous
                        </a>

                    @endif



                    <span class="pagination-current">

                        Page

                        {{ $activeOrders->currentPage() }}

                        of

                        {{ $activeOrders->lastPage() }}

                    </span>



                    @if (
                        $activeOrders->hasMorePages()
                    )

                        <a
                            href="{{
                                $activeOrders
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