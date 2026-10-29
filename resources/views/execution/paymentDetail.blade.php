@extends('layouts.ucpms')

@section('title', 'Financial Review - UCPMS')


@section('page-style')

    /* =========================================================
       HEADER
    ========================================================= */

    .review-header {
        margin-bottom: 20px;

        display: flex;
        align-items: flex-start;
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

    .review-title {
        color: var(--navy);

        font-size: 28px;
        line-height: 1.2;

        letter-spacing: -0.4px;
    }

    .review-subtitle {
        margin-top: 6px;

        color: var(--muted);

        font-size: 12px;
    }

    .header-actions {
        display: flex;
        align-items: center;

        gap: 10px;
    }

    .back-button {
        min-height: 41px;

        padding: 0 14px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        border: 1px solid var(--border);
        border-radius: 10px;

        background: #ffffff;

        color: #59677e;

        font-size: 11px;
        font-weight: 700;
    }


    /* =========================================================
       STATUS STRIP
    ========================================================= */

    .status-strip {
        margin-bottom: 19px;

        padding: 13px 16px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 16px;

        border: 1px solid var(--border);
        border-radius: 13px;

        background: var(--surface);

        box-shadow: var(--shadow);
    }

    .status-group {
        display: flex;
        align-items: center;

        gap: 9px;
    }

    .status-label {
        color: var(--muted);

        font-size: 10px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 6px 10px;

        border-radius: 999px;

        font-size: 10px;
        font-weight: 700;
    }

    .status-pending {
        background: #fff7db;

        color: #a36a00;
    }

    .status-completed {
        background: var(--success-soft);

        color: var(--success);
    }

    .status-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: currentColor;
    }

    .status-paid {
        background: var(--success-soft);

        color: var(--success);
    }

    .payment-processed {
        padding: 13px;

        display: flex;
        align-items: flex-start;

        gap: 10px;

        border-radius: 10px;

        background: var(--success-soft);
    }

    .payment-processed-icon {
        width: 30px;
        height: 30px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #ffffff;

        color: var(--success);

        font-weight: 800;
    }

    .payment-processed-title {
        color: var(--success);

        font-size: 11px;
        font-weight: 750;
    }

    .payment-processed-text {
        margin-top: 4px;

        color: #587263;

        font-size: 9px;
        line-height: 1.5;
    }


    /* =========================================================
       FINANCIAL REVIEW LAYOUT
    ========================================================= */

    .review-workspace {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            330px;

        gap: 18px;

        align-items: start;
    }


    /* =========================================================
       MAIN FINANCIAL PANEL
    ========================================================= */

    .financial-panel {
        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 17px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .financial-summary {
        padding: 24px;

        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 18px;

        border-bottom: 1px solid var(--border);

        background:
            linear-gradient(
                135deg,
                #ffffff,
                var(--primary-soft)
            );
    }

    .amount-label {
        margin-bottom: 7px;

        color: var(--muted);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .amount-value {
        color: var(--primary);

        font-size: 34px;
        font-weight: 850;

        line-height: 1;
    }

    .amount-note {
        margin-top: 7px;

        color: var(--muted);

        font-size: 10px;
    }

    .request-reference {
        text-align: right;
    }

    .reference-label {
        color: var(--muted);

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;
    }

    .reference-value {
        margin-top: 5px;

        color: var(--navy);

        font-size: 13px;
        font-weight: 750;
    }


    /* =========================================================
       PROCUREMENT INFORMATION
    ========================================================= */

    .info-section {
        padding: 22px 24px;
    }

    .section-heading {
        margin-bottom: 14px;

        color: var(--navy);

        font-size: 13px;
        font-weight: 750;
    }

    .information-table {
        width: 100%;

        border-collapse: collapse;
    }

    .information-table td {
        padding: 11px 0;

        border-bottom: 1px solid var(--border);

        vertical-align: top;
    }

    .information-table tr:last-child td {
        border-bottom: none;
    }

    .information-label {
        width: 190px;

        color: var(--muted);

        font-size: 10px;
        font-weight: 650;
    }

    .information-value {
        color: var(--navy);

        font-size: 11px;
        font-weight: 700;

        line-height: 1.45;
    }


    /* =========================================================
       VERIFICATION DOCUMENTS
    ========================================================= */

    .documents-section {
        padding: 0 24px 24px;
    }

    .documents-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 12px;
    }

    .document-card {
        padding: 15px;

        border: 1px solid var(--border);
        border-radius: 12px;

        background: #fbfcfe;
    }

    .document-icon {
        width: 38px;
        height: 38px;

        margin-bottom: 11px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #fff0f0;

        color: #e34848;
    }

    .document-icon svg {
        width: 19px;
        height: 19px;
    }

    .document-title {
        color: var(--navy);

        font-size: 11px;
        font-weight: 750;
    }

    .document-reference {
        margin-top: 4px;

        color: var(--muted);

        font-size: 9px;
    }

    .document-button {
        margin-top: 13px;

        min-height: 34px;

        padding: 0 11px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 5px;

        border: 1px solid var(--border);
        border-radius: 8px;

        background: #ffffff;

        color: var(--navy);

        font-size: 9px;
        font-weight: 700;

        transition: 0.2s;
    }

    .document-button:hover {
        border-color: var(--primary);

        color: var(--primary);
    }

    .document-button svg {
        width: 14px;
        height: 14px;
    }


    /* =========================================================
       PAYMENT ACTION PANEL
    ========================================================= */

    .payment-action-panel {
        position: sticky;

        top: 110px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 17px;

        box-shadow: var(--shadow);

        overflow: hidden;
    }

    .action-panel-header {
        padding: 18px;

        border-bottom: 1px solid var(--border);
    }

    .action-panel-title {
        color: var(--navy);

        font-size: 13px;
        font-weight: 750;
    }

    .action-panel-description {
        margin-top: 5px;

        color: var(--muted);

        font-size: 10px;
        line-height: 1.5;
    }

    .action-panel-body {
        padding: 18px;
    }

    .payment-check {
        padding: 12px;

        display: flex;
        align-items: flex-start;

        gap: 10px;

        border-radius: 10px;

        background: var(--success-soft);
    }

    .payment-check-icon {
        width: 28px;
        height: 28px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #ffffff;

        color: var(--success);
    }

    .payment-check-icon svg {
        width: 15px;
        height: 15px;
    }

    .payment-check-title {
        color: var(--success);

        font-size: 10px;
        font-weight: 750;
    }

    .payment-check-text {
        margin-top: 3px;

        color: #587263;

        font-size: 9px;
        line-height: 1.45;
    }

    .payment-warning {
        margin-top: 14px;

        padding: 11px;

        border: 1px solid #f6df9d;
        border-radius: 9px;

        background: #fffaf0;

        color: #876719;

        font-size: 9px;
        line-height: 1.5;
    }

    .mark-paid-button {
        width: 100%;
        min-height: 44px;

        margin-top: 16px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        border: none;
        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );

        color: #ffffff;

        font-size: 11px;
        font-weight: 750;

        cursor: pointer;

        box-shadow: var(--shadow);

        transition: 0.2s;
    }

    .mark-paid-button:hover {
        transform: translateY(-1px);
    }

    .mark-paid-button svg {
        width: 16px;
        height: 16px;
    }


    /* =========================================================
       DELIVERY INFORMATION
    ========================================================= */

    .delivery-reference {
        margin-top: 15px;

        padding-top: 14px;

        border-top: 1px solid var(--border);
    }

    .delivery-row {
        display: flex;
        justify-content: space-between;

        gap: 10px;

        padding: 6px 0;

        font-size: 9px;
    }

    .delivery-row-label {
        color: var(--muted);
    }

    .delivery-row-value {
        color: var(--navy);

        font-weight: 700;

        text-align: right;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .review-workspace {
            grid-template-columns: 1fr;
        }

        .payment-action-panel {
            position: static;
        }
    }


    @media (max-width: 820px) {

        .documents-grid {
            grid-template-columns: 1fr;
        }

        .financial-summary {
            align-items: flex-start;

            flex-direction: column;
        }

        .request-reference {
            text-align: left;
        }
    }


    @media (max-width: 650px) {

        .review-header {
            flex-direction: column;
        }

        .amount-value {
            font-size: 28px;
        }

        .information-label {
            width: 135px;
        }
    }

