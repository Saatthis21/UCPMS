<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Local Order - {{ $loNumber }}
    </title>


    <style>

        @page {
            margin: 24px 28px;
        }


        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            font-family:
                DejaVu Sans,
                Arial,
                sans-serif;

            font-size: 10px;

            color: #26354d;

            background: #ffffff;
        }


        /* =========================================================
           GENERAL
        ========================================================= */

        .document {
            width: 100%;
        }


        .muted {
            color: #6f7d91;
        }


        .text-right {
            text-align: right;
        }


        .text-center {
            text-align: center;
        }


        .font-bold {
            font-weight: bold;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .header-table {
            width: 100%;

            border-collapse: collapse;

            margin-bottom: 10px;
        }


        .header-table td {
            vertical-align: middle;
        }


        .logo-cell {
            width: 80px;
        }


        .logo {
            width: 58px;
            height: auto;
        }


        .university-name {
            margin-bottom: 3px;

            color: #17233c;

            font-size: 13px;
            font-weight: bold;

            text-transform: uppercase;
        }


        .system-name {
            font-size: 8px;

            color: #687790;
        }


        .document-type {
            width: 180px;

            text-align: right;
        }


        .document-type-title {
            color: #1769e8;

            font-size: 18px;
            font-weight: bold;
        }


        .document-type-subtitle {
            margin-top: 3px;

            color: #718096;

            font-size: 8px;

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }


        .header-line {
            height: 3px;

            margin-bottom: 16px;

            background: #1769e8;
        }


        /* =========================================================
           LO IDENTIFICATION
        ========================================================= */

        .identification-table {
            width: 100%;

            margin-bottom: 16px;

            border-collapse: collapse;
        }


        .identification-table td {
            width: 25%;

            padding: 8px 10px;

            border: 1px solid #dde5ef;

            vertical-align: top;
        }


        .info-label {
            margin-bottom: 4px;

            color: #718096;

            font-size: 7px;
            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 0.35px;
        }


        .info-value {
            color: #17233c;

            font-size: 9px;
            font-weight: bold;
        }


        /* =========================================================
           PARTY INFORMATION
        ========================================================= */

        .party-table {
            width: 100%;

            margin-bottom: 16px;

            border-collapse: separate;
            border-spacing: 8px 0;
        }


        .party-table td {
            width: 50%;

            padding: 13px;

            border: 1px solid #dde5ef;

            vertical-align: top;

            background: #f8fafc;
        }


        .party-heading {
            margin-bottom: 8px;

            color: #1769e8;

            font-size: 8px;
            font-weight: bold;

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }


        .party-name {
            margin-bottom: 6px;

            color: #17233c;

            font-size: 11px;
            font-weight: bold;
        }


        .party-detail {
            margin-top: 4px;

            color: #59677e;

            font-size: 8px;

            line-height: 1.45;
        }


        /* =========================================================
           COMMERCIAL INFORMATION
        ========================================================= */

        .commercial-table {
            width: 100%;

            margin-bottom: 16px;

            border-collapse: collapse;
        }


        .commercial-table td {
            width: 33.33%;

            padding: 9px 10px;

            border: 1px solid #dde5ef;

            vertical-align: top;
        }


        /* =========================================================
           SECTION TITLE
        ========================================================= */

        .section-title {
            margin-top: 5px;
            margin-bottom: 7px;

            padding-bottom: 5px;

            border-bottom: 1px solid #ccd8e6;

            color: #17233c;

            font-size: 9px;
            font-weight: bold;

            text-transform: uppercase;
        }


        /* =========================================================
           ITEM TABLE
        ========================================================= */

        .item-table {
            width: 100%;

            border-collapse: collapse;

            margin-bottom: 12px;
        }


        .item-table thead th {
            padding: 8px 7px;

            border: 1px solid #225c91;

            background: #225c91;

            color: #ffffff;

            font-size: 7px;

            text-align: left;

            text-transform: uppercase;
        }


        .item-table tbody td {
            padding: 8px 7px;

            border:
                1px solid
                #dfe6ef;

            vertical-align: top;

            font-size: 8px;
        }


        .item-table tbody tr:nth-child(even) td {
            background: #f8fafc;
        }


        .item-index {
            width: 35px;

            text-align: center;
        }


        .item-quantity {
            width: 55px;

            text-align: center;
        }


        .item-money {
            width: 95px;

            text-align: right;
        }


        .item-name {
            color: #17233c;

            font-weight: bold;
        }


        .item-description {
            margin-top: 3px;

            color: #718096;

            font-size: 7px;

            line-height: 1.4;
        }


        /* =========================================================
           TOTAL
        ========================================================= */

        .total-wrapper {
            width: 100%;

            margin-bottom: 18px;
        }


        .total-table {
            width: 310px;

            margin-left: auto;

            border-collapse: collapse;
        }


        .total-table td {
            padding: 7px 9px;

            border:
                1px solid
                #dfe6ef;

            font-size: 8px;
        }


        .grand-total-label {
            background: #225c91;

            color: #ffffff;

            font-weight: bold;

            text-transform: uppercase;
        }


        .grand-total-value {
            background: #225c91;

            color: #ffffff;

            font-size: 11px !important;

            font-weight: bold;

            text-align: right;
        }


        /* =========================================================
           DECLARATION / FOOTER
        ========================================================= */

        .declaration {
            margin-top: 8px;

            padding: 10px 12px;

            border: 1px solid #dfe6ef;

            background: #f8fafc;

            color: #647187;

            font-size: 7px;

            line-height: 1.5;
        }


        .footer {
            margin-top: 18px;

            padding-top: 8px;

            border-top:
                1px solid
                #dfe6ef;

            color: #7a879b;

            font-size: 7px;
        }


        .footer-table {
            width: 100%;

            border-collapse: collapse;
        }


        .footer-table td {
            width: 50%;
        }


        /* =========================================================
           PAGE BREAK CONTROL
        ========================================================= */

        tr {
            page-break-inside: avoid;
        }


        .party-table,
        .commercial-table,
        .total-wrapper {
            page-break-inside: avoid;
        }

    </style>

</head>


<body>


<div class="document">


    {{-- =========================================================
         DOCUMENT HEADER
    ========================================================= --}}

    <table class="header-table">

        <tr>

            <td class="logo-cell">

                @php
                    $logoPath =
                        public_path(
                            'images/ucpms-logo.png'
                        );
                @endphp


                @if (file_exists($logoPath))

                    <img
                        src="{{ $logoPath }}"
                        alt="UCPMS"
                        class="logo"
                    >

                @endif

            </td>


            <td>

                <div class="university-name">
                    Universiti Malaysia Pahang Al-Sultan Abdullah
                </div>

                <div class="system-name">
                    University Centralized Procurement Management System (UCPMS)
                </div>

            </td>


            <td class="document-type">

                <div class="document-type-title">
                    LOCAL ORDER
                </div>

                <div class="document-type-subtitle">
                    Official Procurement Document
                </div>

            </td>

        </tr>

    </table>


    <div class="header-line"></div>



    {{-- =========================================================
         LO IDENTIFICATION
    ========================================================= --}}

    <table class="identification-table">

        <tr>

            <td>

                <div class="info-label">
                    LO Number
                </div>

                <div class="info-value">
                    {{ $loNumber }}
                </div>

            </td>


            <td>

                <div class="info-label">
                    LO Date
                </div>

                <div class="info-value">

                    {{
                        \Carbon\Carbon::parse(
                            $loDate
                        )->format('d M Y')
                    }}

                </div>

            </td>


            <td>

                <div class="info-label">
                    Procurement Request
                </div>

                <div class="info-value">

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

            </td>


            <td>

                <div class="info-label">
                    Quotation Reference
                </div>

                <div class="info-value">

                    {{
                        $selectedQuotation
                            ->quotation_reference_number
                        ?: '-'
                    }}

                </div>

            </td>

        </tr>

    </table>



    {{-- =========================================================
         VENDOR / PTJ
    ========================================================= --}}

    <table class="party-table">

        <tr>


            {{-- VENDOR --}}
            <td>

                <div class="party-heading">
                    To Vendor
                </div>


                <div class="party-name">
                    {{ $vendor->company_name }}
                </div>


                <div class="party-detail">

                    <strong>
                        SSM Number:
                    </strong>

                    {{
                        $vendor->ssm_number
                        ?: '-'
                    }}

                </div>


                <div class="party-detail">

                    <strong>
                        MOF Number:
                    </strong>

                    {{
                        $vendor->mof_number
                        ?: '-'
                    }}

                </div>

            </td>



            {{-- PTJ --}}
            <td>

                <div class="party-heading">
                    Pusat Tanggungjawab (PTJ)
                </div>


                <div class="party-name">

                    {{
                        $procurementRequest
                            ->ptjDepartment
                            ->department_name
                        ?? '-'
                    }}

                </div>


                <div class="party-detail">

                    <strong>
                        Department Code:
                    </strong>

                    {{
                        $procurementRequest
                            ->ptjDepartment
                            ->department_code
                        ?? '-'
                    }}

                </div>


                <div class="party-detail">

                    <strong>
                        Request Title:
                    </strong>

                    {{
                        $procurementRequest
                            ->request_title
                    }}

                </div>

            </td>

        </tr>

    </table>



    {{-- =========================================================
         WINNING QUOTATION TERMS
    ========================================================= --}}

    <table class="commercial-table">

        <tr>


            <td>

                <div class="info-label">
                    Quotation Date
                </div>

                <div class="info-value">

                    {{
                        $selectedQuotation
                            ->quotation_date
                            ? $selectedQuotation
                                ->quotation_date
                                ->format('d M Y')
                            : '-'
                    }}

                </div>

            </td>



            <td>

                <div class="info-label">
                    Delivery Lead Time
                </div>

                <div class="info-value">

                    {{
                        $selectedQuotation
                            ->delivery_lead_time
                        ?: '-'
                    }}

                </div>

            </td>



            <td>

                <div class="info-label">
                    Payment Terms
                </div>

                <div class="info-value">

                    {{
                        $selectedQuotation
                            ->payment_terms
                        ?: '-'
                    }}

                </div>

            </td>

        </tr>

    </table>



    {{-- =========================================================
         ORDER ITEMS
    ========================================================= --}}

    <div class="section-title">
        Order Items
    </div>


    <table class="item-table">

        <thead>

            <tr>

                <th class="item-index">
                    #
                </th>

                <th>
                    Item / Specification
                </th>

                <th class="item-quantity">
                    Qty
                </th>

                <th class="item-money">
                    Unit Price (RM)
                </th>

                <th class="item-money">
                    Line Total (RM)
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach ($loItems as $index => $item)

                <tr>


                    <td class="item-index">
                        {{ $index + 1 }}
                    </td>



                    <td>

                        <div class="item-name">
                            {{ $item['item_name'] }}
                        </div>


                        @if (
                            !empty(
                                $item['item_description']
                            )
                        )

                            <div class="item-description">
                                {{ $item['item_description'] }}
                            </div>

                        @endif

                    </td>



                    <td class="item-quantity">
                        {{ $item['quantity'] }}
                    </td>



                    <td class="item-money">

                        {{
                            number_format(
                                (float)
                                $item['unit_price'],
                                2
                            )
                        }}

                    </td>



                    <td class="item-money">

                        {{
                            number_format(
                                (float)
                                $item['line_total'],
                                2
                            )
                        }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>



    {{-- =========================================================
         TOTAL
    ========================================================= --}}

    <div class="total-wrapper">

        <table class="total-table">


            <tr>

                <td>
                    Approved / Itemized Total
                </td>

                <td class="text-right font-bold">

                    RM {{
                        number_format(
                            (float)
                            $totalAmount,
                            2
                        )
                    }}

                </td>

            </tr>


            <tr>

                <td class="grand-total-label">
                    Grand Total
                </td>

                <td class="grand-total-value">

                    RM {{
                        number_format(
                            (float)
                            $totalAmount,
                            2
                        )
                    }}

                </td>

            </tr>

        </table>

    </div>



    {{-- =========================================================
         DOCUMENT NOTE
    ========================================================= --}}

    <div class="declaration">

        This Local Order was generated electronically by UCPMS
        from a procurement request that received final Bendahari
        approval. The vendor and approved amount shown in this
        document are based on the approved winning quotation.

    </div>



    {{-- =========================================================
         FOOTER
    ========================================================= --}}

    <div class="footer">

        <table class="footer-table">

            <tr>

                <td>
                    Generated by UCPMS
                </td>

                <td class="text-right">
                    {{ $loNumber }}
                </td>

            </tr>

        </table>

    </div>


</div>


</body>

</html>