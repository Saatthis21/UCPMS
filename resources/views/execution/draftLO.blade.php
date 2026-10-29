@extends('layouts.ucpms')

@section('title', 'Draft Local Order - UCPMS')


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

    .back-button {
        min-height: 42px;

        padding: 0 15px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        border: 1px solid #dfe6ef;

        border-radius: 10px;

        background: #ffffff;

        color: #536179;

        font-size: 12px;
        font-weight: 700;

        transition: 0.2s ease;
    }

    .back-button:hover {
        border-color: var(--primary);

        color: var(--primary);
    }

    .back-button svg {
        width: 17px;
        height: 17px;
    }


    /* =========================================================
       APPROVED ORDER CARDS
    ========================================================= */

    .approval-cards {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr);

        gap: 18px;

        margin-bottom: 22px;
    }

    .approval-card {
        min-height: 145px;

        padding: 21px 22px;

        display: flex;
        align-items: flex-start;

        gap: 16px;

        background: var(--surface);

        border: 1px solid var(--border);

        border-radius: 16px;

        box-shadow: var(--shadow);
    }

    .approval-card-icon {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 12px;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .approval-card-icon svg {
        width: 20px;
        height: 20px;
    }

    .approval-card-content {
        min-width: 0;

        padding-top: 1px;
    }

    .approval-card-label {
        margin-bottom: 7px;

        color: var(--muted);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.4px;
    }

    .approval-card-value {
        color: var(--navy);

        font-size: 17px;
        font-weight: 750;

        line-height: 1.4;
    }

    .approval-card-amount {
        color: var(--primary);

        font-size: 27px;
        font-weight: 800;

        letter-spacing: -0.5px;
    }

    .approval-card-note {
        margin-top: 6px;

        color: var(--muted);

        font-size: 10px;

        line-height: 1.55;
    }

    .locked-indicator {
        margin-top: 10px;

        display: inline-flex;
        align-items: center;

        gap: 5px;

        padding: 5px 8px;

        border-radius: 999px;

        background: var(--success-soft);

        color: var(--success);

        font-size: 9px;
        font-weight: 700;
    }

    .locked-indicator svg {
        width: 12px;
        height: 12px;
    }


    /* =========================================================
       MAIN WORKSPACE
    ========================================================= */

    .draft-workspace {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            300px;

        gap: 18px;

        align-items: start;
    }

    .draft-workspace > form {
        min-width: 0;
    }


    /* =========================================================
       MAIN DRAFT PANEL
    ========================================================= */

    .draft-panel {
        min-width: 0;

        background: var(--surface);

        border: 1px solid var(--border);

        border-radius: 18px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .draft-panel-header {
        padding: 20px 22px;

        border-bottom: 1px solid var(--border);
    }

    .panel-title {
        color: var(--navy);

        font-size: 16px;
        font-weight: 750;
    }

    .panel-description {
        margin-top: 5px;

        color: var(--muted);

        font-size: 12px;

        line-height: 1.5;
    }

    .draft-panel-body {
        padding: 22px;
    }


    /* =========================================================
       REQUEST META STRIP
    ========================================================= */

    .request-meta-strip {
        display: grid;

        grid-template-columns:
            minmax(110px, 0.45fr)
            minmax(260px, 1.45fr)
            minmax(170px, 0.85fr);

        margin-bottom: 22px;

        border: 1px solid var(--border);

        border-radius: 11px;

        background: #fbfcfe;

        overflow: hidden;
    }

    .request-meta-item {
        min-width: 0;

        padding: 13px 15px;

        border-right: 1px solid var(--border);
    }

    .request-meta-item:last-child {
        border-right: none;
    }

    .request-meta-label {
        margin-bottom: 4px;

        color: var(--muted);

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.35px;
    }

    .request-meta-value {
        color: var(--navy);

        font-size: 11px;
        font-weight: 700;

        line-height: 1.5;
    }


    /* =========================================================
       ITEM TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;

        border: 1px solid var(--border);

        border-radius: 12px;
    }

    .item-table {
        width: 100%;

        min-width: 760px;

        border-collapse: collapse;
    }

    .item-table th,
    .item-table td {
        padding: 14px 15px;

        border-bottom: 1px solid #edf1f6;

        text-align: left;

        vertical-align: middle;

        font-size: 12px;
    }

    .item-table tr:last-child td {
        border-bottom: none;
    }

    .item-table th {
        background: #f8fafc;

        color: #59677e;

        font-size: 11px;
        font-weight: 700;
    }

    .item-number {
        width: 42px;

        color: var(--muted);

        font-weight: 700;
    }

    .item-name {
        color: var(--navy);

        font-weight: 700;
    }

    .item-description {
        margin-top: 4px;

        color: var(--muted);

        font-size: 11px;

        line-height: 1.45;
    }

    .quantity-value {
        color: #35425a;

        font-weight: 700;
    }


    /* =========================================================
       UNIT PRICE INPUT
    ========================================================= */

    .price-wrapper {
        position: relative;

        width: 145px;
    }

    .currency-prefix {
        position: absolute;

        left: 12px;
        top: 50%;

        transform: translateY(-50%);

        color: var(--muted);

        font-size: 11px;

        pointer-events: none;
    }

    .price-input {
        width: 100%;
        height: 40px;

        padding:
            0
            12px
            0
            38px;

        border: 1px solid #dfe6ef;

        border-radius: 9px;

        outline: none;

        background: #ffffff;

        color: var(--text);

        font-size: 12px;
        font-weight: 650;

        text-align: right;
    }

    .price-input:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px rgba(23, 105, 232, 0.08);
    }

    .line-total {
        min-width: 110px;

        color: var(--navy);

        font-weight: 750;

        text-align: right;
    }


    /* =========================================================
       TOTAL / VALIDATION AREA
    ========================================================= */

    .total-section {
        margin-top: 20px;

        padding-top: 20px;

        border-top: 1px solid var(--border);

        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 25px;
    }

    .validation-area {
        max-width: 520px;

        flex: 1;
    }

    .validation-title {
        margin-bottom: 6px;

        color: var(--navy);

        font-size: 12px;
        font-weight: 750;
    }

    .validation-text {
        color: var(--muted);

        font-size: 11px;

        line-height: 1.6;
    }

    .validation-state {
        margin-top: 10px;

        padding: 10px 12px;

        border-radius: 9px;

        font-size: 11px;
        font-weight: 700;

        line-height: 1.5;
    }

    .validation-state.match {
        background: var(--success-soft);

        color: var(--success);
    }

    .validation-state.mismatch {
        background: var(--warning-soft);

        color: #ad6900;
    }

    .totals-box {
        width: 280px;

        flex-shrink: 0;
    }

    .total-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 8px 0;

        color: var(--muted);

        font-size: 12px;
    }

    .total-row strong {
        color: var(--navy);
    }

    .total-row.approved {
        margin-top: 4px;

        padding-top: 12px;

        border-top: 1px solid var(--border);

        font-weight: 700;
    }

    .approved-total-value {
        color: var(--primary) !important;

        font-size: 18px;
        font-weight: 800;
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .form-actions {
        margin-top: 22px;

        padding-top: 20px;

        border-top: 1px solid var(--border);

        display: flex;
        align-items: center;
        justify-content: flex-end;

        gap: 10px;
    }

    .cancel-button,
    .generate-button {
        min-height: 44px;

        padding: 0 17px;

        border-radius: 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        font-size: 12px;
        font-weight: 700;
    }

    .cancel-button {
        border: 1px solid #dfe6ef;

        background: #ffffff;

        color: #59677e;
    }

    .cancel-button:hover {
        border-color: var(--primary);

        color: var(--primary);
    }

    .generate-button {
        border: none;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );

        color: #ffffff;

        cursor: pointer;

        box-shadow: var(--shadow);

        transition: 0.2s ease;
    }

    .generate-button:hover:not(:disabled) {
        transform: translateY(-1px);
    }

    .generate-button:disabled {
        opacity: 0.45;

        cursor: not-allowed;

        box-shadow: none;
    }

    .cancel-button svg,
    .generate-button svg {
        width: 17px;
        height: 17px;
    }


    /* =========================================================
       ORDER REFERENCE / WINNING QUOTATION
    ========================================================= */

    .reference-column {
        position: sticky;

        top: 115px;
    }

    .reference-panel {
        background: var(--surface);

        border: 1px solid var(--border);

        border-radius: 16px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .reference-header {
        display: flex;
        align-items: center;

        gap: 10px;

        padding: 16px 17px;

        background: #fbfcfe;

        border-bottom: 1px solid var(--border);
    }

    .reference-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 10px;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .reference-icon svg {
        width: 17px;
        height: 17px;
    }

    .reference-title {
        color: var(--navy);

        font-size: 13px;
        font-weight: 750;
    }

    .reference-subtitle {
        margin-top: 2px;

        color: var(--muted);

        font-size: 9px;
    }

    .reference-body {
        padding: 18px;
    }

    .reference-section + .reference-section {
        margin-top: 16px;

        padding-top: 16px;

        border-top: 1px solid var(--border);
    }

    .reference-label {
        margin-bottom: 5px;

        color: var(--muted);

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.35px;
    }

    .reference-value {
        color: var(--navy);

        font-size: 13px;
        font-weight: 750;

        line-height: 1.4;

        word-break: break-word;
    }

    .reference-amount {
        color: var(--primary);

        font-size: 20px;
        font-weight: 800;
    }

    .reference-meta {
        margin-top: 5px;

        color: var(--muted);

        font-size: 10px;

        line-height: 1.5;
    }

    .quotation-button {
        width: 100%;

        min-height: 40px;

        margin-top: 18px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        border: 1px solid var(--primary);

        border-radius: 9px;

        background: var(--primary-soft);

        color: var(--primary);

        font-size: 11px;
        font-weight: 700;

        transition: 0.2s ease;
    }

    .quotation-button:hover {
        background: var(--primary);

        color: #ffffff;
    }

    .quotation-button svg {
        width: 16px;
        height: 16px;
    }

    .reference-note {
        margin-top: 14px;

        padding: 10px 11px;

        border-radius: 9px;

        background: #f8fafc;

        color: var(--muted);

        font-size: 9px;

        line-height: 1.55;
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

        line-height: 1.5;
    }

    .alert-error {
        background: var(--danger-soft);

        color: var(--danger);
    }

    .error-list {
        padding-left: 18px;
    }

    .error-list li + li {
        margin-top: 4px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1150px) {

        .draft-workspace {
            grid-template-columns: 1fr;
        }

        .reference-column {
            position: static;
        }

        .reference-body {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 16px;
        }

        .reference-section + .reference-section {
            margin-top: 0;

            padding-top: 0;
            padding-left: 16px;

            border-top: none;
            border-left: 1px solid var(--border);
        }

        .quotation-button {
            grid-column: 1 / -1;
        }

        .reference-note {
            grid-column: 1 / -1;

            margin-top: 0;
        }
    }


    @media (max-width: 850px) {

        .approval-cards {
            grid-template-columns: 1fr;
        }

        .request-meta-strip {
            grid-template-columns: 1fr 1fr;
        }

        .request-meta-item:nth-child(2) {
            border-right: none;
        }

        .request-meta-item:nth-child(3) {
            grid-column: 1 / -1;

            border-top: 1px solid var(--border);
        }

        .total-section {
            flex-direction: column;

            align-items: stretch;
        }

        .totals-box {
            width: 100%;
        }

        .reference-body {
            grid-template-columns: 1fr;
        }

        .reference-section + .reference-section {
            padding-left: 0;
            padding-top: 14px;

            border-left: none;
            border-top: 1px solid var(--border);
        }

        .quotation-button,
        .reference-note {
            grid-column: auto;
        }
    }


    @media (max-width: 650px) {

        .page-heading {
            flex-direction: column;

            align-items: stretch;
        }

        .approval-card {
            min-height: auto;
        }

        .request-meta-strip {
            grid-template-columns: 1fr;
        }

        .request-meta-item,
        .request-meta-item:nth-child(2),
        .request-meta-item:nth-child(3) {
            grid-column: auto;

            border-right: none;
            border-top: 1px solid var(--border);
        }

        .request-meta-item:first-child {
            border-top: none;
        }

        .form-actions {
            flex-direction: column-reverse;

            align-items: stretch;
        }

        .cancel-button,
        .generate-button {
            width: 100%;
        }
    }

@endsection



@section('content')

@php

    $quotation = $procurementRequest->selectedQuotation;

    $vendor = $quotation->vendor;

    $approvedAmount =
        (float) $procurementRequest->approved_amount;

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

            <a href="{{ route('ptj.orders.index') }}">
                Active Orders
            </a>

            &nbsp;›&nbsp;

            Draft LO

        </div>


        <h1 class="page-title">
            Draft Local Order
        </h1>


        <div class="page-description">

            Review the pre-filled Local Order information before
            generating the official LO document.

        </div>

    </div>


    <a
        href="{{ route('ptj.orders.index') }}"
        class="back-button"
    >

        <svg viewBox="0 0 24 24" fill="none">

            <path
                d="M15 6L9 12L15 18"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

        </svg>

        Back to Active Orders

    </a>

</div>



{{-- =========================================================
     ERRORS
========================================================= --}}

@if (session('error'))

    <div class="alert alert-error">
        {{ session('error') }}
    </div>

@endif


@if ($errors->any())

    <div class="alert alert-error">

        <ul class="error-list">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif



{{-- =========================================================
     APPROVED ORDER - TWO MAIN CARDS
========================================================= --}}

<div class="approval-cards">


    {{-- =====================================================
         WINNING VENDOR
    ====================================================== --}}

    <div class="approval-card">

        <div class="approval-card-icon">

            <svg viewBox="0 0 24 24" fill="none">

                <path
                    d="M4 20V9L12 4L20 9V20"
                    stroke="currentColor"
                    stroke-width="1.8"
                />

                <path
                    d="M8 20V14H16V20"
                    stroke="currentColor"
                    stroke-width="1.8"
                />

            </svg>

        </div>


        <div class="approval-card-content">

            <div class="approval-card-label">
                Winning Vendor
            </div>


            <div class="approval-card-value">
                {{ $vendor->company_name }}
            </div>


            <div class="approval-card-note">

                Selected during the approval process
                and locked for this Local Order.

            </div>


            <div class="locked-indicator">

                <svg viewBox="0 0 24 24" fill="none">

                    <rect
                        x="5"
                        y="10"
                        width="14"
                        height="10"
                        rx="2"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M8 10V7C8 4.8 9.6 3 12 3C14.4 3 16 4.8 16 7V10"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                </svg>

                Locked after approval

            </div>

        </div>

    </div>



    {{-- =====================================================
         APPROVED AMOUNT
    ====================================================== --}}

    <div class="approval-card">

        <div class="approval-card-icon">

            <svg viewBox="0 0 24 24" fill="none">

                <rect
                    x="4"
                    y="5"
                    width="16"
                    height="14"
                    rx="2"
                    stroke="currentColor"
                    stroke-width="1.8"
                />

                <path
                    d="M8 9H16M8 13H13"
                    stroke="currentColor"
                    stroke-width="1.8"
                />

            </svg>

        </div>


        <div class="approval-card-content">

            <div class="approval-card-label">
                Approved Amount
            </div>


            <div class="approval-card-amount">

                RM {{
                    number_format(
                        $approvedAmount,
                        2
                    )
                }}

            </div>


            <div class="approval-card-note">
                Final procurement amount approved by Bendahari.
            </div>


            <div class="locked-indicator">

                <svg viewBox="0 0 24 24" fill="none">

                    <rect
                        x="5"
                        y="10"
                        width="14"
                        height="10"
                        rx="2"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M8 10V7C8 4.8 9.6 3 12 3C14.4 3 16 4.8 16 7V10"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                </svg>

                Locked approved value

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     MAIN DRAFT WORKSPACE
========================================================= --}}