@endsection



@section('content')


{{-- =========================================================
     HEADER
========================================================= --}}

<div class="review-header">

    <div>

        <div class="breadcrumb">

            <a href="{{ route('bendahari.dashboard') }}">
                Dashboard
            </a>

            &nbsp;›&nbsp;

            <a
            href="{{
                $deliveryOrder->payment_status === 'Paid'
                    ? route('bendahari.payments.history')
                    : route('bendahari.payments.index')
            }}"
        >
            {{
                $deliveryOrder->payment_status === 'Paid'
                    ? 'Payment History'
                    : 'Pending Payments'
            }}
        </a>

            &nbsp;›&nbsp;

            Financial Review

        </div>


        <h1 class="review-title">

            Financial Review:
            REQ-{{
                str_pad(
                    $procurementRequest
                        ->procurement_request_id,
                    4,
                    '0',
                    STR_PAD_LEFT
                )
            }}

        </h1>


        <div class="review-subtitle">

            @if ($deliveryOrder->payment_status === 'Paid')

                Review the completed payment record,
                delivery information and supporting documents.

            @else

                Review the completed delivery and supporting
                documents before recording the external payment
                as completed.

            @endif

        </div>

    </div>


    <div class="header-actions">

        <a
            href="{{
                $deliveryOrder->payment_status === 'Paid'
                    ? route('bendahari.payments.history')
                    : route('bendahari.payments.index')
            }}"
            class="back-button"
        >
            {{
                $deliveryOrder->payment_status === 'Paid'
                    ? '← Back to Payment History'
                    : '← Back to Payments'
            }}
        </a>

    </div>

