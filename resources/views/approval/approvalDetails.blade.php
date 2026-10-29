@extends('layouts.ucpms')

@section('title', 'Review Procurement Request - UCPMS')


@section('page-style')

<style>

    /* =========================================================
       HEADER
    ========================================================= */

    .review-header {
        margin-bottom: 24px;
        padding: 22px 24px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow: var(--shadow);

        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 20px;
    }

    .review-header-left {
        flex: 1;
        min-width: 0;
    }

    .review-header-right {
        flex-shrink: 0;

        display: flex;
        align-items: flex-start;
        justify-content: flex-end;
    }

    .review-header-title {
        margin: 0;

        font-size: 23px;
        font-weight: 800;
        letter-spacing: -0.4px;

        color: var(--navy);
    }

    .review-header-meta {
        margin-top: 8px;

        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 10px;
    }

    .request-number {
        font-size: 12px;
        font-weight: 800;

        color: var(--primary);
    }

    .back-button {
        min-height: 42px;

        padding: 0 15px;

        border: 1px solid var(--border);
        border-radius: 10px;

        background: #ffffff;

        color: #59677c;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        font-size: 12px;
        font-weight: 700;

        transition: 0.2s ease;
    }

    .back-button:hover {
        background: var(--primary-soft);
        color: var(--primary);

        border-color: var(--primary-soft-strong);
    }

    .back-button svg {
        width: 17px;
        height: 17px;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .status-badge {
        padding: 7px 11px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 800;

        line-height: 1;
        white-space: nowrap;
    }

    .status-pending {
        background: var(--warning-soft);
        color: #a66a00;
    }

    .status-approved {
        background: var(--success-soft);
        color: var(--success);
    }

    .status-rejected {
        background: var(--danger-soft);
        color: var(--danger);
    }

    .status-returned {
        background: #fff0e5;
        color: #c86613;
    }


    /* =========================================================
       MAIN GRID
    ========================================================= */

    .review-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 0.95fr)
            minmax(0, 1.35fr);

        gap: 22px;

        align-items: start;
    }

    .review-column {
        min-width: 0;

        display: flex;
        flex-direction: column;

        gap: 22px;
    }


    /* =========================================================
       COMMON CARD
    ========================================================= */

    .review-card {
        width: 100%;

        overflow: hidden;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow: var(--shadow);
    }

    .card-header {
        padding: 18px 20px;

        border-bottom: 1px solid var(--border);
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;

        color: var(--navy);
    }

    .card-description {
        margin-top: 4px;

        font-size: 12px;
        line-height: 1.55;

        color: var(--muted);
    }

    .card-body {
        padding: 20px;
    }


    /* =========================================================
       REQUEST INFORMATION
    ========================================================= */

    .info-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 18px;
    }

    .info-item {
        min-width: 0;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        margin-bottom: 5px;

        font-size: 11px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.3px;

        color: var(--muted);
    }

    .info-value {
        font-size: 13px;
        font-weight: 650;

        line-height: 1.5;

        color: var(--text);

        word-break: break-word;
    }

    .description-box {
        padding: 13px 14px;

        border: 1px solid var(--border);
        border-radius: 10px;

        background: #fafbfd;

        font-size: 13px;
        line-height: 1.65;

        color: #536177;

        word-break: break-word;
    }


    /* =========================================================
       REQUEST ITEMS
    ========================================================= */

    .items-table-wrapper {
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        min-width: 560px;

        border-collapse: collapse;
    }

    .items-table th {
        padding: 11px 12px;

        background: #f8fafc;

        border-bottom: 1px solid var(--border);

        text-align: left;

        font-size: 10px;
        font-weight: 800;

        color: #768398;

        text-transform: uppercase;
    }

    .items-table td {
        padding: 12px;

        border-bottom: 1px solid #edf1f6;

        font-size: 12px;

        color: var(--text);
    }

    .items-table tbody tr:last-child td {
        border-bottom: none;
    }

    .text-right {
        text-align: right !important;
    }

    .items-total {
        padding: 15px 20px;

        background: #fafbfd;

        border-top: 1px solid var(--border);

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;
    }

    .items-total-label {
        font-size: 12px;
        font-weight: 700;

        color: var(--muted);
    }

    .items-total-value {
        font-size: 15px;
        font-weight: 800;

        color: var(--navy);
    }


    /* =========================================================
       DECISION CARD
    ========================================================= */

    .decision-card .card-body {
        padding: 18px 20px;
    }

    .decision-notice {
        margin-bottom: 16px;

        padding: 12px 14px;

        border-radius: 10px;

        background: var(--primary-soft);

        color: #59677c;

        font-size: 12px;
        line-height: 1.6;
    }

    .decision-actions {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 10px;
    }

    .decision-actions.two-actions {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .decision-button {
        width: 100%;
        min-height: 45px;

        border: none;
        border-radius: 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        cursor: pointer;

        font-size: 12px;
        font-weight: 800;

        transition: 0.2s ease;
    }

    .decision-button svg {
        width: 17px;
        height: 17px;
    }

    .approve-button {
        background: var(--success);
        color: #ffffff;
    }

    .approve-button:hover {
        filter: brightness(0.93);
        transform: translateY(-1px);
    }

    .reject-button {
        background: var(--danger);
        color: #ffffff;
    }

    .reject-button:hover {
        filter: brightness(0.93);
        transform: translateY(-1px);
    }

    .return-button {
        background: #667085;
        color: #ffffff;
    }

    .return-button:hover {
        background: #525d6e;
        transform: translateY(-1px);
    }


    /* =========================================================
       QUOTATIONS
    ========================================================= */

    .quotation-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 14px;
    }

    .quotation-card {
        min-width: 0;
        height: 100%;

        position: relative;

        padding: 18px;

        border: 1px solid var(--border);
        border-radius: 14px;

        background: #ffffff;

        display: flex;
        flex-direction: column;

        transition: 0.2s ease;
    }

    .quotation-card:hover {
        border-color: var(--primary-soft-strong);

        box-shadow:
            0 8px 24px rgba(31, 41, 55, 0.07);
    }

    .quotation-card.selected {
        border: 2px solid var(--primary);

        background: var(--primary-soft);
    }

    .quotation-select {
        position: absolute;

        top: 15px;
        right: 15px;
    }

    .quotation-radio {
        width: 18px;
        height: 18px;

        accent-color: var(--primary);

        cursor: pointer;
    }

    .vendor-name {
        padding-right: 28px;

        font-size: 14px;
        font-weight: 800;
        line-height: 1.4;

        color: var(--navy);

        word-break: break-word;
    }

    .quotation-reference {
        margin-top: 5px;

        font-size: 10px;
        line-height: 1.4;

        color: var(--muted);

        word-break: break-word;
    }

    .quotation-price {
        margin: 15px 0;

        font-size: 19px;
        font-weight: 850;

        color: var(--primary);
    }

    .quotation-details {
        display: flex;
        flex-direction: column;

        gap: 0;
    }

    .quotation-detail-row {
        padding: 8px 0;

        border-bottom: 1px solid #edf1f6;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 12px;

        font-size: 11px;
        line-height: 1.4;
    }

    .quotation-detail-row:last-child {
        border-bottom: none;
    }

    .quotation-detail-label {
        flex: 1;

        color: var(--muted);
    }

    .quotation-detail-value {
        max-width: 55%;

        text-align: right;

        font-weight: 700;

        color: var(--text);

        word-break: break-word;
    }

    .quotation-actions {
        margin-top: auto;
        padding-top: 15px;

        border-top: 1px solid var(--border);
    }

    .pdf-button {
        width: 100%;
        min-height: 39px;

        padding: 0 12px;

        border: 1px solid var(--border);
        border-radius: 9px;

        background: #f7f9fc;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        font-size: 11px;
        font-weight: 750;

        color: #526177;

        transition: 0.2s ease;
    }

    .pdf-button:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .pdf-button svg {
        width: 16px;
        height: 16px;
    }

    .selected-label {
        margin-top: 12px;

        padding: 7px 10px;

        border-radius: 8px;

        background: var(--success-soft);

        color: var(--success);

        text-align: center;

        font-size: 10px;
        font-weight: 800;
    }


    /* =========================================================
       AI
    ========================================================= */

    .ai-state {
        padding: 19px;

        border: 1px dashed #d9e0ea;
        border-radius: 12px;

        background: #fafbfd;
    }

    .ai-state-title {
        display: flex;
        align-items: center;

        gap: 9px;

        font-size: 13px;
        font-weight: 800;

        color: var(--navy);
    }

    .ai-state-title svg {
        width: 20px;
        height: 20px;

        color: var(--primary);
    }

    .ai-state-description {
        margin-top: 8px;

        font-size: 12px;
        line-height: 1.6;

        color: var(--muted);
    }

    .ai-status-row {
        margin-bottom: 14px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 12px;
        flex-wrap: wrap;
    }

    .ai-status-label {
        font-size: 11px;
        font-weight: 700;

        color: var(--muted);
    }

    .ai-status-badge {
        padding: 7px 11px;

        border-radius: 999px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        font-size: 10px;
        font-weight: 800;

        line-height: 1;
    }

    .ai-status-pending {
        background: var(--warning-soft);
        color: #a66a00;
    }

    .ai-status-processing {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .ai-status-completed {
        background: var(--success-soft);
        color: var(--success);
    }

    .ai-status-failed {
        background: var(--danger-soft);
        color: var(--danger);
    }

    .ai-status-timeout {
        background: #fff0e5;
        color: #c86613;
    }


    .ai-recommendation-box {
        padding: 17px;

        border: 1px solid #b9d9f5;
        border-radius: 12px;

        background: #f1f8fe;
    }

    .ai-recommendation-heading {
        margin-bottom: 12px;

        font-size: 11px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.3px;

        color: #1976b9;
    }

    .ai-recommendation-vendor {
        font-size: 17px;
        font-weight: 850;

        color: #17324d;
    }

    .ai-recommendation-amount {
        margin-top: 4px;

        font-size: 13px;
        font-weight: 750;

        color: #1976b9;
    }

    .ai-recommendation-justification {
        margin-top: 14px;
        padding-top: 14px;

        border-top: 1px solid #cfe4f6;

        font-size: 12px;
        line-height: 1.7;

        color: #536177;
    }

    .ai-recommended-label {
        margin-top: 10px;

        padding: 7px 10px;

        border-radius: 8px;

        background: #e7f4fd;
        color: #1976b9;

        text-align: center;

        font-size: 10px;
        font-weight: 800;
    }

    .quotation-card.ai-recommended {
        border-color: #4b9bd3;
    }

    .ai-advisory-note {
        margin-top: 14px;

        padding: 11px 12px;

        border-radius: 9px;

        background: #ffffff;

        font-size: 11px;
        line-height: 1.6;

        color: var(--muted);
    }

    /* =========================================================
    AI RECOMMENDED QUOTATION
    ========================================================= */

    .quotation-card.ai-recommended {
        border-color: #4b9bd3;
    }

    .ai-recommended-label {
        margin-top: 10px;
        padding: 7px 10px;

        border-radius: 8px;

        background: #e7f4fd;
        color: #1976b9;

        text-align: center;

        font-size: 10px;
        font-weight: 800;
    }

    /* =========================================================
       HISTORY
    ========================================================= */

    .history-decision {
        padding: 17px;

        border: 1px solid var(--border);
        border-radius: 12px;

        background: #fafbfd;
    }

    .history-decision-row {
        margin-bottom: 13px;

        display: flex;
        justify-content: space-between;

        gap: 16px;
    }

    .history-decision-row:last-child {
        margin-bottom: 0;
    }

    .history-label {
        font-size: 11px;
        font-weight: 700;

        color: var(--muted);
    }

    .history-value {
        text-align: right;

        font-size: 12px;
        font-weight: 750;

        color: var(--text);
    }

    .history-remarks {
        margin-top: 14px;

        padding: 13px;

        border: 1px solid var(--border);
        border-radius: 9px;

        background: #ffffff;

        font-size: 12px;
        line-height: 1.6;

        color: #56647a;
    }


    /* =========================================================
       VALIDATION
    ========================================================= */

    .validation-error {
        margin-bottom: 20px;

        padding: 14px 16px;

        border: 1px solid rgba(220, 53, 69, 0.16);
        border-radius: 11px;

        background: var(--danger-soft);

        color: var(--danger);

        font-size: 12px;
        font-weight: 700;
    }


    /* =========================================================
       MODAL
    ========================================================= */

    .modal-backdrop {
        position: fixed;

        inset: 0;

        z-index: 5000;

        padding: 20px;

        background: rgba(15, 23, 42, 0.48);

        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-backdrop.open {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 520px;

        overflow: hidden;

        background: #ffffff;

        border-radius: 16px;

        box-shadow:
            0 20px 60px rgba(15, 23, 42, 0.22);
    }

    .modal-header {
        padding: 20px;

        border-bottom: 1px solid var(--border);
    }

    .modal-title {
        font-size: 17px;
        font-weight: 800;

        color: var(--navy);
    }

    .modal-description {
        margin-top: 5px;

        font-size: 12px;

        color: var(--muted);
    }

    .modal-body {
        padding: 20px;
    }

    .modal-label {
        margin-bottom: 7px;

        display: block;

        font-size: 12px;
        font-weight: 750;

        color: var(--text);
    }

    .modal-textarea {
        width: 100%;
        min-height: 130px;

        padding: 12px 13px;

        border: 1px solid #dce3ed;
        border-radius: 10px;

        resize: vertical;

        outline: none;

        font-size: 13px;

        color: var(--text);
    }

    .modal-textarea:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px var(--primary-soft);
    }

    .modal-footer {
        padding: 16px 20px;

        border-top: 1px solid var(--border);

        display: flex;
        justify-content: flex-end;

        gap: 9px;
    }

    .modal-button {
        min-height: 41px;

        padding: 0 15px;

        border: none;
        border-radius: 9px;

        cursor: pointer;

        font-size: 12px;
        font-weight: 800;
    }

    .modal-cancel {
        background: #f4f6f9;
        color: #667085;
    }

    .modal-reject {
        background: var(--danger);
        color: #ffffff;
    }

    .modal-return {
        background: #667085;
        color: #ffffff;
    }

    /* =========================================================
    HISTORY REMARK / REJECTION REASON
    ========================================================= */

    .history-reason-card {
        border-left: 4px solid var(--danger);
    }

    .history-reason-card.returned-reason {
        border-left-color: #d97706;
    }

    .history-reason-box {
        padding: 15px 16px;

        border: 1px solid var(--border);
        border-radius: 10px;

        background: #fafbfd;

        color: #536177;

        font-size: 13px;
        line-height: 1.7;
    }

    .history-reason-title {
        margin-bottom: 6px;

        font-size: 12px;
        font-weight: 800;

        color: var(--danger);
    }

    .returned-reason .history-reason-title {
        color: #c86613;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1250px) {

        .quotation-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 1100px) {

        .review-grid {
            grid-template-columns: 1fr;
        }

        .quotation-grid {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

    }

    @media (max-width: 800px) {

        .quotation-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 700px) {

        .review-header {
            flex-direction: column;
        }

        .review-header-right {
            width: 100%;
        }

        .back-button {
            width: 100%;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item.full-width {
            grid-column: auto;
        }

        .decision-actions,
        .decision-actions.two-actions {
            grid-template-columns: 1fr;
        }

        .quotation-grid {
            grid-template-columns: 1fr;
        }

    }

</style>

@endsection



@section('content')

@php

    $historyMode = $historyMode ?? false;

    $approvalRole = $approvalRole ?? 'kj';

    $isBendahari =
        $approvalRole === 'bendahari';


    $requestId = 'PR-' . str_pad(
        $procurementRequest->procurement_request_id,
        4,
        '0',
        STR_PAD_LEFT
    );


    $currentStatus =
        $procurementRequest->request_status;


    /*
    |--------------------------------------------------------------------------
    | Role-Based Routes
    |--------------------------------------------------------------------------
    */

    $backRoute = $historyMode
        ? (
            $isBendahari
                ? 'bendahari.approvals.history'
                : 'kj.approvals.history'
        )
        : (
            $isBendahari
                ? 'bendahari.approvals.index'
                : 'kj.approvals.index'
        );


    $approveRoute = $isBendahari
        ? 'bendahari.approvals.approve'
        : 'kj.approvals.approve';


    $rejectRoute = $isBendahari
        ? 'bendahari.approvals.reject'
        : 'kj.approvals.reject';


    /*
    |--------------------------------------------------------------------------
    | Base Status Style
    |--------------------------------------------------------------------------
    */

    $statusClass = match ($currentStatus) {

        'Approved_KJ',
        'Approved_Bendahari'
            => 'status-approved',

        'Rejected_KJ',
        'Rejected_Bendahari'
            => 'status-rejected',

        'Pending_Edit'
            => 'status-returned',

        default
            => 'status-pending',
    };


    /*
    |--------------------------------------------------------------------------
    | User-Friendly Status Display
    |--------------------------------------------------------------------------
    */

    $displayStatusLabel =
        str_replace('_', ' ', $currentStatus);

    $displayStatusClass =
        $statusClass;


    /*
    |--------------------------------------------------------------------------
    | Bendahari Pending Status
    |--------------------------------------------------------------------------
    |
    | Approved_KJ remains the internal database status.
    | On the Bendahari review interface it means the request
    | is waiting for final Bendahari approval.
    |--------------------------------------------------------------------------
    */

    if (
        !$historyMode
        &&
        $isBendahari
        &&
        $currentStatus === 'Approved_KJ'
    ) {

        $displayStatusLabel =
            'Pending Bendahari Approval';

        $displayStatusClass =
            'status-pending';
    }

@endphp



{{-- =========================================================
     HEADER
========================================================= --}}

<section class="review-header">

    <div class="review-header-left">

        <h1 class="review-header-title">

            {{ $historyMode
                ? 'Approval Details'
                : (
                    $isBendahari
                        ? 'Bendahari Review'
                        : 'Review Procurement Request'
                )
            }}

            — {{ $requestId }}

        </h1>


        <div class="review-header-meta">

            <span class="request-number">
                {{ $procurementRequest->request_title }}
            </span>


            <span class="status-badge {{ $displayStatusClass }}">
                {{ $displayStatusLabel }}
            </span>

        </div>

    </div>


    <div class="review-header-right">

        <a
            href="{{ route($backRoute) }}"
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


            {{ $historyMode
                ? 'Back to History'
                : 'Back to Pending List'
            }}

        </a>

    </div>

</section>



{{-- =========================================================
     VALIDATION
========================================================= --}}

@if ($errors->any())

    <div class="validation-error">
        {{ $errors->first() }}
    </div>

@endif



{{-- =========================================================
     APPROVE FORM
========================================================= --}}

@if (!$historyMode)

    <form
        id="approveForm"
        method="POST"
        action="{{ route(
            $approveRoute,
            $procurementRequest
        ) }}"
    >

        @csrf