<div class="draft-workspace">


    {{-- =====================================================
         LEFT SIDE - LOCAL ORDER ITEMIZATION
    ====================================================== --}}

    <form
        method="POST"
        action="{{
            route(
                'ptj.orders.generate-lo',
                $procurementRequest->procurement_request_id
            )
        }}"
        id="localOrderForm"
    >

        @csrf


        <div class="draft-panel">


            {{-- Header --}}
            <div class="draft-panel-header">

                <div class="panel-title">
                    Local Order Itemization
                </div>


                <div class="panel-description">

                    Procurement items are pre-filled from the approved
                    request. Review the unit prices and ensure the
                    itemized total exactly matches the Approved Amount.

                </div>

            </div>



            <div class="draft-panel-body">


                {{-- =================================================
                     REQUEST INFORMATION
                ================================================== --}}

                <div class="request-meta-strip">


                    <div class="request-meta-item">

                        <div class="request-meta-label">
                            Request ID
                        </div>

                        <div class="request-meta-value">

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

                    </div>



                    <div class="request-meta-item">

                        <div class="request-meta-label">
                            Procurement Request
                        </div>

                        <div class="request-meta-value">
                            {{ $procurementRequest->request_title }}
                        </div>

                    </div>



                    <div class="request-meta-item">

                        <div class="request-meta-label">
                            PTJ Department
                        </div>

                        <div class="request-meta-value">

                            {{
                                $procurementRequest
                                    ->ptjDepartment
                                    ->department_name
                                ?? '-'
                            }}

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ITEM TABLE
                ================================================== --}}

                <div class="table-wrapper">

                    <table class="item-table">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>
                                    Item / Specification
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Unit Price (RM)
                                </th>

                                <th style="text-align: right;">
                                    Line Total (RM)
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach (
                                $procurementRequest->items
                                as $index => $item
                            )

                                @php

                                    $initialUnitPrice =
                                        old(
                                            'unit_prices.'
                                            . $item->request_item_id,
                                            $item->unit_price
                                        );

                                @endphp


                                <tr
                                    class="lo-item-row"
                                    data-quantity="{{ (int) $item->quantity }}"
                                >


                                    <td class="item-number">
                                        {{ $index + 1 }}
                                    </td>



                                    <td>

                                        <div class="item-name">
                                            {{ $item->item_name }}
                                        </div>


                                        @if ($item->item_description)

                                            <div class="item-description">
                                                {{ $item->item_description }}
                                            </div>

                                        @endif

                                    </td>



                                    <td>

                                        <span class="quantity-value">
                                            {{ $item->quantity }}
                                        </span>

                                    </td>



                                    <td>

                                        <div class="price-wrapper">

                                            <span class="currency-prefix">
                                                RM
                                            </span>


                                            <input
                                                type="number"
                                                name="unit_prices[{{ $item->request_item_id }}]"
                                                value="{{
                                                    number_format(
                                                        (float) $initialUnitPrice,
                                                        2,
                                                        '.',
                                                        ''
                                                    )
                                                }}"
                                                min="0"
                                                step="0.01"
                                                class="price-input unit-price-input"
                                                required
                                            >

                                        </div>

                                    </td>



                                    <td
                                        class="line-total"
                                        data-line-total
                                    >
                                        0.00
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                     TOTAL VALIDATION
                ================================================== --}}

                <div class="total-section">


                    <div class="validation-area">

                        <div class="validation-title">
                            Local Order Validation
                        </div>


                        <div class="validation-text">

                            The sum of all item line totals must equal
                            exactly the Approved Amount before the
                            official Local Order PDF can be generated.

                        </div>


                        <div
                            id="validationState"
                            class="
                                validation-state
                                mismatch
                            "
                        >

                            Checking itemized total...

                        </div>

                    </div>



                    <div class="totals-box">


                        <div class="total-row">

                            <span>
                                Itemized Total
                            </span>


                            <strong>

                                RM
                                <span id="itemizedTotal">
                                    0.00
                                </span>

                            </strong>

                        </div>



                        <div class="total-row approved">

                            <span>
                                Approved Amount
                            </span>


                            <strong
                                class="approved-total-value"
                            >

                                RM {{
                                    number_format(
                                        $approvedAmount,
                                        2
                                    )
                                }}

                            </strong>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ACTIONS
                ================================================== --}}

                <div class="form-actions">


                    <a
                        href="{{ route('ptj.orders.index') }}"
                        class="cancel-button"
                    >

                        <svg viewBox="0 0 24 24" fill="none">

                            <path
                                d="M6 6L18 18M18 6L6 18"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />

                        </svg>

                        Cancel

                    </a>



                    <button
                        type="submit"
                        id="generateButton"
                        class="generate-button"
                        disabled
                    >

                        <svg viewBox="0 0 24 24" fill="none">

                            <path
                                d="M6 3H15L19 7V21H6V3Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <path
                                d="M15 3V8H19"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <path
                                d="M9 12H16M9 16H14"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                        </svg>

                        Generate LO Document

                    </button>

                </div>

            </div>

        </div>

    </form>



    {{-- =====================================================
         RIGHT SIDE - WINNING QUOTATION
    ====================================================== --}}

    <aside class="reference-column">


        <div class="reference-panel">


            <div class="reference-header">


                <div class="reference-icon">

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M6 3H15L19 7V21H6V3Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M15 3V8H19"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                </div>


                <div>

                    <div class="reference-title">
                        Order Reference
                    </div>


                    <div class="reference-subtitle">
                        Approved winning quotation
                    </div>

                </div>

            </div>



            <div class="reference-body">


                {{-- Quotation Reference --}}
                <div class="reference-section">

                    <div class="reference-label">
                        Winning Quotation
                    </div>


                    <div class="reference-value">

                        {{
                            $quotation->quotation_reference_number
                            ?: 'Quotation Document'
                        }}

                    </div>

                </div>



                {{-- Quotation Amount --}}
                <div class="reference-section">

                    <div class="reference-label">
                        Quotation Amount
                    </div>


                    <div class="reference-amount">

                        RM {{
                            number_format(
                                (float) $quotation->total_amount,
                                2
                            )
                        }}

                    </div>

                </div>



                {{-- Quotation Date --}}
                <div class="reference-section">

                    <div class="reference-label">
                        Quotation Date
                    </div>


                    <div class="reference-value">

                        {{
                            $quotation->quotation_date
                                ? $quotation
                                    ->quotation_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>



                {{-- View PDF --}}
                @if ($quotation->quotation_file_path)

                    <a
                        href="{{
                            asset(
                                'storage/'
                                . $quotation->quotation_file_path
                            )
                        }}"
                        target="_blank"
                        class="quotation-button"
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

                        View Winning Quotation

                    </a>

                @endif



                <div class="reference-note">

                    Use the approved winning quotation as the
                    reference when reviewing the Local Order
                    item prices.

                </div>

            </div>

        </div>

    </aside>