</div>



{{-- =========================================================
     STATUS STRIP
========================================================= --}}

<div class="status-strip">

    <div class="status-group">

        <span class="status-label">
            Payment Status
        </span>

        @if ($deliveryOrder->payment_status === 'Paid')

            <span class="status-badge status-paid">

                <span class="status-dot"></span>

                Paid

            </span>

        @else

            <span class="status-badge status-pending">

                <span class="status-dot"></span>

                Pending

            </span>

        @endif

    </div>


    <div class="status-group">

        <span class="status-label">
            Procurement Status
        </span>

        <span class="status-badge status-completed">

            <span class="status-dot"></span>

            Completed

        </span>

    </div>

</div>



{{-- =========================================================
     WORKSPACE
========================================================= --}}

<div class="review-workspace">


    {{-- =====================================================
         MAIN REVIEW
    ====================================================== --}}

    <div class="financial-panel">


        <div class="financial-summary">

            <div>

                <div class="amount-label">
                    Approved Payment Amount
                </div>

                <div class="amount-value">

                    RM {{
                        number_format(
                            (float) $localOrder->total_amount,
                            2
                        )
                    }}

                </div>

                <div class="amount-note">
                    Final amount based on the approved Local Order
                </div>

            </div>


            <div class="request-reference">

                <div class="reference-label">
                    Local Order
                </div>

                <div class="reference-value">
                    {{ $localOrder->lo_number }}
                </div>

            </div>

        </div>



        {{-- PROCUREMENT / DELIVERY INFORMATION --}}

        <div class="info-section">

            <div class="section-heading">
                Procurement & Delivery Information
            </div>


            <table class="information-table">


                <tr>

                    <td class="information-label">
                        Vendor
                    </td>

                    <td class="information-value">
                        {{ $vendor->company_name }}
                    </td>

                </tr>


                <tr>

                    <td class="information-label">
                        PTJ Department
                    </td>

                    <td class="information-value">

                        {{
                            $procurementRequest
                                ->ptjDepartment
                                ->department_name
                            ?? '-'
                        }}

                    </td>

                </tr>


                <tr>

                    <td class="information-label">
                        Procurement Request
                    </td>

                    <td class="information-value">
                        {{ $procurementRequest->request_title }}
                    </td>

                </tr>


                <tr>

                    <td class="information-label">
                        Delivery Order
                    </td>

                    <td class="information-value">
                        {{ $deliveryOrder->do_number }}
                    </td>

                </tr>


                <tr>

                    <td class="information-label">
                        Actual Delivery Date
                    </td>

                    <td class="information-value">

                        {{
                            $deliveryOrder
                                ->delivery_date
                                ->format('d M Y')
                        }}

                    </td>

                </tr>


                <tr>

                    <td class="information-label">
                        Vendor Rating
                    </td>

                    <td class="information-value">

                        {{ $deliveryOrder->vendor_rating }}
                        / 5

                    </td>

                </tr>


                <tr>

                    <td class="information-label">
                        Delivery Comments
                    </td>

                    <td class="information-value">

                        {{
                            $deliveryOrder
                                ->delivery_comments
                            ?: 'No comments provided.'
                        }}

                    </td>

                </tr>


            </table>

        </div>



        {{-- DOCUMENTS --}}

        <div class="documents-section">

            <div class="section-heading">
                Verification Documents
            </div>


            <div class="documents-grid">


                {{-- LOCAL ORDER --}}
                <div class="document-card">

                    <div class="document-icon">

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
                            />

                        </svg>

                    </div>


                    <div class="document-title">
                        Local Order (LO)
                    </div>

                    <div class="document-reference">
                        {{ $localOrder->lo_number }}
                    </div>


                    <a
                        href="{{
                            route(
                                'bendahari.payments.local-order',
                                $deliveryOrder
                                    ->delivery_order_id
                            )
                        }}"
                        target="_blank"
                        class="document-button"
                    >
                        View Document
                    </a>

                </div>



                {{-- DELIVERY ORDER --}}
                <div class="document-card">

                    <div class="document-icon">

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
                            />

                        </svg>

                    </div>


                    <div class="document-title">
                        Delivery Order (DO)
                    </div>

                    <div class="document-reference">
                        {{ $deliveryOrder->do_number }}
                    </div>


                    <a
                        href="{{
                            route(
                                'bendahari.payments.delivery-order',
                                $deliveryOrder
                                    ->delivery_order_id
                            )
                        }}"
                        target="_blank"
                        class="document-button"
                    >
                        View Document
                    </a>

                </div>



                {{-- INVOICE --}}
                <div class="document-card">

                    <div class="document-icon">

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
                            />

                        </svg>

                    </div>


                    <div class="document-title">
                        Vendor Invoice
                    </div>

                    <div class="document-reference">
                        Uploaded by PTJ
                    </div>


                    <a
                        href="{{
                            route(
                                'bendahari.payments.invoice',
                                $deliveryOrder
                                    ->delivery_order_id
                            )
                        }}"
                        target="_blank"
                        class="document-button"
                    >
                        View Document
                    </a>

                </div>


            </div>

        </div>


    </div>



    {{-- =====================================================
         PAYMENT ACTION
    ====================================================== --}}

    <aside class="payment-action-panel">


        <div class="action-panel-header">

            <div class="action-panel-title">

                {{
                    $deliveryOrder->payment_status === 'Paid'
                        ? 'Payment Record'
                        : 'Payment Processing'
                }}

            </div>

            <div class="action-panel-description">

                @if ($deliveryOrder->payment_status === 'Paid')

                    Review the recorded payment outcome and
                    supporting delivery documentation.

                @else

                    Record completion of the university's
                    external payment process.

                @endif

            </div>
        </div>



        <div class="action-panel-body">


            <div class="payment-check">

                <div class="payment-check-icon">

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

                    <div class="payment-check-title">
                        Delivery Documentation Available
                    </div>

                    <div class="payment-check-text">

                        Local Order, Delivery Order and vendor
                        invoice are available for financial review.

                    </div>

                </div>

            </div>



            <div class="delivery-reference">

                <div class="delivery-row">

                    <span class="delivery-row-label">
                        Payment Status
                    </span>

                    <span class="delivery-row-value">
                        {{ $deliveryOrder->payment_status }}
                    </span>

                </div>


                <div class="delivery-row">

                    <span class="delivery-row-label">
                        Delivery Status
                    </span>

                    <span class="delivery-row-value">
                        {{ $deliveryOrder->delivery_order_status }}
                    </span>

                </div>


                <div class="delivery-row">

                    <span class="delivery-row-label">
                        LO Status
                    </span>

                    <span class="delivery-row-value">
                        {{ $localOrder->lo_status }}
                    </span>

                </div>

            </div>



            @if ($deliveryOrder->payment_status === 'Pending')

                <div class="payment-warning">

                    This action records the payment as completed
                    in UCPMS. It does not perform an actual bank
                    transfer or financial transaction.

                </div>

