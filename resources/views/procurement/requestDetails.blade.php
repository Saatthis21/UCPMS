@extends('layouts.ucpms')

@section('title', 'Procurement Request Details - UCPMS')


@section('page-style')

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .details-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;

        margin-bottom: 22px;
    }

    .breadcrumb {
        margin-bottom: 8px;

        color: var(--muted);
        font-size: 12px;
    }

    .breadcrumb a {
        color: var(--primary);
        font-weight: 600;
    }

    .details-title-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title {
        margin: 0;

        color: var(--navy);

        font-size: 29px;
        line-height: 1.2;

        letter-spacing: -0.5px;
    }

    .request-number {
        color: var(--primary);
    }

    .page-description {
        margin-top: 7px;

        color: var(--muted);
        font-size: 13px;
    }


    /* =========================================================
       HEADER ACTIONS
    ========================================================= */

    .header-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .back-button,
    .edit-request-button {
        min-height: 42px;

        padding: 0 16px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        border-radius: 10px;

        font-size: 12px;
        font-weight: 700;

        transition: 0.2s ease;
    }

    .back-button {
        border: 1px solid #dfe6ef;

        background: #ffffff;
        color: #536179;
    }

    .back-button:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .edit-request-button {
        border: none;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );

        color: #ffffff;

        box-shadow: var(--shadow);
    }

    .edit-request-button:hover {
        transform: translateY(-1px);
    }


    /* =========================================================
       STATUS BADGE
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 7px 11px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 750;

        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;
    }

    .status-warning {
        background: var(--warning-soft);
        color: #a96500;
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
        color: #66748a;
    }

    .status-neutral .status-dot {
        background: #8794a8;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .summary-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 15px;

        margin-bottom: 20px;
    }

    .summary-card {
        padding: 17px 18px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 14px;

        box-shadow: var(--shadow);
    }

    .summary-label {
        margin-bottom: 7px;

        color: var(--muted);

        font-size: 10px;
        font-weight: 600;
    }

    .summary-value {
        color: var(--navy);

        font-size: 15px;
        font-weight: 800;

        word-break: break-word;
    }

    .summary-value.amount {
        color: var(--primary);
        font-size: 18px;
    }


    /* =========================================================
       PROGRESS TRACKER
    ========================================================= */

    .progress-card {
        margin-bottom: 20px;
        padding: 21px 22px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow: var(--shadow);
    }

    .progress-header {
        margin-bottom: 20px;
    }

    .progress-title {
        color: var(--navy);

        font-size: 15px;
        font-weight: 800;
    }

    .progress-subtitle {
        margin-top: 3px;

        color: var(--muted);
        font-size: 11px;
    }

    .progress-track {
        display: grid;

        grid-template-columns:
            repeat(6, minmax(0, 1fr));

        position: relative;
    }

    .progress-track::before {
        content: "";

        position: absolute;

        top: 15px;
        left: 8.3%;
        right: 8.3%;

        height: 2px;

        background: #e5eaf1;

        z-index: 0;
    }

    .progress-step {
        position: relative;
        z-index: 1;

        text-align: center;
    }

    .progress-circle {
        width: 31px;
        height: 31px;

        margin: 0 auto 8px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 2px solid #d8e0ea;
        border-radius: 50%;

        background: #ffffff;
        color: #8592a6;

        font-size: 10px;
        font-weight: 800;
    }

    .progress-step.completed .progress-circle {
        border-color: var(--success);
        background: var(--success);
        color: #ffffff;
    }

    .progress-step.current .progress-circle {
        border-color: var(--primary);
        background: var(--primary);
        color: #ffffff;

        box-shadow:
            0 0 0 5px var(--primary-soft);
    }

    .progress-label {
        color: #718096;

        font-size: 10px;
        font-weight: 650;
    }

    .progress-step.completed .progress-label,
    .progress-step.current .progress-label {
        color: var(--navy);
    }


    /* =========================================================
       COMMON DETAIL CARD
    ========================================================= */

    .detail-card {
        margin-bottom: 20px;

        overflow: hidden;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow: var(--shadow);
    }

    .detail-card-header {
        display: flex;
        align-items: center;
        gap: 10px;

        padding: 17px 20px;

        border-bottom: 1px solid var(--border);

        background: #fbfcfe;
    }

    .section-marker {
        width: 4px;
        height: 21px;

        border-radius: 999px;

        background:
            linear-gradient(
                180deg,
                var(--primary),
                var(--secondary)
            );
    }

    .detail-card-title {
        color: var(--navy);

        font-size: 14px;
        font-weight: 800;
    }

    .detail-card-subtitle {
        margin-top: 2px;

        color: var(--muted);
        font-size: 10px;
    }

    .detail-card-body {
        padding: 20px;
    }


    /* =========================================================
       GENERAL DETAILS
    ========================================================= */

    .detail-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 17px 26px;
    }

    .detail-field.full {
        grid-column: 1 / -1;
    }

    .detail-label {
        margin-bottom: 5px;

        color: var(--muted);

        font-size: 10px;
        font-weight: 650;

        text-transform: uppercase;

        letter-spacing: 0.3px;
    }

    .detail-value {
        color: var(--navy);

        font-size: 13px;
        font-weight: 650;

        line-height: 1.55;

        word-break: break-word;
    }

    .detail-description {
        padding: 13px 14px;

        border: 1px solid #e5eaf1;
        border-radius: 10px;

        background: #fafbfd;

        color: #4b5a70;

        font-size: 12px;
        line-height: 1.6;

        white-space: pre-line;
    }


    /* =========================================================
       ITEMS TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;
    }

    .details-table {
        width: 100%;
        min-width: 850px;

        border-collapse: collapse;
    }

    .details-table th,
    .details-table td {
        padding: 13px 14px;

        text-align: left;

        border-bottom: 1px solid #edf1f6;

        font-size: 12px;
    }

    .details-table th {
        background: #f7f9fc;

        color: #5f6e84;

        font-size: 10px;
        font-weight: 750;
    }

    .details-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-name {
        color: var(--navy);
        font-weight: 700;
    }

    .money {
        color: var(--navy);
        font-weight: 700;

        white-space: nowrap;
    }

    .table-total-row td {
        background: #fafcfe;

        color: var(--navy);

        font-weight: 800;
    }


    /* =========================================================
       QUOTATIONS
    ========================================================= */

    .quotation-count {
        margin-left: auto;

        padding: 5px 9px;

        border-radius: 999px;

        background: var(--primary-soft);
        color: var(--primary);

        font-size: 10px;
        font-weight: 750;
    }

    .quotation-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 15px;
    }

    .quotation-card {
        overflow: hidden;

        border: 1px solid rgba(23, 105, 232, 0.18);
        border-radius: 13px;

        background: #ffffff;
    }

    .quotation-card-header {
        padding: 14px 15px;

        border-bottom: 1px solid #e8edf5;

        background:
            linear-gradient(
                135deg,
                rgba(23, 105, 232, 0.06),
                rgba(24, 184, 178, 0.035)
            );
    }

    .quotation-number {
        margin-bottom: 3px;

        color: var(--primary);

        font-size: 10px;
        font-weight: 750;
    }

    .quotation-vendor {
        color: var(--navy);

        font-size: 13px;
        font-weight: 800;

        word-break: break-word;
    }

    .quotation-body {
        padding: 15px;
    }

    .quotation-info-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;

        padding: 8px 0;

        border-bottom: 1px solid #f0f3f7;
    }

    .quotation-info-row:last-of-type {
        border-bottom: none;
    }

    .quotation-info-label {
        color: var(--muted);

        font-size: 10px;
    }

    .quotation-info-value {
        max-width: 60%;

        color: var(--navy);

        text-align: right;

        font-size: 11px;
        font-weight: 700;

        word-break: break-word;
    }

    .quotation-amount {
        color: var(--primary);

        font-size: 14px;
        font-weight: 850;
    }


    /* =========================================================
       PDF BUTTON
    ========================================================= */

    .pdf-button {
        width: 100%;

        min-height: 39px;

        margin-top: 13px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        border: 1px solid rgba(220, 53, 69, 0.25);
        border-radius: 9px;

        background: #fff1f2;
        color: #c82333;

        font-size: 11px;
        font-weight: 750;

        transition: 0.2s;
    }

    .pdf-button:hover {
        border-color: #dc3545;
        background: #fdecee;
        color: #b51f2e;
    }

    .pdf-button svg {
        width: 16px;
        height: 16px;
    }

    /* =========================================================
       NOTICE
    ========================================================= */

    .status-notice {
        margin-bottom: 20px;

        padding: 13px 15px;

        border-radius: 11px;

        font-size: 11px;
        font-weight: 650;
    }

    .status-notice.info {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .status-notice.warning {
        background: var(--warning-soft);
        color: #9d6200;
    }

    .status-notice.danger {
        background: var(--danger-soft);
        color: var(--danger);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .quotation-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 800px) {

        .details-page-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .back-button,
        .edit-request-button {
            flex: 1;
        }

        .detail-grid,
        .summary-grid,
        .quotation-grid {
            grid-template-columns: 1fr;
        }

        .progress-track {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 20px 0;
        }

        .progress-track::before {
            display: none;
        }
    }

    @media (max-width: 520px) {

        .header-actions {
            flex-direction: column;
        }

        .progress-track {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }
    }

    .selected-badge {
        display: inline-block;
        margin-left: 6px;
        padding: 2px 8px;
        border-radius: 999px;
        background: var(--success-soft);
        color: var(--success);
        font-size: 9px;
        font-weight: 750;
    }

    .doc-button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .doc-button-row .pdf-button {
        width: auto;
        min-width: 190px;
        margin-top: 0;
        padding: 0 16px;
    }

    .rating-text {
        color: #b56c00;
        letter-spacing: 2px;
    }


@endsection


@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Status Display
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
            'label' => 'Returned for Amendment',
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
            'label' => 'Local Order Generated',
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


    $displayStatus =
        $statusConfig[$procurementRequest->request_status]
        ?? [
            'label' => $procurementRequest->request_status,
            'class' => 'status-neutral',
        ];


    /*
    |--------------------------------------------------------------------------
    | Department Name
    |--------------------------------------------------------------------------
    */

    $departmentName =
        optional($procurementRequest->ptjDepartment)->department_name
        ?? optional($procurementRequest->ptjDepartment)->ptj_name
        ?? '-';


    /*
    |--------------------------------------------------------------------------
    | Workflow Progress
    |--------------------------------------------------------------------------
    |
    | This is display-only. The database request_status remains the source
    | of truth.
    |--------------------------------------------------------------------------
    */

    $progressLevel = match ($procurementRequest->request_status) {

        'Draft',
        'Pending_Edit' => 1,

        'Pending_KJ',
        'Rejected_KJ' => 2,

        'Approved_KJ',
        'Rejected_Bendahari' => 3,

        'Approved_Bendahari' => 4,

        'LO_Generated' => 5,

        'Delivery_Received',
        'Completed' => 7,

        default => 1,
    };


    $progressSteps = [

        1 => 'Submitted',
        2 => 'KJ Approval',
        3 => 'Bendahari',
        4 => 'Approved',
        5 => 'Local Order',
        6 => 'Delivery',

    ];

@endphp


{{-- =========================================================
     PAGE HEADER
========================================================= --}}

<div class="details-page-header">

    <div>

        <div class="breadcrumb">

            <a href="{{ route('ptj.dashboard') }}">
                Dashboard
            </a>

            &nbsp;›&nbsp;

            <a href="{{ route('ptj.procurement.index') }}">
                Procurement Request
            </a>

            &nbsp;›&nbsp;

            Request Details

        </div>


        <div class="details-title-row">

            <h1 class="page-title">

                Procurement Request

                <span class="request-number">

                    REQ-{{ str_pad(
                        $procurementRequest->procurement_request_id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ) }}

                </span>

            </h1>


            <span
                class="
                    status-badge
                    {{ $displayStatus['class'] }}
                "
            >

                <span class="status-dot"></span>

                {{ $displayStatus['label'] }}

            </span>

        </div>


        <div class="page-description">

            Review the complete procurement request,
            submitted items and vendor quotations.

        </div>

    </div>


    <div class="header-actions">

        <a
            href="{{ route('ptj.procurement.index') }}"
            class="back-button"
        >
            ← Back to Request List
        </a>


        @if ($procurementRequest->request_status === 'Pending_Edit')

            <a
                href="{{ route(
                    'ptj.procurement.edit',
                    $procurementRequest->procurement_request_id
                ) }}"
                class="edit-request-button"
            >
                Edit & Resubmit
            </a>

        @endif

    </div>