@endif



<div class="review-grid">


    {{-- =====================================================
         LEFT COLUMN
    ====================================================== --}}

    <div class="review-column">


        {{-- REQUEST INFORMATION --}}

        <section class="review-card">

            <div class="card-header">

                <div class="card-title">
                    Request Information
                </div>

                <div class="card-description">
                    Procurement request submitted by PTJ staff.
                </div>

            </div>


            <div class="card-body">

                <div class="info-grid">


                    <div class="info-item">

                        <div class="info-label">
                            Requester
                        </div>

                        <div class="info-value">
                            {{ $procurementRequest->user->user_name ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Staff ID
                        </div>

                        <div class="info-value">
                            {{ $procurementRequest->user->staff_id ?? '-' }}
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            PTJ Department
                        </div>

                        <div class="info-value">

                            {{ $procurementRequest
                                ->ptjDepartment
                                ->department_name
                                ?? '-' }}

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-label">
                            Date Submitted
                        </div>

                        <div class="info-value">

                            {{ optional(
                                $procurementRequest->created_at
                            )->format('d M Y, h:i A') ?? '-' }}

                        </div>

                    </div>


                    <div class="info-item full-width">

                        <div class="info-label">
                            Request Description
                        </div>

                        <div class="description-box">

                            {{ $procurementRequest->request_description
                                ?: 'No description provided.' }}

                        </div>

                    </div>


                </div>

            </div>

        </section>



        {{-- REQUEST ITEMS --}}

        <section class="review-card">

            <div class="card-header">

                <div class="card-title">
                    Request Items
                </div>

                <div class="card-description">
                    Itemized procurement details submitted by PTJ.
                </div>

            </div>


            <div class="items-table-wrapper">

                <table class="items-table">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Item</th>

                            <th class="text-right">
                                Quantity
                            </th>

                            <th class="text-right">
                                Unit Price
                            </th>

                            <th class="text-right">
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($procurementRequest->items as $item)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $item->item_name }}
                                </td>

                                <td class="text-right">
                                    {{ $item->quantity }}
                                </td>

                                <td class="text-right">

                                    RM
                                    {{ number_format(
                                        (float) $item->unit_price,
                                        2
                                    ) }}

                                </td>

                                <td class="text-right">

                                    RM
                                    {{ number_format(
                                        (float) $item->total_price,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="items-total">

                <span class="items-total-label">
                    Estimated Amount
                </span>

                <span class="items-total-value">

                    RM
                    {{ number_format(
                        (float)
                        $procurementRequest->estimated_amount,
                        2
                    ) }}

                </span>

            </div>

        </section>



        {{-- DECISION --}}

        <section class="review-card decision-card">

            <div class="card-header">

                <div class="card-title">

                    @if ($historyMode)

                        {{ $isBendahari
                            ? 'Bendahari Decision Record'
                            : 'KJ Decision Record'
                        }}

                    @else

                        {{ $isBendahari
                            ? 'Submit Bendahari Decision'
                            : 'Submit KJ Decision'
                        }}

                    @endif

                </div>


                <div class="card-description">

                    @if ($historyMode)

                        Recorded decision made for this procurement request.

                    @elseif ($isBendahari)

                        Review the quotation selected by KJ and submit the final Bendahari decision.

                    @else

                        Select a vendor quotation before approving the request.

                    @endif

                </div>

            </div>


            <div class="card-body">

                {{-- =====================================================
                    HISTORY MODE - READ ONLY
                ====================================================== --}}

                @if ($historyMode)

                    <div class="history-decision">

                        <div class="history-decision-row">

                            <span class="history-label">
                                Decision
                            </span>

                            <span class="history-value">
                                {{ $approvalRecord->decision }}
                            </span>

                        </div>


                        <div class="history-decision-row">

                            <span class="history-label">
                                Approval Stage
                            </span>

                            <span class="history-value">
                                {{ $approvalRecord->approval_stage }}
                            </span>

                        </div>


                        <div class="history-decision-row">

                            <span class="history-label">
                                Decision Date
                            </span>

                            <span class="history-value">

                                {{ optional(
                                    $approvalRecord->created_at
                                )->format('d M Y, h:i A') ?? '-' }}

                            </span>

                        </div>

                    </div>


                {{-- =====================================================
                    ACTIVE APPROVAL MODE
                ====================================================== --}}

                @else

                    <div class="decision-notice">

                        @if ($isBendahari)

                            KJ has already selected the winning quotation.
                            Approving this request will provide final
                            Bendahari approval and confirm the procurement
                            for the next Active Order stage.

                        @else

                            Approving this request will use the selected
                            quotation as the confirmed procurement amount
                            and forward the request to Bendahari.

                        @endif

                    </div>


                    <div
                        class="
                            decision-actions
                            {{ $isBendahari ? 'two-actions' : '' }}
                        "
                    >

                        {{-- KJ ONLY: RETURN FOR EDIT --}}
                        @if (!$isBendahari)

                            <button
                                type="button"
                                class="decision-button return-button"
                                onclick="openReturnModal()"
                            >

                                <svg viewBox="0 0 24 24" fill="none">

                                    <path
                                        d="M9 7L4 12L9 17"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                    <path
                                        d="M5 12H14C17 12 19 14 19 17"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                </svg>

                                Return for Edit

                            </button>

                        @endif


                        {{-- REJECT --}}
                        <button
                            type="button"
                            class="decision-button reject-button"
                            onclick="openRejectModal()"
                        >

                            <svg viewBox="0 0 24 24" fill="none">

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M9 9L15 15M15 9L9 15"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                            </svg>

                            Reject

                        </button>


                        {{-- APPROVE --}}
                        <button
                            type="submit"
                            class="decision-button approve-button"
                            onclick="return confirmApproval(event)"
                        >

                            <svg viewBox="0 0 24 24" fill="none">

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M8 12L11 15L16 9"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>


                            {{ $isBendahari
                                ? 'Approve Request'
                                : 'Approve Selected'
                            }}

                        </button>

                    </div>

                @endif

            </div>

        </section>


    </div>



    {{-- =====================================================
         RIGHT COLUMN
    ====================================================== --}}

    <div class="review-column">


        {{-- VENDOR QUOTATIONS --}}

        <section class="review-card">

            <div class="card-header">

                <div class="card-title">
                    Vendor Quotation Comparison
                </div>


                <div class="card-description">

                    @if ($historyMode)

                        Quotations submitted for this procurement request.

                    @elseif ($isBendahari)

                        Review all submitted quotations.
                        The quotation selected by KJ is highlighted.

                    @else

                        Review the quotations and select one vendor before approval.

                    @endif

                </div>

            </div>


            <div class="card-body">

                <div class="quotation-grid">


                    @forelse (
                        $procurementRequest->quotations
                        as $quotation
                    )


                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | KJ / Bendahari Selected Quotation
                            |--------------------------------------------------------------------------
                            */

                            $isSelected =
                                (int)
                                $procurementRequest
                                    ->selected_quotation_id
                                ===
                                (int)
                                $quotation->quotation_id;


                            /*
                            |--------------------------------------------------------------------------
                            | AI Recommended Quotation
                            |--------------------------------------------------------------------------
                            |
                            | This ONLY highlights the AI recommendation visually.
                            |
                            | It does NOT:
                            | - select the KJ radio button
                            | - update selected_quotation_id
                            | - approve the request
                            |
                            */

                            $aiRecommendation =
                                $procurementRequest->aiRecommendation;


                            $isAiRecommended =
                                $aiRecommendation
                                &&
                                $aiRecommendation->api_status === 'Completed'
                                &&
                                $aiRecommendation->recommended_quotation_id !== null
                                &&
                                (int) $aiRecommendation->recommended_quotation_id
                                    === (int) $quotation->quotation_id;

                        @endphp


                        <div
                            id="quotation-card-{{ $quotation->quotation_id }}"
                            class="
                                quotation-card
                                {{ $isSelected ? 'selected' : '' }}
                                {{ $isAiRecommended ? 'ai-recommended' : '' }}
                            "
                        >


                            @if (
                                !$historyMode
                                &&
                                !$isBendahari
                            )

                                <div class="quotation-select">

                                    <input
                                        type="radio"
                                        name="selected_quotation_id"
                                        value="{{ $quotation->quotation_id }}"
                                        class="quotation-radio"
                                        id="quotation-{{ $quotation->quotation_id }}"
                                        {{ old('selected_quotation_id')
                                            == $quotation->quotation_id
                                                ? 'checked'
                                                : ''
                                        }}
                                        onchange="selectQuotation(
                                            {{ $quotation->quotation_id }}
                                        )"
                                    >

                                </div>

                            @endif


                            <div class="vendor-name">

                                {{ $quotation
                                    ->vendor
                                    ->company_name
                                    ?? 'Vendor'
                                }}

                            </div>


                            <div class="quotation-reference">

                                Ref:
                                {{ $quotation
                                    ->quotation_reference_number
                                    ?? '-'
                                }}

                            </div>


                            <div class="quotation-price">

                                RM
                                {{ number_format(
                                    (float)
                                    $quotation->total_amount,
                                    2
                                ) }}

                            </div>


                            <div class="quotation-details">


                                <div class="quotation-detail-row">

                                    <span class="quotation-detail-label">
                                        Quotation Date
                                    </span>

                                    <span class="quotation-detail-value">

                                        {{ optional(
                                            $quotation->quotation_date
                                        )->format('d M Y') ?? '-' }}

                                    </span>

                                </div>


                                <div class="quotation-detail-row">

                                    <span class="quotation-detail-label">
                                        Warranty
                                    </span>

                                    <span class="quotation-detail-value">

                                        {{ $quotation->warranty_period !== null
                                            ? $quotation->warranty_period . ' months'
                                            : '-'
                                        }}

                                    </span>

                                </div>


                                <div class="quotation-detail-row">

                                    <span class="quotation-detail-label">
                                        Delivery
                                    </span>

                                    <span class="quotation-detail-value">

                                        {{ $quotation->delivery_lead_time !== null
                                            ? $quotation->delivery_lead_time . ' days'
                                            : '-'
                                        }}

                                    </span>

                                </div>


                                <div class="quotation-detail-row">

                                    <span class="quotation-detail-label">
                                        Payment Terms
                                    </span>

                                    <span class="quotation-detail-value">

                                        {{ $quotation->payment_terms ?: '-' }}

                                    </span>

                                </div>


                            </div>


                            <div class="quotation-actions">

                                <a
                                    href="{{ asset(
                                        'storage/'
                                        .
                                        $quotation->quotation_file_path
                                    ) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="pdf-button"
                                >

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <path
                                            d="M7 3H14L19 8V21H7V3Z"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            d="M14 3V8H19"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                        <path
                                            d="M9 14H17M9 17H15"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        />

                                    </svg>

                                    View Quotation PDF

                                </a>

                            </div>

                            @if ($isAiRecommended)

                                <div class="ai-recommended-label">
                                    AI Recommended Best Value
                                </div>

                            @endif


                            @if ($isSelected)

                                <div class="selected-label">

                                    {{ $isBendahari
                                        ? 'Selected & Approved by KJ'
                                        : 'Selected Quotation'
                                    }}

                                </div>

                            @endif


                        </div>


                    @empty


                        <div class="ai-state">

                            <div class="ai-state-title">
                                No quotations found
                            </div>

                            <div class="ai-state-description">
                                This request does not contain vendor quotations.
                            </div>

                        </div>


                    @endforelse


                </div>

            </div>

        </section>



        {{-- =========================================================
            AI EVALUATION
        ========================================================= --}}

        <section class="review-card">

            <div class="card-header">

                <div class="card-title">
                    AI Quotation Evaluation
                </div>

                <div class="card-description">
                    AI-assisted Best Value for Money recommendation
                    to support the approval review.
                </div>

            </div>


            <div class="card-body">

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | Current AI Evaluation
                    |--------------------------------------------------------------------------
                    */

                    $aiRecommendation =
                        $procurementRequest->aiRecommendation;


                    /*
                    |--------------------------------------------------------------------------
                    | Resolve Recommended Quotation Locally
                    |--------------------------------------------------------------------------
                    |
                    | Gemini only receives anonymous Vendor A / B / C...
                    |
                    | recommended_quotation_id is mapped back to the actual
                    | quotation inside UCPMS. Therefore, the real vendor name
                    | shown below never needs to be sent to Gemini.
                    |
                    */

                    $recommendedQuotation = null;


                    if (
                        $aiRecommendation
                        &&
                        $aiRecommendation->recommended_quotation_id
                    ) {

                        $recommendedQuotation =
                            $procurementRequest
                                ->quotations
                                ->firstWhere(
                                    'quotation_id',
                                    $aiRecommendation
                                        ->recommended_quotation_id
                                );
                    }


                    $aiStatus =
                        $aiRecommendation?->api_status;

                    
                    /*
                    |--------------------------------------------------------------------------
                    | Convert Anonymous AI Vendor Codes to Local Vendor Names
                    |--------------------------------------------------------------------------
                    |
                    | Gemini receives:
                    | Vendor A, Vendor B, Vendor C...
                    |
                    | The KJ should see:
                    | Actual vendor company names.
                    |
                    | The mapping follows the SAME quotation_id order used by
                    | AiEvaluationService when anonymising vendors.
                    |
                    */

                    $displayJustification =
                        $aiRecommendation?->justification_text;


                    /*
                    | Convert index:
                    | 0 -> A
                    | 1 -> B
                    | ...
                    | 25 -> Z
                    | 26 -> AA
                    */
                    $indexToLetters = function (int $index): string {

                        $letters = '';

                        $index++;

                        while ($index > 0) {

                            $index--;

                            $letters =
                                chr(65 + ($index % 26))
                                . $letters;

                            $index =
                                intdiv($index, 26);
                        }

                        return $letters;
                    };


                    /*
                    | Build the exact same anonymous mapping used by Gemini.
                    */
                    $vendorDisplayMap = [];

                    $quotationsForAiMapping =
                        $procurementRequest
                            ->quotations
                            ->sortBy('quotation_id')
                            ->values();


                    foreach (
                        $quotationsForAiMapping
                        as $index => $quotation
                    ) {

                        $vendorCode =
                            'Vendor '
                            . $indexToLetters($index);

                        $vendorDisplayMap[$vendorCode] =
                            $quotation->vendor?->company_name
                            ?? $vendorCode;
                    }


                    /*
                    | Replace only the user-facing copy.
                    |
                    | Example:
                    | Vendor C -> Smart Vision Enterprise
                    |
                    | Raw database justification remains unchanged.
                    */
                    if ($displayJustification) {

                        $displayJustification =
                            preg_replace_callback(
                                '/\bVendor [A-Z]+\b/',
                                function ($matches) use ($vendorDisplayMap) {

                                    return $vendorDisplayMap[$matches[0]]
                                        ?? $matches[0];
                                },
                                $displayJustification
                            );
                    }

                @endphp



                {{-- =====================================================
                    NO AI RECORD
                ====================================================== --}}

                @if (!$aiRecommendation)

                    <div class="ai-state">

                        <div class="ai-state-title">

                            <svg viewBox="0 0 24 24" fill="none">

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M12 8V12M12 16H12.01"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />

                            </svg>

                            AI Evaluation Not Available

                        </div>


                        <div class="ai-state-description">

                            No AI evaluation record is currently available
                            for this procurement request.

                            Manual quotation review and approval remain available.

                        </div>

                    </div>



                {{-- =====================================================
                    PENDING
                ====================================================== --}}

                @elseif ($aiStatus === 'Pending')

                    <div class="ai-status-row">

                        <span class="ai-status-label">
                            Evaluation Status
                        </span>

                        <span class="ai-status-badge ai-status-pending">
                            Pending Evaluation
                        </span>

                    </div>


                    <div class="ai-state">

                        <div class="ai-state-title">
                            AI Evaluation Pending
                        </div>

                        <div class="ai-state-description">

                            The quotation evaluation has been queued
                            and is waiting to be processed.

                            You may continue reviewing the quotations manually
                            or refresh this page later to view the recommendation.

                        </div>

                    </div>



                {{-- =====================================================
                    PROCESSING
                ====================================================== --}}

                @elseif ($aiStatus === 'Processing')

                    <div class="ai-status-row">

                        <span class="ai-status-label">
                            Evaluation Status
                        </span>

                        <span class="ai-status-badge ai-status-processing">
                            AI Evaluation in Progress
                        </span>

                    </div>


                    <div class="ai-state">

                        <div class="ai-state-title">
                            Evaluating Quotations
                        </div>

                        <div class="ai-state-description">

                            UCPMS is currently evaluating the submitted
                            quotations using the AI-assisted Best Value process.

                            Manual review remains available while the
                            evaluation is processing.

                        </div>

                    </div>



                {{-- =====================================================
                    COMPLETED
                ====================================================== --}}

                @elseif ($aiStatus === 'Completed')

                    <div class="ai-status-row">

                        <span class="ai-status-label">
                            Evaluation Status
                        </span>

                        <span class="ai-status-badge ai-status-completed">
                            Evaluation Completed
                        </span>

                    </div>


                    @if ($recommendedQuotation)

                        <div class="ai-recommendation-box">

                            <div class="ai-recommendation-heading">
                                Recommended Best Value Quotation
                            </div>


                            <div class="ai-recommendation-vendor">

                                {{ $recommendedQuotation
                                    ->vendor
                                    ->company_name
                                    ?? 'Vendor'
                                }}

                            </div>


                            <div class="ai-recommendation-amount">

                                RM
                                {{ number_format(
                                    (float)
                                    $recommendedQuotation->total_amount,
                                    2
                                ) }}

                            </div>


                            <div class="ai-recommendation-justification">

                                {{ $displayJustification
                                    ?: 'No recommendation explanation was provided.'
                                }}

                            </div>


                            <div class="ai-advisory-note">

                                This recommendation is advisory only.
                                The Ketua Jabatan remains responsible for
                                reviewing all quotation information and making
                                the final procurement decision.

                            </div>

                        </div>


                    @else

                        <div class="ai-state">

                            <div class="ai-state-title">
                                Recommendation Record Unavailable
                            </div>

                            <div class="ai-state-description">

                                The AI evaluation is marked as completed,
                                but the recommended quotation could not
                                be matched to the current quotation records.

                                Please continue with manual review.

                            </div>

                        </div>

                    @endif



                {{-- =====================================================
                    FAILED
                ====================================================== --}}

                @elseif ($aiStatus === 'Failed')

                    <div class="ai-status-row">

                        <span class="ai-status-label">
                            Evaluation Status
                        </span>

                        <span class="ai-status-badge ai-status-failed">
                            AI Evaluation Failed
                        </span>

                    </div>


                    <div class="ai-state">

                        <div class="ai-state-title">
                            AI Recommendation Unavailable
                        </div>

                        <div class="ai-state-description">

                            The AI evaluation could not be completed.

                            This does not block the approval process.
                            Please review all submitted quotations manually
                            and proceed with your decision.

                        </div>

                    </div>



                {{-- =====================================================
                    TIMEOUT
                ====================================================== --}}

                @elseif ($aiStatus === 'Timeout')

                    <div class="ai-status-row">

                        <span class="ai-status-label">
                            Evaluation Status
                        </span>

                        <span class="ai-status-badge ai-status-timeout">
                            AI Evaluation Timeout
                        </span>

                    </div>


                    <div class="ai-state">

                        <div class="ai-state-title">
                            AI Evaluation Timed Out
                        </div>

                        <div class="ai-state-description">

                            The external AI service did not respond within
                            the configured evaluation time.

                            Manual quotation review remains fully available
                            and the approval process can continue normally.

                        </div>

                    </div>



                {{-- =====================================================
                    UNKNOWN STATUS SAFEGUARD
                ====================================================== --}}

                @else

                    <div class="ai-state">

                        <div class="ai-state-title">
                            AI Evaluation Status Unavailable
                        </div>

                        <div class="ai-state-description">

                            The current AI evaluation status could not
                            be displayed.

                            Please continue with manual quotation review.

                        </div>

                    </div>

                @endif

            </div>

        </section>

        {{-- =================================================
                HISTORY REJECTION / RETURN REASON
            ================================================== --}}

            @if (
                $historyMode
                &&
                !empty($approvalRecord->remarks)
                &&
                in_array(
                    $approvalRecord->decision,
                    ['Rejected', 'Returned'],
                    true
                )
            )

                @php

                    $isReturnedDecision =
                        $approvalRecord->decision === 'Returned';

                @endphp


                <section
                    class="
                        review-card
                        history-reason-card
                        {{ $isReturnedDecision ? 'returned-reason' : '' }}
                    "
                >

                    <div class="card-header">

                        <div class="card-title">

                            {{ $isReturnedDecision
                                ? 'Required Amendment'
                                : 'Rejection Reason'
                            }}

                        </div>


                        <div class="card-description">

                            @if ($isReturnedDecision)

                                Changes requested from the PTJ staff before
                                this procurement request can be resubmitted.

                            @else

                                Reason provided when this procurement request
                                was rejected at the
                                {{ $approvalRecord->approval_stage }} approval stage.

                            @endif

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="history-reason-box">

                            <div class="history-reason-title">

                                {{ $isReturnedDecision
                                    ? 'Amendment Required'
                                    : 'Reason for Rejection'
                                }}

                            </div>


                            {{ $approvalRecord->remarks }}

                        </div>

                    </div>

                </section>

            @endif


    </div>


</div>



@if (!$historyMode)

    </form>

@endif



{{-- =========================================================
     MODALS
========================================================= --}}

@if (!$historyMode)


    {{-- REJECT MODAL --}}

    <div
        class="modal-backdrop"
        id="rejectModal"
    >

        <div class="modal-box">

            <div class="modal-header">

                <div class="modal-title">
                    Reject Procurement Request
                </div>

                <div class="modal-description">
                    Please provide the reason for rejecting this request.
                </div>

            </div>


            <form
                method="POST"
                action="{{ route(
                    $rejectRoute,
                    $procurementRequest
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <label
                        for="rejectRemarks"
                        class="modal-label"
                    >
                        Rejection Reason
                    </label>


                    <textarea
                        id="rejectRemarks"
                        name="remarks"
                        class="modal-textarea"
                        maxlength="2000"
                        required
                        placeholder="Enter the reason for rejection..."
                    ></textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="modal-button modal-cancel"
                        onclick="closeRejectModal()"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="modal-button modal-reject"
                    >
                        Confirm Reject
                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- RETURN FOR EDIT MODAL - KJ ONLY --}}

    @if (!$isBendahari)

        <div
            class="modal-backdrop"
            id="returnModal"
        >

            <div class="modal-box">

                <div class="modal-header">

                    <div class="modal-title">
                        Return Request for Amendment
                    </div>

                    <div class="modal-description">
                        Describe the changes required from the PTJ staff.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'kj.approvals.return-edit',
                        $procurementRequest
                    ) }}"
                >

                    @csrf


                    <div class="modal-body">

                        <label
                            for="returnRemarks"
                            class="modal-label"
                        >
                            Required Amendment
                        </label>


                        <textarea
                            id="returnRemarks"
                            name="remarks"
                            class="modal-textarea"
                            maxlength="2000"
                            required
                            placeholder="Enter the corrections or amendments required..."
                        ></textarea>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="modal-button modal-cancel"
                            onclick="closeReturnModal()"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="modal-button modal-return"
                        >
                            Return to PTJ
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif


@endif


@endsection



@section('page-script')

@if (!$historyMode)

<script>

    function selectQuotation(quotationId) {

        document
            .querySelectorAll('.quotation-card')
            .forEach(function (card) {

                card.classList.remove('selected');

            });


        const selectedCard =
            document.getElementById(
                'quotation-card-' + quotationId
            );


        if (selectedCard) {

            selectedCard.classList.add('selected');

        }

    }


    function confirmApproval(event) {

        const isBendahari =
            @json($isBendahari);


        if (!isBendahari) {

            const selectedQuotation =
                document.querySelector(
                    'input[name="selected_quotation_id"]:checked'
                );


            if (!selectedQuotation) {

                event.preventDefault();

                alert(
                    'Please select a vendor quotation before approving.'
                );

                return false;

            }


            return confirm(
                'Approve this procurement request using the selected vendor quotation?'
            );

        }


        return confirm(
            'Give final Bendahari approval for this procurement request?'
        );

    }


    function openRejectModal() {

        document
            .getElementById('rejectModal')
            ?.classList
            .add('open');

    }


    function closeRejectModal() {

        document
            .getElementById('rejectModal')
            ?.classList
            .remove('open');

    }


    function openReturnModal() {

        document
            .getElementById('returnModal')
            ?.classList
            .add('open');

    }


    function closeReturnModal() {

        document
            .getElementById('returnModal')
            ?.classList
            .remove('open');

    }


    document
        .getElementById('rejectModal')
        ?.addEventListener(
            'click',
            function (event) {

                if (event.target === this) {

                    closeRejectModal();

                }

            }
        );


    document
        .getElementById('returnModal')
        ?.addEventListener(
            'click',
            function (event) {

                if (event.target === this) {

                    closeReturnModal();

                }

            }
        );


    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                closeRejectModal();
                closeReturnModal();

            }

        }
    );

</script>

@endif

@endsection