@endif


    @if ($deliveryOrder->payment_status === 'Pending')
            <form
                method="POST"
                action="{{
                    route(
                        'bendahari.payments.mark-paid',
                        $deliveryOrder
                            ->delivery_order_id
                    )
                }}"
                id="markPaidForm"
            >

                @csrf


                <button
                    type="submit"
                    class="mark-paid-button"
                    id="markPaidButton"
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

                    Simulate Payment — Mark as Paid

                </button>

            </form>

            @else

                <div class="payment-processed">

                    <div class="payment-processed-icon">
                        ✓
                    </div>

                    <div>

                        <div class="payment-processed-title">
                            Payment Processed
                        </div>

                        <div class="payment-processed-text">

                            This payment has already been recorded
                            as Paid.

                            <br>

                            Processed:
                            {{
                                $deliveryOrder
                                    ->updated_at
                                    ->format(
                                        'd M Y, h:i A'
                                    )
                            }}

                        </div>

                    </div>

                </div>

            @endif


        </div>


    </aside>


</div>

@endsection



@section('page-script')

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const form =
                document.getElementById(
                    'markPaidForm'
                );

            const button =
                document.getElementById(
                    'markPaidButton'
                );


            /*
            |--------------------------------------------------------------------------
            | Paid history records do not have a payment form
            |--------------------------------------------------------------------------
            */

            if (!form || !button) {
                return;
            }


            form.addEventListener(
                'submit',
                function () {

                    button.disabled = true;

                    button.textContent =
                        'Processing Payment...';

                }
            );

        }
    );

</script>

@endsection