</div>

@endsection



@section('page-script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const approvedAmountCents = Math.round(
        Number(@json($approvedAmount)) * 100
    );


    const itemRows =
        document.querySelectorAll('.lo-item-row');


    const itemizedTotalElement =
        document.getElementById('itemizedTotal');


    const validationState =
        document.getElementById('validationState');


    const generateButton =
        document.getElementById('generateButton');


    /*
    |--------------------------------------------------------------------------
    | Format Money
    |--------------------------------------------------------------------------
    */

    function formatMoney(cents) {

        return (cents / 100).toLocaleString(
            'en-MY',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Calculate Draft LO
    |--------------------------------------------------------------------------
    */

    function calculateLocalOrder() {

        let itemizedTotalCents = 0;


        itemRows.forEach(function (row) {

            const quantity =
                Number(row.dataset.quantity);


            const priceInput =
                row.querySelector(
                    '.unit-price-input'
                );


            const lineTotalElement =
                row.querySelector(
                    '[data-line-total]'
                );


            let unitPrice =
                Number(priceInput.value);


            if (
                Number.isNaN(unitPrice)
                || unitPrice < 0
            ) {
                unitPrice = 0;
            }


            const unitPriceCents =
                Math.round(unitPrice * 100);


            const lineTotalCents =
                unitPriceCents * quantity;


            itemizedTotalCents +=
                lineTotalCents;


            lineTotalElement.textContent =
                formatMoney(lineTotalCents);

        });


        itemizedTotalElement.textContent =
            formatMoney(itemizedTotalCents);



        /*
        |--------------------------------------------------------------------------
        | Exact Approved Amount Match
        |--------------------------------------------------------------------------
        */

        if (
            itemizedTotalCents
            === approvedAmountCents
        ) {

            validationState.classList.remove(
                'mismatch'
            );

            validationState.classList.add(
                'match'
            );


            validationState.textContent =
                'Itemized total matches the Approved Amount. '
                + 'The Local Order is ready to be generated.';


            generateButton.disabled = false;

        } else {

            validationState.classList.remove(
                'match'
            );

            validationState.classList.add(
                'mismatch'
            );


            const differenceCents =
                approvedAmountCents
                - itemizedTotalCents;


            if (differenceCents > 0) {

                validationState.textContent =
                    'Itemized total is RM '
                    + formatMoney(differenceCents)
                    + ' below the Approved Amount. '
                    + 'Review the unit prices before generating the LO.';

            } else {

                validationState.textContent =
                    'Itemized total exceeds the Approved Amount by RM '
                    + formatMoney(
                        Math.abs(differenceCents)
                    )
                    + '. Review the unit prices before generating the LO.';
            }


            generateButton.disabled = true;
        }
    }



    /*
    |--------------------------------------------------------------------------
    | Recalculate When Unit Price Changes
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.unit-price-input')
        .forEach(function (input) {

            input.addEventListener(
                'input',
                calculateLocalOrder
            );

        });



    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    calculateLocalOrder();

});

</script>

@endsection