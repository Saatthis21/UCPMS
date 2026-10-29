@extends('layouts.ucpms')

@section('title', 'Initiate New Procurement Request - UCPMS')


@section('page-style')

    .page-heading {
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
        margin: 0;
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
       MAIN FORM
    ========================================================= */

    .procurement-form-card {
        overflow: hidden;

        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;

        box-shadow: var(--shadow);
    }

    .form-section {
        padding: 23px 24px;
        border-bottom: 1px solid var(--border);
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;

        margin-bottom: 18px;
    }

    .section-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-marker {
        width: 4px;
        height: 22px;

        border-radius: 999px;

        background: linear-gradient(
            180deg,
            var(--primary),
            var(--secondary)
        );
    }

    .section-title {
        color: var(--navy);
        font-size: 16px;
        font-weight: 750;
    }

    .section-subtitle {
        margin-top: 3px;

        color: var(--muted);
        font-size: 11px;
    }


    /* =========================================================
       FIELDS
    ========================================================= */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;

        margin-bottom: 7px;

        color: #42516a;
        font-size: 12px;
        font-weight: 700;
    }

    .required {
        color: var(--danger);
    }

    .form-control,
    .form-select,
    .form-textarea {
        width: 100%;

        border: 1px solid #dfe6ef;
        border-radius: 10px;

        background: #ffffff;
        color: var(--text);

        outline: none;

        font-size: 13px;

        transition: 0.2s ease;
    }

    .form-control,
    .form-select {
        height: 43px;
        padding: 0 13px;
    }

    .form-textarea {
        min-height: 100px;

        padding: 12px 13px;

        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px rgba(23, 105, 232, 0.08);
    }

    .form-control[readonly] {
        background: #f5f7fa;
        color: #536179;
    }

    .field-error {
        margin-top: 5px;

        color: var(--danger);
        font-size: 11px;
        font-weight: 600;
    }

    .field-note {
        margin-top: 5px;

        color: var(--muted);
        font-size: 10px;
    }


    /* =========================================================
       BUDGET INFORMATION
    ========================================================= */

    .budget-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;

        margin-top: 17px;
    }

    .budget-card {
        padding: 15px 16px;

        border: 1px solid #e2e8f0;
        border-radius: 12px;

        background: #f9fbfd;
    }

    .budget-label {
        margin-bottom: 5px;

        color: var(--muted);
        font-size: 10px;
        font-weight: 600;
    }

    .budget-value {
        color: var(--navy);
        font-size: 17px;
        font-weight: 800;
    }

    .budget-card.highlight {
        border-color: rgba(23, 105, 232, 0.25);
        background: var(--primary-soft);
    }

    .budget-card.limit {
        border-color: rgba(245, 158, 11, 0.3);
        background: var(--warning-soft);
    }

    .budget-message {
        display: none;

        margin-top: 12px;
        padding: 10px 12px;

        border-radius: 9px;

        font-size: 11px;
        font-weight: 700;
    }

    .budget-message.error {
        display: block;

        background: var(--danger-soft);
        color: var(--danger);
    }

    .budget-message.success {
        display: block;

        background: var(--success-soft);
        color: var(--success);
    }


    /* =========================================================
       REQUEST ITEMS
    ========================================================= */

    .items-wrapper {
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        min-width: 1050px;

        border-collapse: separate;
        border-spacing: 0;

        border: 1px solid #e3e9f1;
        border-radius: 12px;

        overflow: hidden;
    }

    .items-table th {
        padding: 11px 10px;

        background: #f5f7fa;
        border-bottom: 1px solid #e3e9f1;

        color: #5e6c82;

        text-align: left;
        font-size: 10px;
        font-weight: 750;
    }

    .items-table td {
        padding: 9px 8px;

        border-bottom: 1px solid #edf1f6;

        vertical-align: top;
    }

    .items-table tbody tr:last-child td {
        border-bottom: none;
    }

    .item-number {
        padding-top: 12px !important;

        color: var(--muted);
        font-size: 11px;
        font-weight: 700;

        text-align: center;
    }

    .item-input {
        width: 100%;
        height: 38px;

        padding: 0 10px;

        border: 1px solid #dfe6ef;
        border-radius: 8px;

        outline: none;

        font-size: 11px;
    }

    .item-input:focus {
        border-color: var(--primary);
    }

    .item-total {
        background: #f5f7fa;
        color: #536179;
    }

    .remove-item-button {
        width: 34px;
        height: 34px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 1px solid rgba(239, 68, 68, 0.18);
        border-radius: 8px;

        background: var(--danger-soft);
        color: var(--danger);

        cursor: pointer;
    }

    .remove-item-button:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .items-actions {
        display: flex;
        justify-content: flex-end;

        margin-top: 10px;
    }

    .add-item-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 8px 12px;

        border: 1px solid rgba(23, 105, 232, 0.2);
        border-radius: 9px;

        background: var(--primary-soft);
        color: var(--primary);

        font-size: 11px;
        font-weight: 700;

        cursor: pointer;
    }


    /* =========================================================
       ESTIMATED AMOUNT
    ========================================================= */

    .estimated-wrapper {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 14px;

        align-items: end;

        margin-top: 18px;
    }

    .estimated-limit {
        padding-bottom: 12px;

        color: var(--muted);
        font-size: 10px;

        white-space: nowrap;
    }


    /* =========================================================
       VENDOR QUOTATIONS
    ========================================================= */

    .vendor-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .vendor-count {
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
    }

    .add-vendor-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 8px 12px;

        border: 1px solid rgba(23, 105, 232, 0.2);
        border-radius: 9px;

        background: var(--primary-soft);
        color: var(--primary);

        font-size: 11px;
        font-weight: 700;

        cursor: pointer;
    }

    .vendor-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .vendor-card {
        position: relative;

        padding: 18px;

        border: 1px solid rgba(23, 105, 232, 0.2);
        border-radius: 14px;

        background:
            linear-gradient(
                180deg,
                rgba(23, 105, 232, 0.025),
                rgba(255, 255, 255, 1)
            );
    }

    .vendor-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;

        margin-bottom: 15px;
    }

    .vendor-title {
        color: var(--navy);
        font-size: 14px;
        font-weight: 800;
    }

    .remove-vendor-button {
        border: none;

        background: transparent;
        color: var(--danger);

        font-size: 11px;
        font-weight: 700;

        cursor: pointer;
    }

    .vendor-field {
        margin-bottom: 12px;
    }

    .vendor-field:last-child {
        margin-bottom: 0;
    }


    /* =========================================================
       FILE UPLOAD
    ========================================================= */

    .file-upload {
        position: relative;
    }

    .file-input {
        position: absolute;

        width: 1px;
        height: 1px;

        opacity: 0;
        overflow: hidden;
    }

    .file-label {
        min-height: 72px;

        padding: 12px;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        border: 1px dashed #b7c6d9;
        border-radius: 10px;

        background: #fafcff;

        color: var(--primary);

        text-align: center;

        cursor: pointer;
    }

    .file-label:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    .file-title {
        font-size: 11px;
        font-weight: 750;
    }

    .file-name {
        max-width: 100%;

        margin-top: 5px;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;

        color: var(--muted);
        font-size: 9px;
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;

        padding: 18px 24px;

        background: #fbfcfe;
    }

    .cancel-button,
    .submit-button {
        min-height: 43px;

        padding: 0 20px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        font-size: 12px;
        font-weight: 750;

        cursor: pointer;

        transition: 0.2s ease;
    }

    .cancel-button {
        border: 1px solid #dfe6ef;

        background: #ffffff;
        color: #536179;
    }

    .cancel-button:hover {
        border-color: #bfc9d8;
        background: #f7f9fc;
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

        box-shadow: var(--shadow);
    }

    .submit-button:hover {
        transform: translateY(-1px);
    }

    .submit-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }


    /* =========================================================
       GENERAL ERROR
    ========================================================= */

    .alert {
        margin-bottom: 18px;

        padding: 13px 15px;

        border-radius: 11px;

        font-size: 12px;
        font-weight: 600;
    }

    .alert-error {
        background: var(--danger-soft);
        color: var(--danger);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .vendor-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 800px) {

        .form-grid,
        .budget-grid,
        .vendor-grid,
        .estimated-wrapper {
            grid-template-columns: 1fr;
        }

        .estimated-limit {
            padding-bottom: 0;
        }

        .section-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .vendor-header-actions {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 600px) {

        .form-section {
            padding: 19px 16px;
        }

        .form-actions {
            padding: 16px;

            flex-direction: column-reverse;
        }

        .cancel-button,
        .submit-button {
            width: 100%;
        }
    }

@endsection


@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Preserve old request-item values after validation errors
    |--------------------------------------------------------------------------
    */

    $oldItems = old('items');

    if (!is_array($oldItems) || count($oldItems) === 0) {
        $oldItems = [
            [
                'item_name' => '',
                'item_description' => '',
                'quantity' => 1,
                'unit_price' => '',
            ]
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Preserve vendor quotation fields after validation errors.
    | Minimum of 3 cards must always exist.
    |--------------------------------------------------------------------------
    */

    $oldQuotations = old('quotations');

    if (!is_array($oldQuotations)) {
        $oldQuotations = [];
    }

    while (count($oldQuotations) < 3) {

        $oldQuotations[] = [
            'vendor_id' => '',
            'quotation_reference_number' => '',
            'quotation_date' => '',
            'total_amount' => '',
            'warranty_period' => '',
            'delivery_lead_time' => '',
            'payment_terms' => '',
        ];
    }


    $department = auth()->user()->ptjDepartment;

    $departmentName =
        optional($department)->department_name
        ?? optional($department)->ptj_name
        ?? 'Department not assigned';

@endphp


{{-- =========================================================
     PAGE HEADER
========================================================= --}}

<div class="page-heading">

    <div class="breadcrumb">

        <a href="{{ route('ptj.dashboard') }}">
            Dashboard
        </a>

        &nbsp;›&nbsp;

        <a href="{{ route('ptj.procurement.index') }}">
            Procurement Request
        </a>

        &nbsp;›&nbsp;

        New Request

    </div>

    <h1 class="page-title">
        Initiate New Procurement Request
    </h1>

    <div class="page-description">
        Enter procurement items and vendor quotation information for approval.
    </div>

</div>


@if (session('error'))

    <div class="alert alert-error">
        {{ session('error') }}
    </div>

@endif


<form
    method="POST"
    action="{{ route('ptj.procurement.store') }}"
    enctype="multipart/form-data"
    id="procurementForm"
>

    @csrf


    <div class="procurement-form-card">


        {{-- =====================================================
             GENERAL REQUEST DETAILS
        ====================================================== --}}

        <section class="form-section">

            <div class="section-header">

                <div class="section-title-wrap">

                    <div class="section-marker"></div>

                    <div>

                        <div class="section-title">
                            General Request Details
                        </div>

                        <div class="section-subtitle">
                            Provide the basic information for this procurement request.
                        </div>

                    </div>

                </div>

            </div>


            <div class="form-grid">


                {{-- Request Title --}}
                <div class="form-group">

                    <label
                        for="request_title"
                        class="form-label"
                    >
                        Request Title
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="request_title"
                        id="request_title"
                        class="form-control"
                        value="{{ old('request_title') }}"
                        maxlength="100"
                        placeholder="e.g., Office Laptops"
                        required
                    >

                    @error('request_title')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Department --}}
                <div class="form-group">

                    <label class="form-label">
                        Department
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $departmentName }}"
                        readonly
                    >

                    <div class="field-note">
                        Automatically assigned from your PTJ account.
                    </div>

                </div>


                {{-- Description --}}
                <div class="form-group full">

                    <label
                        for="request_description"
                        class="form-label"
                    >
                        Request Description
                        <span class="required">*</span>
                    </label>

                    <textarea
                        name="request_description"
                        id="request_description"
                        class="form-textarea"
                        placeholder="Provide a detailed description of the procurement request..."
                        required
                    >{{ old('request_description') }}</textarea>

                    @error('request_description')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </section>



        {{-- =====================================================
             REQUEST ITEMS
        ====================================================== --}}

        <section class="form-section">

            <div class="section-header">

                <div class="section-title-wrap">

                    <div class="section-marker"></div>

                    <div>

                        <div class="section-title">
                            Request Items
                        </div>

                        <div class="section-subtitle">
                            Add all items required for this procurement.
                        </div>

                    </div>

                </div>

            </div>


            <div class="items-wrapper">

                <table class="items-table">

                    <thead>

                        <tr>
                            <th style="width: 45px;">#</th>
                            <th style="width: 180px;">
                                Item Name <span class="required">*</span>
                            </th>
                            <th>
                                Description <span class="required">*</span>
                            </th>
                            <th style="width: 100px;">
                                Quantity <span class="required">*</span>
                            </th>
                            <th style="width: 140px;">
                                Unit Price (RM) <span class="required">*</span>
                            </th>
                            <th style="width: 140px;">
                                Total Price (RM)
                            </th>
                            <th style="width: 70px;">
                                Action
                            </th>
                        </tr>

                    </thead>


                    <tbody id="itemsBody">

                        @foreach ($oldItems as $index => $item)

                            <tr class="item-row">

                                <td class="item-number">
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <input
                                        type="text"
                                        name="items[{{ $index }}][item_name]"
                                        class="item-input"
                                        value="{{ $item['item_name'] ?? '' }}"
                                        placeholder="e.g., Laptop"
                                        required
                                    >

                                    @error("items.$index.item_name")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </td>


                                <td>

                                    <input
                                        type="text"
                                        name="items[{{ $index }}][item_description]"
                                        class="item-input"
                                        value="{{ $item['item_description'] ?? '' }}"
                                        placeholder="e.g., Dell Latitude 5440"
                                        required
                                    >

                                    @error("items.$index.item_description")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </td>


                                <td>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][quantity]"
                                        class="item-input item-quantity"
                                        value="{{ $item['quantity'] ?? 1 }}"
                                        min="1"
                                        step="1"
                                        required
                                    >

                                    @error("items.$index.quantity")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </td>


                                <td>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][unit_price]"
                                        class="item-input item-unit-price"
                                        value="{{ $item['unit_price'] ?? '' }}"
                                        min="0.01"
                                        step="0.01"
                                        placeholder="0.00"
                                        required
                                    >

                                    @error("items.$index.unit_price")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </td>


                                <td>

                                    <input
                                        type="text"
                                        class="item-input item-total"
                                        value="0.00"
                                        readonly
                                        tabindex="-1"
                                    >

                                </td>


                                <td style="text-align: center;">

                                    <button
                                        type="button"
                                        class="remove-item-button"
                                        title="Remove item"
                                    >
                                        ×
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @error('items')
                <div class="field-error">
                    {{ $message }}
                </div>
            @enderror


            <div class="items-actions">

                <button
                    type="button"
                    class="add-item-button"
                    id="addItemButton"
                >
                    + Add Item
                </button>

            </div>


            {{-- Estimated Amount --}}
            <div class="estimated-wrapper">

                <div class="form-group">

                    <label
                        for="estimatedAmount"
                        class="form-label"
                    >
                        Estimated Amount (RM)
                    </label>

                    <input
                        type="text"
                        id="estimatedAmount"
                        class="form-control"
                        value="0.00"
                        readonly
                    >

                    @error('estimated_amount')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <div class="estimated-limit">
                    Maximum RM 50,000 for Direct Purchase
                </div>

            </div>


            {{-- Budget Information --}}
            <div class="budget-grid">

                <div class="budget-card">

                    <div class="budget-label">
                        Allocated Budget
                    </div>

                    <div class="budget-value">
                        RM {{ number_format((float) $budget->allocated_amount, 2) }}
                    </div>

                </div>


                <div class="budget-card highlight">

                    <div class="budget-label">
                        Available Budget
                    </div>

                    <div class="budget-value">
                        RM {{ number_format((float) $availableBudget, 2) }}
                    </div>

                </div>


                <div class="budget-card limit">

                    <div class="budget-label">
                        Direct Purchase Limit
                    </div>

                    <div class="budget-value">
                        RM 50,000.00
                    </div>

                </div>

            </div>


            <div
                id="budgetMessage"
                class="budget-message"
            ></div>

        </section>



        {{-- =====================================================
             VENDOR QUOTATIONS
        ====================================================== --}}

        <section class="form-section">

            <div class="section-header">

                <div class="section-title-wrap">

                    <div class="section-marker"></div>

                    <div>

                        <div class="section-title">
                            Vendor Quotations
                        </div>

                        <div class="section-subtitle">
                            Minimum three different vendor quotations are required.
                        </div>

                    </div>

                </div>


                <div class="vendor-header-actions">

                    <span
                        class="vendor-count"
                        id="vendorCount"
                    >
                        {{ count($oldQuotations) }} vendor(s)
                    </span>

                    <button
                        type="button"
                        class="add-vendor-button"
                        id="addVendorButton"
                    >
                        + Add Vendor
                    </button>

                </div>

            </div>


            @error('quotations')

                <div
                    class="field-error"
                    style="margin-bottom: 12px;"
                >
                    {{ $message }}
                </div>

            @enderror


            <div
                class="vendor-grid"
                id="vendorGrid"
            >

                @foreach ($oldQuotations as $index => $quotation)

                    <div
                        class="vendor-card"
                        data-vendor-card
                    >

                        <div class="vendor-card-header">

                            <div class="vendor-title">
                                Vendor {{ $loop->iteration }}
                            </div>

                            <button
                                type="button"
                                class="remove-vendor-button"
                            >
                                Remove
                            </button>

                        </div>


                        {{-- Vendor --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Select Vendor
                                <span class="required">*</span>
                            </label>

                            <select
                                name="quotations[{{ $index }}][vendor_id]"
                                class="form-select vendor-select"
                                required
                            >

                                <option value="">
                                    Choose vendor
                                </option>

                                @foreach ($vendors as $vendor)

                                    <option
                                        value="{{ $vendor->vendor_id }}"
                                        @selected(
                                            (string) ($quotation['vendor_id'] ?? '')
                                            ===
                                            (string) $vendor->vendor_id
                                        )
                                    >
                                        {{ $vendor->company_name }}
                                    </option>

                                @endforeach

                            </select>

                            @error("quotations.$index.vendor_id")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Reference --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Quotation Reference Number
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="quotations[{{ $index }}][quotation_reference_number]"
                                class="form-control"
                                value="{{ $quotation['quotation_reference_number'] ?? '' }}"
                                placeholder="e.g., QT-2026-001"
                                maxlength="100"
                                required
                            >

                            @error("quotations.$index.quotation_reference_number")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Date --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Quotation Date
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="quotations[{{ $index }}][quotation_date]"
                                class="form-control"
                                value="{{ $quotation['quotation_date'] ?? '' }}"
                                required
                            >

                            @error("quotations.$index.quotation_date")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Amount --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Quoted Amount (RM)
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                name="quotations[{{ $index }}][total_amount]"
                                class="form-control"
                                value="{{ $quotation['total_amount'] ?? '' }}"
                                min="0.01"
                                step="0.01"
                                placeholder="0.00"
                                required
                            >

                            @error("quotations.$index.total_amount")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Warranty --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Warranty Period (Months)
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                name="quotations[{{ $index }}][warranty_period]"
                                class="form-control"
                                value="{{ $quotation['warranty_period'] ?? '' }}"
                                min="0"
                                step="1"
                                placeholder="e.g., 24"
                                required
                            >

                            @error("quotations.$index.warranty_period")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Delivery --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Delivery Lead Time (Days)
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                name="quotations[{{ $index }}][delivery_lead_time]"
                                class="form-control"
                                value="{{ $quotation['delivery_lead_time'] ?? '' }}"
                                min="0"
                                step="1"
                                placeholder="e.g., 7"
                                required
                            >

                            @error("quotations.$index.delivery_lead_time")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Payment Terms --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Payment Terms
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="quotations[{{ $index }}][payment_terms]"
                                class="form-control"
                                value="{{ $quotation['payment_terms'] ?? '' }}"
                                placeholder="e.g., 30 days"
                                maxlength="255"
                                required
                            >

                            @error("quotations.$index.payment_terms")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PDF --}}
                        <div class="vendor-field">

                            <label class="form-label">
                                Quotation PDF
                                <span class="required">*</span>
                            </label>

                            <div class="file-upload">

                                <input
                                    type="file"
                                    name="quotations[{{ $index }}][quotation_file]"
                                    id="quotationFile{{ $index }}"
                                    class="file-input quotation-file-input"
                                    accept="application/pdf,.pdf"
                                    required
                                >

                                <label
                                    for="quotationFile{{ $index }}"
                                    class="file-label"
                                >

                                    <span class="file-title">
                                        Upload Quotation PDF
                                    </span>

                                    <span class="file-name">
                                        PDF only · Maximum 5 MB
                                    </span>

                                </label>

                            </div>

                            @error("quotations.$index.quotation_file")

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                @endforeach

            </div>

        </section>



        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="form-actions">

            <button
                type="button"
                class="cancel-button"
                id="cancelButton"
            >
                Cancel
            </button>

            <button
                type="submit"
                class="submit-button"
                id="submitButton"
            >
                Submit Request
            </button>

        </div>

    </div>