</div>


{{-- =========================================================
     STATUS NOTICE
========================================================= --}}

@if ($procurementRequest->request_status === 'Pending_KJ')

    <div class="status-notice warning">
        This request has been submitted and is currently waiting for KJ approval.
        Procurement information cannot be modified while it is under review.
    </div>

@elseif ($procurementRequest->request_status === 'Approved_KJ')

    <div class="status-notice info">
        This request has been approved by KJ and is waiting for Bendahari approval.
    </div>

@elseif ($procurementRequest->request_status === 'Pending_Edit')

    <div class="status-notice info">
        This request has been returned for amendment.
        Review the requested corrections and use Edit & Resubmit to update it.
    </div>

@elseif ($procurementRequest->request_status === 'Rejected_KJ')

    <div class="status-notice danger">

        <div>
            <strong>
                This procurement request has been rejected by KJ.
            </strong>
        </div>

        @if ($rejectionRemarks)

            <div style="margin-top: 8px;">
                <strong>Reason:</strong>
                {{ $rejectionRemarks }}
            </div>

        @endif

    </div>


@elseif ($procurementRequest->request_status === 'Rejected_Bendahari')

    <div class="status-notice danger">

        <div>
            <strong>
                This procurement request has been rejected by Bendahari.
            </strong>
        </div>

        @if ($rejectionRemarks)

            <div style="margin-top: 8px;">
                <strong>Reason:</strong>
                {{ $rejectionRemarks }}
            </div>

        @endif

    </div>


