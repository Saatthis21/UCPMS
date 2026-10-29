@extends('layouts.ucpms')

@section('title', 'Confirm Delivery - UCPMS')


@section('page-style')

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-heading {
        display: flex;
        align-items: center;
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

        transition: 0.2s;
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
       DELIVERY WORKFLOW BANNER
    ========================================================= */

    .delivery-banner {
        margin-bottom: 18px;

        padding: 15px 18px;

        display: flex;
        align-items: center;
        gap: 13px;

        border: 1px solid #cfe0fa;
        border-radius: 13px;

        background:
            linear-gradient(
                90deg,
                var(--primary-soft),
                #f7fbff
            );
    }

    .delivery-banner-icon {
        width: 42px;
        height: 42px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #ffffff;
        color: var(--primary);
    }

    .delivery-banner-icon svg {
        width: 21px;
        height: 21px;
    }

    .delivery-banner-title {
        color: var(--navy);

        font-size: 13px;
        font-weight: 750;
    }

    .delivery-banner-text {
        margin-top: 3px;

        color: #62718a;

        font-size: 11px;
        line-height: 1.5;
    }


    /* =========================================================
       ORDER REFERENCE STRIP
    ========================================================= */

    .order-reference-strip {
        margin-bottom: 20px;

        display: grid;

        grid-template-columns:
            0.8fr
            1.25fr
            1.6fr
            0.85fr;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 14px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .reference-item {
        min-width: 0;

        padding: 16px 17px;

        border-right: 1px solid var(--border);
    }

    .reference-item:last-child {
        border-right: none;
    }

    .reference-label {
        margin-bottom: 6px;

        color: var(--muted);

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .reference-value {
        color: var(--navy);

        font-size: 12px;
        font-weight: 700;

        line-height: 1.4;

        overflow-wrap: anywhere;
    }

    .reference-amount {
        color: var(--primary);

        font-size: 16px;
        font-weight: 800;
    }


    /* =========================================================
       MAIN PANEL
    ========================================================= */

    .delivery-panel {
        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 18px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .delivery-panel-header {
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

    .delivery-panel-body {
        padding: 23px;
    }


    /* =========================================================
       FORM SECTION
    ========================================================= */

    .form-section + .form-section {
        margin-top: 28px;

        padding-top: 27px;

        border-top: 1px solid var(--border);
    }

    .section-heading {
        margin-bottom: 17px;

        display: flex;
        align-items: flex-start;

        gap: 11px;
    }

    .section-number {
        width: 30px;
        height: 30px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: var(--primary-soft);

        color: var(--primary);

        font-size: 11px;
        font-weight: 800;
    }

    .section-title {
        color: var(--navy);

        font-size: 13px;
        font-weight: 750;
    }

    .section-description {
        margin-top: 3px;

        color: var(--muted);

        font-size: 11px;
        line-height: 1.45;
    }


    /* =========================================================
       FIELDS
    ========================================================= */

    .form-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 16px;
    }

    .form-group label {
        display: block;

        margin-bottom: 7px;

        color: #45536b;

        font-size: 11px;
        font-weight: 700;
    }

    .required {
        color: var(--danger);
    }

    .form-control {
        width: 100%;
        min-height: 43px;

        padding: 0 13px;

        border: 1px solid #dfe6ef;
        border-radius: 10px;

        outline: none;

        background: #ffffff;

        color: var(--text);

        font-size: 12px;

        transition: 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px
            rgba(23, 105, 232, 0.08);
    }

    textarea.form-control {
        min-height: 115px;

        padding: 12px 13px;

        resize: vertical;

        line-height: 1.5;
    }

    .field-help {
        margin-top: 6px;

        color: var(--muted);

        font-size: 10px;
        line-height: 1.4;
    }

    .field-error {
        margin-top: 6px;

        color: var(--danger);

        font-size: 10px;
        font-weight: 600;
    }


    /* =========================================================
       DOCUMENT UPLOAD
    ========================================================= */

    .upload-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 16px;
    }

    .upload-field {
        min-width: 0;
    }

    .upload-label {
        display: block;

        margin-bottom: 7px;

        color: #45536b;

        font-size: 11px;
        font-weight: 700;
    }

    .upload-zone {
        min-height: 155px;

        padding: 22px 18px;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        text-align: center;

        border: 1.5px dashed #b9cae3;
        border-radius: 13px;

        background: #fbfdff;

        cursor: pointer;

        transition: 0.2s;
    }

    .upload-zone:hover {
        border-color: var(--primary);

        background: var(--primary-soft);
    }

    .upload-icon {
        width: 43px;
        height: 43px;

        margin-bottom: 11px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .upload-icon svg {
        width: 22px;
        height: 22px;
    }

    .upload-title {
        color: var(--navy);

        font-size: 12px;
        font-weight: 700;
    }

    .upload-text {
        margin-top: 4px;

        color: var(--muted);

        font-size: 10px;
        line-height: 1.45;
    }

    .upload-file-name {
        margin-top: 9px;

        color: var(--primary);

        font-size: 10px;
        font-weight: 700;

        word-break: break-all;
    }

    .file-input {
        display: none;
    }


    /* =========================================================
       RATING
    ========================================================= */

    .rating-title {
        margin-bottom: 10px;

        color: #45536b;

        font-size: 11px;
        font-weight: 700;
    }

    .rating-options {
        display: grid;

        grid-template-columns:
            repeat(5, minmax(80px, 1fr));

        gap: 9px;
    }

    .rating-option input {
        position: absolute;

        opacity: 0;

        pointer-events: none;
    }

    .rating-option label {
        min-height: 54px;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        gap: 3px;

        border: 1px solid #dfe6ef;
        border-radius: 10px;

        background: #ffffff;

        color: #66758d;

        cursor: pointer;

        transition: 0.18s;
    }

    .rating-option label:hover {
        border-color: var(--primary);

        background: var(--primary-soft);
    }

    .rating-option input:checked + label {
        border-color: var(--primary);

        background: var(--primary-soft);

        color: var(--primary);

        box-shadow:
            inset 0 0 0 1px var(--primary);
    }

    .rating-star {
        font-size: 17px;
        line-height: 1;
    }

    .rating-number {
        font-size: 10px;
        font-weight: 750;
    }

    .rating-caption {
        margin-top: 8px;

        color: var(--muted);

        font-size: 10px;
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .form-actions {
        margin-top: 28px;

        padding-top: 20px;

        display: flex;
        justify-content: flex-end;
        align-items: center;

        gap: 10px;

        border-top: 1px solid var(--border);
    }

    .cancel-button,
    .submit-button,
    .view-lo-button {
        min-height: 43px;

        padding: 0 16px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        border-radius: 10px;

        font-size: 11px;
        font-weight: 700;

        transition: 0.2s;
    }

    .cancel-button {
        border: 1px solid #dfe6ef;

        background: #ffffff;

        color: #59677e;
    }

    .cancel-button:hover {
        border-color: #9ba8b8;
    }

    .view-lo-button {
        border: 1px solid var(--primary);

        background: var(--primary-soft);

        color: var(--primary);
    }

    .view-lo-button:hover {
        background: var(--primary);
        color: #ffffff;
    }

    .submit-button {
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
    }

    .submit-button:hover {
        transform: translateY(-1px);
    }

    .cancel-button svg,
    .view-lo-button svg,
    .submit-button svg {
        width: 17px;
        height: 17px;
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

    @media (max-width: 1000px) {

        .order-reference-strip {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .reference-item:nth-child(2) {
            border-right: none;
        }

        .reference-item:nth-child(1),
        .reference-item:nth-child(2) {
            border-bottom: 1px solid var(--border);
        }
    }


    @media (max-width: 760px) {

        .page-heading {
            flex-direction: column;
            align-items: stretch;
        }

        .form-grid,
        .upload-grid {
            grid-template-columns: 1fr;
        }

        .rating-options {
            grid-template-columns:
                repeat(5, minmax(55px, 1fr));
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-button,
        .view-lo-button,
        .submit-button {
            width: 100%;
        }
    }


    @media (max-width: 560px) {

        .order-reference-strip {
            grid-template-columns: 1fr;
        }

        .reference-item {
            border-right: none;
            border-bottom: 1px solid var(--border);
        }

        .reference-item:last-child {
            border-bottom: none;
        }

        .rating-options {
            grid-template-columns:
                repeat(5, 1fr);
        }

        .rating-option label {
            min-height: 49px;
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

            <a href="{{ route('ptj.orders.index') }}">
                Active Orders
            </a>

            &nbsp;›&nbsp;

            Confirm Delivery

        </div>


        <h1 class="page-title">
            Confirm Order Delivery
        </h1>


        <div class="page-description">

            Record the physical delivery, upload the vendor
            documents and submit the order for payment processing.

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
     VALIDATION ERRORS
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
     WORKFLOW BANNER
========================================================= --}}

<div class="delivery-banner">

    <div class="delivery-banner-icon">

        <svg viewBox="0 0 24 24" fill="none">

            <path
                d="M3 7H14V17H3V7Z"
                stroke="currentColor"
                stroke-width="1.8"
            />

            <path
                d="M14 10H18L21 13V17H14V10Z"
                stroke="currentColor"
                stroke-width="1.8"
            />

            <circle
                cx="7"
                cy="18"
                r="2"
                stroke="currentColor"
                stroke-width="1.8"
            />

            <circle
                cx="18"
                cy="18"
                r="2"
                stroke="currentColor"
                stroke-width="1.8"
            />

        </svg>

    </div>


    <div>

        <div class="delivery-banner-title">
            Local Order Generated — Awaiting Physical Delivery Confirmation
        </div>

        <div class="delivery-banner-text">

            Complete this form only after the vendor has physically
            delivered the goods and the Delivery Order and invoice
            have been received.

        </div>

    </div>

</div>



{{-- =========================================================
     ORDER REFERENCE
========================================================= --}}

<div class="order-reference-strip">


    <div class="reference-item">

        <div class="reference-label">
            Local Order
        </div>

        <div class="reference-value">
            {{ $localOrder->lo_number }}
        </div>

    </div>


    <div class="reference-item">

        <div class="reference-label">
            Winning Vendor
        </div>

        <div class="reference-value">
            {{ $vendor->company_name }}
        </div>

    </div>


    <div class="reference-item">

        <div class="reference-label">
            Procurement Request
        </div>

        <div class="reference-value">

            REQ-{{
                str_pad(
                    $procurementRequest->procurement_request_id,
                    4,
                    '0',
                    STR_PAD_LEFT
                )
            }}

            ·

            {{ $procurementRequest->request_title }}

        </div>

    </div>


    <div class="reference-item">

        <div class="reference-label">
            Approved Amount
        </div>

        <div class="reference-amount">

            RM {{
                number_format(
                    (float) $procurementRequest->approved_amount,
                    2
                )
            }}

        </div>

    </div>


</div>



{{-- =========================================================
     DELIVERY FORM
========================================================= --}}

<form
    method="POST"
    action="{{
        route(
            'ptj.orders.store-delivery',
            $localOrder->local_order_id
        )
    }}"
    enctype="multipart/form-data"
    id="deliveryForm"
>

    @csrf


    <div class="delivery-panel">


        <div class="delivery-panel-header">

            <div class="panel-title">
                Delivery Confirmation & Documents
            </div>

            <div class="panel-description">

                Match the vendor's physical Delivery Order and
                invoice to Local Order {{ $localOrder->lo_number }}.

            </div>

        </div>



        <div class="delivery-panel-body">


            {{-- =================================================
                 SECTION 1 - DELIVERY DETAILS
            ================================================== --}}

            <div class="form-section">

                <div class="section-heading">

                    <div class="section-number">
                        01
                    </div>


                    <div>

                        <div class="section-title">
                            Delivery Details
                        </div>

                        <div class="section-description">
                            Enter the actual physical delivery information.
                        </div>

                    </div>

                </div>


                <div class="form-grid">


                    <div class="form-group">

                        <label for="delivery_date">

                            Actual Delivery Date

                            <span class="required">*</span>

                        </label>


                        <input
                            type="date"
                            id="delivery_date"
                            name="delivery_date"
                            value="{{ old('delivery_date') }}"
                            max="{{ now()->toDateString() }}"
                            class="form-control"
                            required
                        >


                        @error('delivery_date')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    <div class="form-group">

                        <label for="do_number">

                            Delivery Order Number

                            <span class="required">*</span>

                        </label>


                        <input
                            type="text"
                            id="do_number"
                            name="do_number"
                            value="{{ old('do_number') }}"
                            class="form-control"
                            placeholder="e.g. DO-2026-00125"
                            maxlength="100"
                            required
                        >


                        <div class="field-help">
                            Enter the number printed on the vendor's Delivery Order.
                        </div>


                        @error('do_number')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>

            </div>



            {{-- =================================================
                 SECTION 2 - DOCUMENTS
            ================================================== --}}

            <div class="form-section">

                <div class="section-heading">

                    <div class="section-number">
                        02
                    </div>


                    <div>

                        <div class="section-title">
                            Delivery Documents
                        </div>

                        <div class="section-description">

                            Upload the received Delivery Order and
                            vendor invoice in PDF format.

                        </div>

                    </div>

                </div>


                <div class="upload-grid">


                    {{-- DO PDF --}}
                    <div class="upload-field">

                        <div class="upload-label">

                            Delivery Order (DO) PDF

                            <span class="required">*</span>

                        </div>


                        <label
                            for="do_pdf"
                            class="upload-zone"
                        >

                            <div class="upload-icon">

                                <svg viewBox="0 0 24 24" fill="none">

                                    <path
                                        d="M12 16V4M8 8L12 4L16 8"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />

                                    <path
                                        d="M5 14V19H19V14"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                </svg>

                            </div>


                            <div class="upload-title">
                                Upload Delivery Order
                            </div>


                            <div class="upload-text">
                                Click to select the vendor DO PDF<br>
                                PDF only · Maximum 5 MB
                            </div>


                            <div
                                id="doFileName"
                                class="upload-file-name"
                            >
                                No file selected
                            </div>

                        </label>


                        <input
                            type="file"
                            id="do_pdf"
                            name="do_pdf"
                            accept="application/pdf,.pdf"
                            class="file-input"
                            required
                        >


                        @error('do_pdf')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- INVOICE PDF --}}
                    <div class="upload-field">

                        <div class="upload-label">

                            Vendor Invoice PDF

                            <span class="required">*</span>

                        </div>


                        <label
                            for="invoice_pdf"
                            class="upload-zone"
                        >

                            <div class="upload-icon">

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

                            </div>


                            <div class="upload-title">
                                Upload Vendor Invoice
                            </div>


                            <div class="upload-text">
                                Click to select the invoice PDF<br>
                                PDF only · Maximum 5 MB
                            </div>


                            <div
                                id="invoiceFileName"
                                class="upload-file-name"
                            >
                                No file selected
                            </div>

                        </label>


                        <input
                            type="file"
                            id="invoice_pdf"
                            name="invoice_pdf"
                            accept="application/pdf,.pdf"
                            class="file-input"
                            required
                        >


                        @error('invoice_pdf')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>

            </div>



            {{-- =================================================
                 SECTION 3 - VENDOR PERFORMANCE
            ================================================== --}}

            <div class="form-section">

                <div class="section-heading">

                    <div class="section-number">
                        03
                    </div>


                    <div>

                        <div class="section-title">
                            Vendor Performance
                        </div>

                        <div class="section-description">

                            Rate the vendor based on this completed
                            delivery and add any useful delivery notes.

                        </div>

                    </div>

                </div>



                <div class="rating-title">

                    Vendor Rating

                    <span class="required">*</span>

                </div>


                <div class="rating-options">


                    @for ($rating = 1; $rating <= 5; $rating++)

                        <div class="rating-option">

                            <input
                                type="radio"
                                id="rating_{{ $rating }}"
                                name="vendor_rating"
                                value="{{ $rating }}"
                                {{
                                    (string) old('vendor_rating')
                                    === (string) $rating
                                        ? 'checked'
                                        : ''
                                }}
                                required
                            >


                            <label for="rating_{{ $rating }}">

                                <span class="rating-star">
                                    ★
                                </span>

                                <span class="rating-number">
                                    {{ $rating }} / 5
                                </span>

                            </label>

                        </div>

                    @endfor


                </div>


                <div class="rating-caption">
                    1 = Poor performance · 5 = Excellent performance
                </div>


                @error('vendor_rating')

                    <div class="field-error">
                        {{ $message }}
                    </div>

                @enderror



                <div
                    class="form-group"
                    style="margin-top: 20px;"
                >

                    <label for="delivery_comments">

                        Delivery Comments / Notes

                        <span
                            style="
                                color: var(--muted);
                                font-weight: 500;
                            "
                        >
                            (Optional)
                        </span>

                    </label>


                    <textarea
                        id="delivery_comments"
                        name="delivery_comments"
                        class="form-control"
                        placeholder="e.g. Items received in good condition and complete."
                    >{{ old('delivery_comments') }}</textarea>


                    @error('delivery_comments')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

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



                <a
                    href="{{
                        route(
                            'ptj.orders.local-order',
                            $localOrder->local_order_id
                        )
                    }}"
                    target="_blank"
                    class="view-lo-button"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M2 12C4.5 7.5 7.8 5.5 12 5.5C16.2 5.5 19.5 7.5 22 12C19.5 16.5 16.2 18.5 12 18.5C7.8 18.5 4.5 16.5 2 12Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="2.5"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                    </svg>

                    View Local Order

                </a>



                <button
                    type="submit"
                    class="submit-button"
                    id="submitDeliveryButton"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M5 12L10 17L19 7"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                    </svg>

                    Confirm Delivery & Submit

                </button>


            </div>


        </div>

    </div>

</form>

@endsection



@section('page-script')

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Display Selected DO File Name
            |--------------------------------------------------------------------------
            */

            const doInput =
                document.getElementById('do_pdf');

            const doFileName =
                document.getElementById('doFileName');


            doInput.addEventListener(
                'change',
                function () {

                    if (
                        this.files
                        && this.files.length > 0
                    ) {

                        doFileName.textContent =
                            this.files[0].name;

                    } else {

                        doFileName.textContent =
                            'No file selected';

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Display Selected Invoice File Name
            |--------------------------------------------------------------------------
            */

            const invoiceInput =
                document.getElementById('invoice_pdf');

            const invoiceFileName =
                document.getElementById('invoiceFileName');


            invoiceInput.addEventListener(
                'change',
                function () {

                    if (
                        this.files
                        && this.files.length > 0
                    ) {

                        invoiceFileName.textContent =
                            this.files[0].name;

                    } else {

                        invoiceFileName.textContent =
                            'No file selected';

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Prevent Accidental Double Submission
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById('deliveryForm');

            const button =
                document.getElementById(
                    'submitDeliveryButton'
                );


            form.addEventListener(
                'submit',
                function () {

                    button.disabled = true;

                    button.textContent =
                        'Submitting Delivery...';

                }
            );

        }
    );

</script>

@endsection