</form>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const itemsBody = document.getElementById('itemsBody');
    const addItemButton = document.getElementById('addItemButton');

    const vendorGrid = document.getElementById('vendorGrid');
    const addVendorButton = document.getElementById('addVendorButton');
    const vendorCount = document.getElementById('vendorCount');

    const estimatedAmount = document.getElementById('estimatedAmount');
    const budgetMessage = document.getElementById('budgetMessage');

    const procurementForm = document.getElementById('procurementForm');
    const submitButton = document.getElementById('submitButton');
    const cancelButton = document.getElementById('cancelButton');

    const availableBudget = {{ (float) $availableBudget }};
    const directPurchaseLimit = 50000;

    let itemIndex = {{ count($oldItems) }};
    let quotationIndex = {{ count($oldQuotations) }};


    /*
    |--------------------------------------------------------------------------
    | REQUEST ITEM CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateTotals() {

        let grandTotal = 0;

        document.querySelectorAll('.item-row').forEach(function (row) {

            const quantityInput =
                row.querySelector('.item-quantity');

            const unitPriceInput =
                row.querySelector('.item-unit-price');

            const totalInput =
                row.querySelector('.item-total');


            const quantity =
                parseFloat(quantityInput.value) || 0;

            const unitPrice =
                parseFloat(unitPriceInput.value) || 0;


            const rowTotal =
                quantity * unitPrice;


            totalInput.value =
                rowTotal.toFixed(2);


            grandTotal += rowTotal;

        });


        estimatedAmount.value =
            grandTotal.toFixed(2);


        validateBudgetPreview(grandTotal);
    }


    function validateBudgetPreview(total) {

        budgetMessage.className = 'budget-message';
        budgetMessage.textContent = '';

        submitButton.disabled = false;


        if (total > directPurchaseLimit) {

            budgetMessage.classList.add('error');

            budgetMessage.textContent =
                'Amount exceeds the RM 50,000 Direct Purchase limit.';

            submitButton.disabled = true;

            return;
        }


        if (total > availableBudget) {

            budgetMessage.classList.add('error');

            budgetMessage.textContent =
                'Insufficient budget balance. Available budget: RM '
                + availableBudget.toLocaleString(
                    'en-MY',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );

            submitButton.disabled = true;

            return;
        }


        if (total > 0) {

            budgetMessage.classList.add('success');

            budgetMessage.textContent =
                'Estimated amount is within the available budget and Direct Purchase limit.';
        }
    }


    function refreshItemNumbers() {

        const rows =
            document.querySelectorAll('.item-row');


        rows.forEach(function (row, index) {

            row.querySelector('.item-number').textContent =
                index + 1;

        });


        document.querySelectorAll('.remove-item-button')
            .forEach(function (button) {

                button.disabled =
                    rows.length <= 1;

            });
    }


    function bindItemRow(row) {

        row.querySelector('.item-quantity')
            .addEventListener(
                'input',
                calculateTotals
            );


        row.querySelector('.item-unit-price')
            .addEventListener(
                'input',
                calculateTotals
            );


        row.querySelector('.remove-item-button')
            .addEventListener(
                'click',
                function () {

                    if (
                        document.querySelectorAll('.item-row').length <= 1
                    ) {
                        return;
                    }

                    row.remove();

                    refreshItemNumbers();
                    calculateTotals();

                }
            );
    }


    document.querySelectorAll('.item-row')
        .forEach(bindItemRow);


    addItemButton.addEventListener('click', function () {

        const row =
            document.createElement('tr');


        row.className =
            'item-row';


        row.innerHTML = `

            <td class="item-number"></td>

            <td>
                <input
                    type="text"
                    name="items[${itemIndex}][item_name]"
                    class="item-input"
                    placeholder="e.g., Laptop"
                    required
                >
            </td>

            <td>
                <input
                    type="text"
                    name="items[${itemIndex}][item_description]"
                    class="item-input"
                    placeholder="e.g., Dell Latitude 5440"
                    required
                >
            </td>

            <td>
                <input
                    type="number"
                    name="items[${itemIndex}][quantity]"
                    class="item-input item-quantity"
                    value="1"
                    min="1"
                    step="1"
                    required
                >
            </td>

            <td>
                <input
                    type="number"
                    name="items[${itemIndex}][unit_price]"
                    class="item-input item-unit-price"
                    min="0.01"
                    step="0.01"
                    placeholder="0.00"
                    required
                >
            </td>

            <td>
                <input
                    type="text"
                    class="item-input item-total"
                    value="0.00"
                    readonly
                    tabindex="-1"
                >
            </td>

            <td style="text-align:center;">
                <button
                    type="button"
                    class="remove-item-button"
                    title="Remove item"
                >
                    ×
                </button>
            </td>
        `;


        itemsBody.appendChild(row);


        bindItemRow(row);

        itemIndex++;

        refreshItemNumbers();
        calculateTotals();

    });


    /*
    |--------------------------------------------------------------------------
    | VENDOR QUOTATION MANAGEMENT
    |--------------------------------------------------------------------------
    */

    function refreshVendorCards() {

        const cards =
            document.querySelectorAll('[data-vendor-card]');


        cards.forEach(function (card, index) {

            card.querySelector('.vendor-title')
                .textContent =
                'Vendor ' + (index + 1);


            const removeButton =
                card.querySelector(
                    '.remove-vendor-button'
                );


            if (removeButton) {

                removeButton.style.display =
                    cards.length > 3
                        ? 'inline'
                        : 'none';
            }
        });


        vendorCount.textContent =
            cards.length + ' vendor(s)';


        updateVendorOptions();
    }


    function updateVendorOptions() {

        const selects =
            Array.from(
                document.querySelectorAll(
                    '.vendor-select'
                )
            );


        const selectedValues =
            selects
                .map(select => select.value)
                .filter(value => value !== '');


        selects.forEach(function (select) {

            const ownValue =
                select.value;


            Array.from(select.options)
                .forEach(function (option) {

                    if (option.value === '') {
                        return;
                    }


                    option.disabled =
                        selectedValues.includes(
                            option.value
                        )
                        &&
                        option.value !== ownValue;

                });
        });
    }


    function bindVendorCard(card) {

        const vendorSelect =
            card.querySelector(
                '.vendor-select'
            );


        vendorSelect.addEventListener(
            'change',
            updateVendorOptions
        );


        const removeButton =
            card.querySelector(
                '.remove-vendor-button'
            );


        removeButton.addEventListener(
            'click',
            function () {

                const cards =
                    document.querySelectorAll(
                        '[data-vendor-card]'
                    );


                if (cards.length <= 3) {

                    alert(
                        'At least three vendor quotations are required.'
                    );

                    return;
                }


                card.remove();

                refreshVendorCards();

            }
        );


        const fileInput =
            card.querySelector(
                '.quotation-file-input'
            );


        fileInput.addEventListener(
            'change',
            function () {

                const label =
                    card.querySelector(
                        '.file-name'
                    );


                if (
                    this.files
                    &&
                    this.files.length > 0
                ) {

                    label.textContent =
                        this.files[0].name;

                } else {

                    label.textContent =
                        'PDF only · Maximum 5 MB';
                }

            }
        );
    }


    document.querySelectorAll(
        '[data-vendor-card]'
    ).forEach(bindVendorCard);


    addVendorButton.addEventListener(
        'click',
        function () {

            const card =
                document.createElement('div');


            card.className =
                'vendor-card';


            card.setAttribute(
                'data-vendor-card',
                ''
            );


            card.innerHTML = `

                <div class="vendor-card-header">

                    <div class="vendor-title"></div>

                    <button
                        type="button"
                        class="remove-vendor-button"
                    >
                        Remove
                    </button>

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Select Vendor
                        <span class="required">*</span>
                    </label>

                    <select
                        name="quotations[${quotationIndex}][vendor_id]"
                        class="form-select vendor-select"
                        required
                    >

                        <option value="">
                            Choose vendor
                        </option>

                        @foreach ($vendors as $vendor)

                            <option value="{{ $vendor->vendor_id }}">
                                {{ addslashes($vendor->company_name) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Quotation Reference Number
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="quotations[${quotationIndex}][quotation_reference_number]"
                        class="form-control"
                        maxlength="100"
                        placeholder="e.g., QT-2026-001"
                        required
                    >

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Quotation Date
                        <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="quotations[${quotationIndex}][quotation_date]"
                        class="form-control"
                        required
                    >

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Quoted Amount (RM)
                        <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="quotations[${quotationIndex}][total_amount]"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Warranty Period (Months)
                        <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="quotations[${quotationIndex}][warranty_period]"
                        class="form-control"
                        min="0"
                        step="1"
                        placeholder="e.g., 24"
                        required
                    >

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Delivery Lead Time (Days)
                        <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="quotations[${quotationIndex}][delivery_lead_time]"
                        class="form-control"
                        min="0"
                        step="1"
                        placeholder="e.g., 7"
                        required
                    >

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Payment Terms
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="quotations[${quotationIndex}][payment_terms]"
                        class="form-control"
                        maxlength="255"
                        placeholder="e.g., 30 days"
                        required
                    >

                </div>


                <div class="vendor-field">

                    <label class="form-label">
                        Quotation PDF
                        <span class="required">*</span>
                    </label>

                    <div class="file-upload">

                        <input
                            type="file"
                            name="quotations[${quotationIndex}][quotation_file]"
                            id="quotationFile${quotationIndex}"
                            class="file-input quotation-file-input"
                            accept="application/pdf,.pdf"
                            required
                        >

                        <label
                            for="quotationFile${quotationIndex}"
                            class="file-label"
                        >

                            <span class="file-title">
                                Upload Quotation PDF
                            </span>

                            <span class="file-name">
                                PDF only · Maximum 5 MB
                            </span>

                        </label>

                    </div>

                </div>
            `;


            vendorGrid.appendChild(card);


            bindVendorCard(card);

            quotationIndex++;

            refreshVendorCards();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CANCEL FLOW
    |--------------------------------------------------------------------------
    */

    cancelButton.addEventListener(
        'click',
        function () {

            const confirmed =
                confirm(
                    'Are you sure you want to cancel? All entered data will be discarded.'
                );


            if (confirmed) {

                window.location.href =
                    "{{ route('ptj.procurement.index') }}";
            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    refreshItemNumbers();
    refreshVendorCards();
    calculateTotals();


    /*
    |--------------------------------------------------------------------------
    | FINAL FRONTEND CHECK
    |--------------------------------------------------------------------------
    */

    procurementForm.addEventListener(
        'submit',
        function (event) {

            const vendorCards =
                document.querySelectorAll(
                    '[data-vendor-card]'
                );


            if (vendorCards.length < 3) {

                event.preventDefault();

                alert(
                    'At least three vendor quotations are required.'
                );

                return;
            }


            const total =
                parseFloat(
                    estimatedAmount.value
                )
                || 0;


            if (total <= 0) {

                event.preventDefault();

                alert(
                    'Please add valid request item amounts.'
                );

                return;
            }


            if (total > directPurchaseLimit) {

                event.preventDefault();

                alert(
                    'Amount exceeds the RM 50,000 Direct Purchase limit.'
                );

                return;
            }


            if (total > availableBudget) {

                event.preventDefault();

                alert(
                    'Insufficient budget balance.'
                );

                return;
            }


            submitButton.disabled = true;

            submitButton.textContent =
                'Submitting...';

        }
    );

});

</script>

@endsection