@elseif ($procurementRequest->request_status === 'Approved_Bendahari')

    <div class="status-notice info">
        Approved by Bendahari. Go to Active Orders to draft the Local Order.
    </div>

@elseif ($procurementRequest->request_status === 'LO_Generated')

    <div class="status-notice info">
        Local Order generated. Confirm delivery in Active Orders once the vendor delivers.
    </div>

@elseif ($procurementRequest->request_status === 'Completed')

    <div class="status-notice info" style="background: var(--success-soft); color: var(--success);">
        This procurement is complete. Delivery was confirmed and all records are shown below.
    </div>

@endif


{{-- =========================================================
     REQUEST SUMMARY
========================================================= --}}

<div class="summary-grid">

    <div class="summary-card">

        <div class="summary-label">
            Estimated Amount
        </div>

        <div class="summary-value amount">

            RM {{ number_format(
                (float) $procurementRequest->estimated_amount,
                2
            ) }}

        </div>

    </div>


    <div class="summary-card">

        <div class="summary-label">
            Request Items
        </div>

        <div class="summary-value">
            {{ $procurementRequest->items->count() }}
        </div>

    </div>


    <div class="summary-card">

        <div class="summary-label">
            Date Submitted
        </div>

        <div class="summary-value">

            {{ $procurementRequest->created_at
                ? $procurementRequest->created_at->format('d M Y')
                : '-' }}

        </div>

    </div>


    <div class="summary-card">

        <div class="summary-label">
            Vendor Quotations
        </div>

        <div class="summary-value">
            {{ $procurementRequest->quotations->count() }}
        </div>

    </div>

</div>


{{-- =========================================================
     WORKFLOW PROGRESS
========================================================= --}}

<div class="progress-card">

    <div class="progress-header">

        <div class="progress-title">
            Procurement Progress
        </div>

        <div class="progress-subtitle">
            Current position of this request in the Procure-to-Pay workflow.
        </div>

    </div>


    <div class="progress-track">

        @foreach ($progressSteps as $level => $label)

            <div
                class="
                    progress-step
                    {{ $level < $progressLevel ? 'completed' : '' }}
                    {{ $level === $progressLevel ? 'current' : '' }}
                "
            >

                <div class="progress-circle">

                    @if ($level < $progressLevel)
                        ✓
                    @else
                        {{ $level }}
                    @endif

                </div>

                <div class="progress-label">
                    {{ $label }}
                </div>

            </div>

        @endforeach

    </div>

</div>


{{-- =========================================================
     GENERAL REQUEST DETAILS
========================================================= --}}

<div class="detail-card">

    <div class="detail-card-header">

        <div class="section-marker"></div>

        <div>

            <div class="detail-card-title">
                General Request Details
            </div>

            <div class="detail-card-subtitle">
                Information submitted by the PTJ staff.
            </div>

        </div>

    </div>


    <div class="detail-card-body">

        <div class="detail-grid">


            <div class="detail-field">

                <div class="detail-label">
                    Request ID
                </div>

                <div class="detail-value">

                    REQ-{{ str_pad(
                        $procurementRequest->procurement_request_id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ) }}

                </div>

            </div>


            <div class="detail-field">

                <div class="detail-label">
                    Request Title
                </div>

                <div class="detail-value">
                    {{ $procurementRequest->request_title }}
                </div>

            </div>


            <div class="detail-field">

                <div class="detail-label">
                    Department
                </div>

                <div class="detail-value">
                    {{ $departmentName }}
                </div>

            </div>


            <div class="detail-field">

                <div class="detail-label">
                    Submitted Date & Time
                </div>

                <div class="detail-value">

                    {{ $procurementRequest->created_at
                        ? $procurementRequest->created_at
                            ->format('d M Y, h:i A')
                        : '-' }}

                </div>

            </div>


            <div class="detail-field full">

                <div class="detail-label">
                    Request Description
                </div>

                <div class="detail-description">
                    {{ $procurementRequest->request_description }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     REQUEST ITEMS
========================================================= --}}

<div class="detail-card">

    <div class="detail-card-header">

        <div class="section-marker"></div>

        <div>

            <div class="detail-card-title">
                Request Items
            </div>

            <div class="detail-card-subtitle">
                Items included in the submitted procurement request.
            </div>

        </div>

    </div>


    <div class="table-wrapper">

        <table class="details-table">

            <thead>

                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price (RM)</th>
                    <th>Total Price (RM)</th>
                </tr>

            </thead>


            <tbody>

                @foreach ($procurementRequest->items as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>

                            <span class="item-name">
                                {{ $item->item_name }}
                            </span>

                        </td>

                        <td>
                            {{ $item->item_description ?: '-' }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td class="money">

                            {{ number_format(
                                (float) $item->unit_price,
                                2
                            ) }}

                        </td>

                        <td class="money">

                            {{ number_format(
                                (float) $item->total_price,
                                2
                            ) }}

                        </td>

                    </tr>

                @endforeach


                <tr class="table-total-row">

                    <td colspan="5" style="text-align: right;">
                        Estimated Amount
                    </td>

                    <td>

                        RM {{ number_format(
                            (float) $procurementRequest->estimated_amount,
                            2
                        ) }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>


{{-- =========================================================
     VENDOR QUOTATIONS
========================================================= --}}

<div class="detail-card">

    <div class="detail-card-header">

        <div class="section-marker"></div>

        <div>

            <div class="detail-card-title">
                Vendor Quotations
            </div>

            <div class="detail-card-subtitle">
                Structured quotation information and uploaded quotation documents.
            </div>

        </div>


        <div class="quotation-count">

            {{ $procurementRequest->quotations->count() }}
            quotation(s)

        </div>

    </div>


    <div class="detail-card-body">

        <div class="quotation-grid">

            @foreach (
                $procurementRequest->quotations
                as $quotation
            )

                <div class="quotation-card">

                    <div class="quotation-card-header">

                        <div class="quotation-number">
                            Quotation {{ $loop->iteration }}

                            @if ($quotation->quotation_id === $procurementRequest->selected_quotation_id)
                                <span class="selected-badge">Selected</span>
                            @endif
                        </div>

                        <div class="quotation-vendor">

                            {{ optional(
                                $quotation->vendor
                            )->company_name ?? 'Unknown Vendor' }}

                        </div>

                    </div>


                    <div class="quotation-body">


                        <div class="quotation-info-row">

                            <div class="quotation-info-label">
                                Reference No.
                            </div>

                            <div class="quotation-info-value">
                                {{ $quotation->quotation_reference_number }}
                            </div>

                        </div>


                        <div class="quotation-info-row">

                            <div class="quotation-info-label">
                                Quotation Date
                            </div>

                            <div class="quotation-info-value">

                                {{ $quotation->quotation_date
                                    ? $quotation->quotation_date
                                        ->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        <div class="quotation-info-row">

                            <div class="quotation-info-label">
                                Quoted Amount
                            </div>

                            <div
                                class="
                                    quotation-info-value
                                    quotation-amount
                                "
                            >

                                RM {{ number_format(
                                    (float) $quotation->total_amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="quotation-info-row">

                            <div class="quotation-info-label">
                                Warranty
                            </div>

                            <div class="quotation-info-value">

                                {{ $quotation->warranty_period }}
                                month(s)

                            </div>

                        </div>


                        <div class="quotation-info-row">

                            <div class="quotation-info-label">
                                Delivery Lead Time
                            </div>

                            <div class="quotation-info-value">

                                {{ $quotation->delivery_lead_time }}
                                day(s)

                            </div>

                        </div>


                        <div class="quotation-info-row">

                            <div class="quotation-info-label">
                                Payment Terms
                            </div>

                            <div class="quotation-info-value">

                                {{ $quotation->payment_terms ?: '-' }}

                            </div>

                        </div>


                        <a
                            href="{{ Storage::url(
                                $quotation->quotation_file_path
                            ) }}"
                            target="_blank"
                            rel="noopener"
                            class="pdf-button"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >

                                <path
                                    d="M7 3H14L19 8V21H7V3Z"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                />

                                <path
                                    d="M14 3V8H19"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                />

                                <path
                                    d="M9.5 15H16M9.5 18H14"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                />

                            </svg>

                            View Quotation PDF

                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>


{{-- =========================================================
     LOCAL ORDER & DELIVERY
========================================================= --}}

@php
    $localOrder    = $procurementRequest->localOrder;
    $deliveryOrder = $localOrder?->deliveryOrder;
@endphp

@if ($localOrder)

<div class="detail-card">

    <div class="detail-card-header">
        <div class="section-marker"></div>
        <div>
            <div class="detail-card-title">Local Order & Delivery</div>
            <div class="detail-card-subtitle">
                Order issued to the approved vendor and delivery confirmation.
            </div>
        </div>
    </div>

    <div class="detail-card-body">

        <div class="detail-grid">

            <div class="detail-field">
                <div class="detail-label">Approved Vendor</div>
                <div class="detail-value">
                    {{ optional(optional($procurementRequest->selectedQuotation)->vendor)->company_name ?? '-' }}
                </div>
            </div>

            <div class="detail-field">
                <div class="detail-label">Approved Amount</div>
                <div class="detail-value">
                    RM {{ number_format((float) $procurementRequest->approved_amount, 2) }}
                </div>
            </div>

            <div class="detail-field">
                <div class="detail-label">Local Order Number</div>
                <div class="detail-value">{{ $localOrder->lo_number }}</div>
            </div>

            <div class="detail-field">
                <div class="detail-label">Local Order Date</div>
                <div class="detail-value">
                    {{ $localOrder->lo_date ? $localOrder->lo_date->format('d M Y') : '-' }}
                </div>
            </div>

            @if ($deliveryOrder)

                <div class="detail-field">
                    <div class="detail-label">Delivery Order Number</div>
                    <div class="detail-value">{{ $deliveryOrder->do_number }}</div>
                </div>

                <div class="detail-field">
                    <div class="detail-label">Delivery Date</div>
                    <div class="detail-value">
                        {{ $deliveryOrder->delivery_date ? $deliveryOrder->delivery_date->format('d M Y') : '-' }}
                    </div>
                </div>

                <div class="detail-field">
                    <div class="detail-label">Vendor Rating</div>
                    <div class="detail-value">
                        <span class="rating-text">
                            {{ str_repeat('★', (int) $deliveryOrder->vendor_rating) }}{{ str_repeat('☆', 5 - (int) $deliveryOrder->vendor_rating) }}
                        </span>
                        ({{ $deliveryOrder->vendor_rating }}/5)
                    </div>
                </div>

                <div class="detail-field">
                    <div class="detail-label">Payment Status</div>
                    <div class="detail-value">
                        {{ $deliveryOrder->payment_status === 'Paid' ? 'Paid' : 'Processing by Finance' }}
                    </div>
                </div>

                @if ($deliveryOrder->delivery_comments)
                    <div class="detail-field full">
                        <div class="detail-label">Delivery Comments</div>
                        <div class="detail-description">{{ $deliveryOrder->delivery_comments }}</div>
                    </div>
                @endif

            @endif

        </div>

        <div class="doc-button-row">

            <a href="{{ route('ptj.orders.local-order', $localOrder->local_order_id) }}"
               target="_blank" rel="noopener" class="pdf-button">
                View Local Order PDF
            </a>

            @if ($deliveryOrder)

                <a href="{{ route('ptj.orders.delivery-order', $deliveryOrder->delivery_order_id) }}"
                   target="_blank" rel="noopener" class="pdf-button">
                    View Delivery Order PDF
                </a>

                <a href="{{ route('ptj.orders.invoice', $deliveryOrder->delivery_order_id) }}"
                   target="_blank" rel="noopener" class="pdf-button">
                    View Invoice PDF
                </a>

            @endif

        </div>

    </div>

</div>

@endif

@endsection