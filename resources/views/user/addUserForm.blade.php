@extends('layouts.ucpms')

@section('title', 'Add User | UCPMS')


@section('page-style')

    /* =========================================================
       PAGE SHELL
    ========================================================= */

    .add-user-shell {
        width: 100%;
        max-width: 1080px;

        margin: 0 auto;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 24px;

        margin-bottom: 24px;
    }


    .page-heading-content {
        max-width: 720px;
    }


    .page-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        margin-bottom: 8px;

        font-size: 11px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.7px;

        color: var(--primary);
    }


    .page-eyebrow-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: var(--primary);
    }


    .page-heading h1 {
        font-size: 30px;
        font-weight: 800;

        line-height: 1.2;

        letter-spacing: -0.7px;

        color: var(--navy);
    }


    .page-heading p {
        margin-top: 8px;

        font-size: 13px;
        line-height: 1.6;

        color: var(--muted);
    }


    .back-directory-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        min-height: 40px;

        padding: 0 13px;

        border: 1px solid #dfe5ef;
        border-radius: 9px;

        background: #ffffff;

        color: #5f6e84;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;

        transition: 0.2s ease;
    }


    .back-directory-link:hover {
        border-color: var(--primary);

        background: var(--primary-soft);

        color: var(--primary);
    }


    .back-directory-link svg {
        width: 16px;
        height: 16px;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .alert {
        margin-bottom: 20px;

        padding: 14px 16px;

        border-radius: 11px;

        font-size: 12px;
        font-weight: 600;
        line-height: 1.6;
    }


    .alert-danger {
        border: 1px solid #f4c8ce;

        background: var(--danger-soft);

        color: var(--danger);
    }


    .alert-title {
        margin-bottom: 5px;

        font-weight: 800;
    }


    /* =========================================================
       FORM WRAPPER
    ========================================================= */

    .form-wrapper {
        width: 100%;
    }


    .form-card {
        overflow: hidden;

        margin-bottom: 20px;

        border: 1px solid var(--border);
        border-radius: 16px;

        background: var(--surface);

        box-shadow: var(--shadow);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .form-card:hover {
        box-shadow:
            0 14px 36px rgba(30, 64, 175, 0.09);
    }


    /* =========================================================
       CARD HEADER
    ========================================================= */

    .form-card-header {
        display: flex;
        align-items: center;

        gap: 13px;

        padding: 17px 21px;

        border-bottom: 1px solid var(--border);

        background:
            linear-gradient(
                90deg,
                var(--primary-soft),
                #ffffff 75%
            );
    }


    .section-number {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 11px;

        background: #ffffff;

        border: 1px solid var(--primary-soft-strong);

        box-shadow:
            0 4px 12px rgba(109, 40, 217, 0.08);

        color: var(--primary);

        font-size: 12px;
        font-weight: 800;
    }


    .section-icon {
        width: 19px;
        height: 19px;
    }


    .section-icon svg {
        width: 19px;
        height: 19px;
    }


    .form-card-heading {
        flex: 1;
    }


    .form-card-title {
        font-size: 15px;
        font-weight: 800;

        color: var(--navy);
    }


    .form-card-description {
        margin-top: 3px;

        font-size: 11px;
        line-height: 1.5;

        color: var(--muted);
    }


    .section-step {
        padding: 5px 9px;

        border-radius: 999px;

        background: #ffffff;

        color: var(--primary);

        font-size: 9px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.4px;
    }


    .form-card-body {
        padding: 23px;
    }


    /* =========================================================
       FORM GRID
    ========================================================= */

    .form-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        column-gap: 20px;
        row-gap: 2px;
    }


    .form-group {
        margin-bottom: 18px;
    }


    .form-group.full-width {
        grid-column: 1 / -1;
    }


    .form-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 10px;

        margin-bottom: 7px;
    }


    .form-label {
        display: block;

        font-size: 12px;
        font-weight: 700;

        color: #4f5f77;
    }


    .required {
        color: var(--danger);
    }


    .optional-label {
        font-size: 9px;
        font-weight: 700;

        color: #9aa4b4;
    }


    /* =========================================================
       INPUTS
    ========================================================= */

    .input-wrapper {
        position: relative;
    }


    .input-icon {
        position: absolute;

        left: 13px;
        top: 50%;

        width: 17px;
        height: 17px;

        transform: translateY(-50%);

        color: #96a1b2;

        pointer-events: none;
    }


    .input-icon svg {
        width: 17px;
        height: 17px;
    }


    .form-control {
        width: 100%;
        height: 46px;

        padding: 0 13px;

        border: 1px solid #dbe2ed;
        border-radius: 10px;

        background: #ffffff;

        color: var(--text);

        outline: none;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }


    .form-control.with-icon {
        padding-left: 40px;
    }


    .form-control:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px var(--primary-soft);
    }


    .form-control:hover:not(:disabled) {
        border-color: #c7d0dd;
    }


    .form-control:disabled {
        background: #f3f5f8;

        color: #8b95a5;

        cursor: not-allowed;
    }


    /* =========================================================
    INVALID INPUT STATE
    ========================================================= */

    .form-control.is-invalid,
    .form-control:user-invalid {
        border-color: var(--danger) !important;

        background: #fffafb;

        box-shadow:
            0 0 0 3px rgba(220, 53, 69, 0.08);
    }


    .form-control.is-invalid:focus,
    .form-control:user-invalid:focus {
        border-color: var(--danger) !important;

        background: #fffafb;

        box-shadow:
            0 0 0 3px rgba(220, 53, 69, 0.12);
    }


    .form-note {
        display: flex;
        align-items: flex-start;

        gap: 5px;

        margin-top: 7px;

        font-size: 10px;
        line-height: 1.5;

        color: var(--muted);
    }


    .form-note svg {
        width: 13px;
        height: 13px;

        margin-top: 1px;

        flex-shrink: 0;
    }


    .error-text {
        margin-top: 6px;

        font-size: 10px;
        font-weight: 600;

        color: var(--danger);
    }


    /* =========================================================
       ROLE RULE PANEL
    ========================================================= */

    .role-rule {
        position: relative;

        display: flex;
        align-items: flex-start;

        gap: 11px;

        margin-top: 3px;

        padding: 13px 15px 13px 17px;

        overflow: hidden;

        border: 1px solid #ddcff7;
        border-radius: 11px;

        background:
            linear-gradient(
                90deg,
                var(--primary-soft),
                #fbf9ff
            );

        color: #5e5271;

        font-size: 11px;
        line-height: 1.65;
    }


    .role-rule::before {
        content: "";

        position: absolute;

        left: 0;
        top: 0;
        bottom: 0;

        width: 4px;

        background: var(--primary);
    }


    .role-rule-icon {
        width: 28px;
        height: 28px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 8px;

        background: #ffffff;

        color: var(--primary);
    }


    .role-rule-icon svg {
        width: 16px;
        height: 16px;
    }


    .role-rule strong {
        display: block;

        margin-bottom: 2px;

        color: var(--primary);
    }


    /* =========================================================
       PASSWORD FIELDS
    ========================================================= */

    .password-wrapper {
        position: relative;
    }


    .password-wrapper .form-control {
        padding-right: 48px;
    }


    .password-toggle {
        position: absolute;

        top: 50%;
        right: 8px;

        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        transform: translateY(-50%);

        border: none;
        border-radius: 8px;

        background: transparent;

        color: #7e899a;

        cursor: pointer;

        transition: 0.2s ease;
    }


    .password-toggle:hover {
        background: var(--primary-soft);

        color: var(--primary);
    }


    .password-toggle svg {
        width: 17px;
        height: 17px;
    }


    .password-info {
        display: flex;
        align-items: flex-start;

        gap: 10px;

        margin-top: 3px;

        padding: 12px 14px;

        border: 1px solid #e5eaf1;
        border-radius: 10px;

        background: #f8fafc;

        color: #66758b;

        font-size: 10px;
        line-height: 1.6;
    }


    .password-info-icon {
        width: 28px;
        height: 28px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 8px;

        background: var(--success-soft);

        color: var(--success);
    }


    .password-info-icon svg {
        width: 15px;
        height: 15px;
    }


    .password-requirement {
        margin-top: 7px;

        display: flex;
        align-items: center;

        gap: 6px;

        color: #79869a;

        font-size: 10px;
    }


    .requirement-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: var(--primary);
    }


    /* =========================================================
       FORM ACTION FOOTER
    ========================================================= */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 18px;

        margin-bottom: 35px;

        padding: 17px 20px;

        border: 1px solid var(--border);
        border-radius: 14px;

        background: #ffffff;

        box-shadow: var(--shadow);
    }


    .action-note {
        display: flex;
        align-items: center;

        gap: 7px;

        color: var(--muted);

        font-size: 10px;
    }


    .action-note svg {
        width: 15px;
        height: 15px;

        color: var(--primary);
    }


    .action-buttons {
        display: flex;
        align-items: center;

        gap: 10px;
    }


    .btn-cancel,
    .btn-submit {
        min-width: 125px;

        min-height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        padding: 0 16px;

        border-radius: 9px;

        font-size: 12px;
        font-weight: 700;

        text-align: center;

        cursor: pointer;

        transition: 0.2s ease;
    }


    .btn-cancel {
        border: 1px solid #dbe2ed;

        background: #ffffff;

        color: #65738b;
    }


    .btn-cancel:hover {
        border-color: #cbd4e0;

        background: #f7f8fb;
    }


    .btn-submit {
        border: none;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );

        color: #ffffff;

        box-shadow:
            0 8px 18px rgba(109, 40, 217, 0.18);
    }


    .btn-submit:hover {
        transform: translateY(-1px);

        box-shadow:
            0 11px 23px rgba(109, 40, 217, 0.24);
    }


    .btn-submit svg,
    .btn-cancel svg {
        width: 16px;
        height: 16px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 850px) {

        .page-heading {
            flex-direction: column;
        }


        .form-grid {
            grid-template-columns: 1fr;
        }


        .form-group.full-width {
            grid-column: auto;
        }


        .section-step {
            display: none;
        }


        .form-actions {
            align-items: stretch;
            flex-direction: column;
        }


        .action-buttons {
            width: 100%;
        }


        .btn-cancel,
        .btn-submit {
            flex: 1;
        }
    }


    @media (max-width: 550px) {

        .form-card-header {
            padding: 15px;
        }


        .form-card-body {
            padding: 17px;
        }


        .action-buttons {
            flex-direction: column-reverse;
        }


        .btn-cancel,
        .btn-submit {
            width: 100%;
        }
    }

@endsection


@section('content')

<div class="add-user-shell">


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="page-heading">


        <div class="page-heading-content">

            <div class="page-eyebrow">

                <span class="page-eyebrow-dot"></span>

                User Management

            </div>


            <h1>
                Register New System User
            </h1>


            <p>
                Create a new PTJ Administrative Staff, Ketua Jabatan or
                Bendahari account and assign the appropriate UCPMS access.
            </p>

        </div>


        <a
            href="{{ route('admin.users.index') }}"
            class="back-directory-link"
        >

            <svg viewBox="0 0 24 24" fill="none">

                <path
                    d="M15 18L9 12L15 6"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

            </svg>

            Back to User Directory

        </a>


    </div>


    <!-- =====================================================
         VALIDATION ERRORS
    ====================================================== -->

    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="alert-title">
                Please correct the following information:
            </div>


            @foreach ($errors->all() as $error)

                <div>
                    • {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <!-- =====================================================
         ADD USER FORM
    ====================================================== -->

    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
        id="addUserForm"
    >

        @csrf


        <div class="form-wrapper">


            <!-- =================================================
                 SECTION 01 — USER INFORMATION
            ================================================== -->

            <div class="form-card">


                <div class="form-card-header">


                    <div class="section-number">

                        <div class="section-icon">

                            <svg viewBox="0 0 24 24" fill="none">

                                <circle
                                    cx="12"
                                    cy="8"
                                    r="4"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M5 21C5.8 16.9 8 15 12 15C16 15 18.2 16.9 19 21"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                            </svg>

                        </div>

                    </div>


                    <div class="form-card-heading">

                        <div class="form-card-title">
                            User Information
                        </div>

                        <div class="form-card-description">
                            Enter the staff member's official identification details.
                        </div>

                    </div>


                    <div class="section-step">
                        Step 01
                    </div>


                </div>


                <div class="form-card-body">


                    <div class="form-grid">


                        <!-- Full Name -->

                        <div class="form-group">

                            <div class="form-label-row">

                                <label
                                    for="user_name"
                                    class="form-label"
                                >
                                    Full Name
                                    <span class="required">*</span>
                                </label>

                            </div>


                            <div class="input-wrapper">


                                <div class="input-icon">

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <circle
                                            cx="12"
                                            cy="8"
                                            r="3.5"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                        <path
                                            d="M5 20C5.7 16.5 7.8 15 12 15C16.2 15 18.3 16.5 19 20"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="user_name"
                                    type="text"
                                    name="user_name"

                                    value="{{ old('user_name') }}"

                                    class="form-control with-icon
                                        @error('user_name')
                                            is-invalid
                                        @enderror
                                    "

                                    maxlength="100"

                                    placeholder="e.g. Ahmad bin Abdullah"

                                    required
                                >

                            </div>


                            @error('user_name')

                                <div class="error-text">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Staff ID -->

                        <div class="form-group">

                            <div class="form-label-row">

                                <label
                                    for="staff_id"
                                    class="form-label"
                                >
                                    Staff ID
                                    <span class="required">*</span>
                                </label>

                            </div>


                            <div class="input-wrapper">


                                <div class="input-icon">

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <path
                                            d="M5 7H19M5 12H19M5 17H19"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                        <path
                                            d="M9 4L7 20M17 4L15 20"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="staff_id"
                                    type="text"
                                    name="staff_id"

                                    value="{{ old('staff_id') }}"

                                    class="form-control with-icon
                                        @error('staff_id')
                                            is-invalid
                                        @enderror
                                    "

                                    maxlength="50"

                                    placeholder="e.g. STF-2026-001"

                                    required
                                >

                            </div>


                            <div class="form-note">

                                <svg viewBox="0 0 24 24" fill="none">

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <path
                                        d="M12 10V17"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <circle
                                        cx="12"
                                        cy="7"
                                        r="1"
                                        fill="currentColor"
                                    />

                                </svg>

                                Staff ID must be unique and will be used as the user's login ID.

                            </div>

                            <div
                                id="staffIdLiveError"
                                class="error-text"
                                style="display: none;"
                            ></div>


                            @error('staff_id')

                                <div class="error-text">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Official Email -->

                        <div class="form-group full-width">

                            <div class="form-label-row">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Official Email Address
                                    <span class="required">*</span>
                                </label>

                            </div>


                            <div class="input-wrapper">


                                <div class="input-icon">

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                        <path
                                            d="M4 7L12 13L20 7"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"

                                    value="{{ old('email') }}"

                                    class="form-control with-icon
                                        @error('email')
                                            is-invalid
                                        @enderror
                                    "

                                    maxlength="100"

                                    placeholder="e.g. ahmad@umpsa.edu.my"

                                    required
                                >

                            </div>


                            <div class="form-note">

                                <svg viewBox="0 0 24 24" fill="none">

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <path
                                        d="M12 10V17"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <circle
                                        cx="12"
                                        cy="7"
                                        r="1"
                                        fill="currentColor"
                                    />

                                </svg>

                                The official email address must be unique for each registered user.

                            </div>

                            <div
                                id="emailLiveError"
                                class="error-text"
                                style="display: none;"
                            ></div>


                            @error('email')

                                <div class="error-text">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>


                </div>

            </div>



            <!-- =================================================
                 SECTION 02 — ROLE & DEPARTMENT
            ================================================== -->

            <div class="form-card">


                <div class="form-card-header">


                    <div class="section-number">

                        <div class="section-icon">

                            <svg viewBox="0 0 24 24" fill="none">

                                <path
                                    d="M12 3L19 6V11C19 15.4 16.4 18.6 12 21C7.6 18.6 5 15.4 5 11V6L12 3Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M9 12L11 14L15 10"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </div>

                    </div>


                    <div class="form-card-heading">

                        <div class="form-card-title">
                            Role & Department Assignment
                        </div>

                        <div class="form-card-description">
                            Assign the user's UCPMS access level and applicable PTJ Department.
                        </div>

                    </div>


                    <div class="section-step">
                        Step 02
                    </div>


                </div>


                <div class="form-card-body">


                    <div class="form-grid">


                        <!-- Role -->

                        <div class="form-group">

                            <div class="form-label-row">

                                <label
                                    for="role"
                                    class="form-label"
                                >
                                    System Role
                                    <span class="required">*</span>
                                </label>

                            </div>


                            <select
                                id="role"
                                name="role"

                                class="form-control
                                    @error('role')
                                        is-invalid
                                    @enderror
                                "

                                required
                            >

                                <option value="">
                                    Select System Role
                                </option>


                                <option
                                    value="ptj"
                                    @selected(old('role') === 'ptj')
                                >
                                    PTJ Administrative Staff
                                </option>


                                <option
                                    value="kj"
                                    @selected(old('role') === 'kj')
                                >
                                    Ketua Jabatan (KJ)
                                </option>


                                <option
                                    value="bendahari"
                                    @selected(old('role') === 'bendahari')
                                >
                                    Bendahari
                                </option>

                            </select>


                            <div class="form-note">

                                <svg viewBox="0 0 24 24" fill="none">

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <path
                                        d="M12 10V17"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <circle
                                        cx="12"
                                        cy="7"
                                        r="1"
                                        fill="currentColor"
                                    />

                                </svg>

                                Determines the user's permitted UCPMS functions.

                            </div>


                            @error('role')

                                <div class="error-text">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- PTJ Department -->

                        <div class="form-group">

                            <div class="form-label-row">

                                <label
                                    for="ptj_id"
                                    class="form-label"
                                >
                                    PTJ Department

                                    <span
                                        id="ptjRequiredMark"
                                        class="required"
                                    >
                                        *
                                    </span>

                                </label>

                            </div>


                            <select
                                id="ptj_id"
                                name="ptj_id"

                                class="form-control
                                    @error('ptj_id')
                                        is-invalid
                                    @enderror
                                "
                            >

                                <option
                                    id="ptjPlaceholder"
                                    value=""
                                >
                                    Select a system role first
                                </option>


                                @foreach ($ptjDepartments as $department)

                                    <option
                                        value="{{ $department->ptj_id }}"

                                        @selected(
                                            (string) old('ptj_id')
                                            ===
                                            (string) $department->ptj_id
                                        )
                                    >
                                        {{ $department->department_name }}
                                        ({{ $department->department_code }})
                                    </option>

                                @endforeach

                            </select>


                            <div
                                id="ptjNote"
                                class="form-note"
                            >

                                <svg viewBox="0 0 24 24" fill="none">

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <path
                                        d="M12 10V17"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />

                                    <circle
                                        cx="12"
                                        cy="7"
                                        r="1"
                                        fill="currentColor"
                                    />

                                </svg>

                                <span id="ptjNoteText">
                                    Select a system role first.
                                </span>

                            </div>


                            @error('ptj_id')

                                <div class="error-text">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>


                    <!-- Role Mapping Rule -->

                    <div class="role-rule">


                        <div class="role-rule-icon">

                            <svg viewBox="0 0 24 24" fill="none">

                                <path
                                    d="M12 3L19 6V11C19 15.4 16.4 18.6 12 21C7.6 18.6 5 15.4 5 11V6L12 3Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M12 8V12"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <circle
                                    cx="12"
                                    cy="15.5"
                                    r="1"
                                    fill="currentColor"
                                />

                            </svg>

                        </div>


                        <div>

                            <strong>
                                Role Assignment Rule
                            </strong>

                            PTJ Administrative Staff and Ketua Jabatan must be
                            assigned to a valid PTJ Department. Bendahari is a
                            system-level role and therefore does not require a
                            PTJ Department mapping.

                        </div>


                    </div>


                </div>

            </div>



            <!-- =================================================
                 SECTION 03 — TEMPORARY PASSWORD
            ================================================== -->

            <div class="form-card">


                <div class="form-card-header">


                    <div class="section-number">

                        <div class="section-icon">

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

                                <circle
                                    cx="12"
                                    cy="15"
                                    r="1.5"
                                    fill="currentColor"
                                />

                            </svg>

                        </div>

                    </div>


                    <div class="form-card-heading">

                        <div class="form-card-title">
                            Temporary Login Password
                        </div>

                        <div class="form-card-description">
                            Set the user's initial password for first access to UCPMS.
                        </div>

                    </div>


                    <div class="section-step">
                        Step 03
                    </div>


                </div>


                <div class="form-card-body">


                    <div class="form-grid">


                        <!-- Password -->

                        <div class="form-group">

                            <div class="form-label-row">

                                <label
                                    for="password"
                                    class="form-label"
                                >
                                    Temporary Password
                                    <span class="required">*</span>
                                </label>

                            </div>


                            <div class="password-wrapper">

                                <input
                                    id="password"
                                    type="password"
                                    name="password"

                                    class="form-control
                                        @error('password')
                                            is-invalid
                                        @enderror
                                    "

                                    minlength="8"

                                    autocomplete="new-password"

                                    placeholder="Minimum 8 characters"

                                    required
                                >


                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="password"
                                    aria-label="Show temporary password"
                                    title="Show / Hide Password"
                                >

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <path
                                            d="M2.5 12C4.6 8.6 7.7 7 12 7C16.3 7 19.4 8.6 21.5 12C19.4 15.4 16.3 17 12 17C7.7 17 4.6 15.4 2.5 12Z"
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

                                </button>

                            </div>


                            <div class="password-requirement">

                                <span class="requirement-dot"></span>

                                Minimum 8 characters

                            </div>


                            @error('password')

                                <div class="error-text">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- Confirm Password -->

                        <div class="form-group">

                            <div class="form-label-row">

                                <label
                                    for="password_confirmation"
                                    class="form-label"
                                >
                                    Confirm Temporary Password
                                    <span class="required">*</span>
                                </label>

                            </div>


                            <div class="password-wrapper">

                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"

                                    class="form-control"

                                    minlength="8"

                                    autocomplete="new-password"

                                    placeholder="Re-enter temporary password"

                                    required
                                >


                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-target="password_confirmation"
                                    aria-label="Show password confirmation"
                                    title="Show / Hide Password"
                                >

                                    <svg viewBox="0 0 24 24" fill="none">

                                        <path
                                            d="M2.5 12C4.6 8.6 7.7 7 12 7C16.3 7 19.4 8.6 21.5 12C19.4 15.4 16.3 17 12 17C7.7 17 4.6 15.4 2.5 12Z"
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

                                </button>

                            </div>


                            <div
                                id="passwordMatchMessage"
                                class="password-requirement"
                            >

                                <span class="requirement-dot"></span>

                                Re-enter the same temporary password

                            </div>


                        </div>


                    </div>


                    <!-- Security Note -->

                    <div class="password-info">


                        <div class="password-info-icon">

                            <svg viewBox="0 0 24 24" fill="none">

                                <path
                                    d="M12 3L19 6V11C19 15.4 16.4 18.6 12 21C7.6 18.6 5 15.4 5 11V6L12 3Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M9 12L11 14L15 10"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                            </svg>

                        </div>


                        <div>

                            <strong>
                                Password Security
                            </strong>

                            <br>

                            The temporary password is securely hashed before
                            being stored in the database. The registered user
                            can later change their password through the Manage
                            Profile module.

                        </div>


                    </div>


                </div>

            </div>



            <!-- =================================================
                 ACTION FOOTER
            ================================================== -->

            <div class="form-actions">


                <div class="action-note">

                    <svg viewBox="0 0 24 24" fill="none">

                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <path
                            d="M12 10V17"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <circle
                            cx="12"
                            cy="7"
                            r="1"
                            fill="currentColor"
                        />

                    </svg>

                    Fields marked with * are required.

                </div>


                <div class="action-buttons">


                    <a
                        href="{{ route('admin.users.index') }}"
                        class="btn-cancel"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn-submit"
                    >

                        <svg viewBox="0 0 24 24" fill="none">

                            <circle
                                cx="9"
                                cy="8"
                                r="3"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <path
                                d="M3.5 19C4.2 15.8 6 14.5 9 14.5C10.6 14.5 11.8 14.8 12.8 15.5"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <path
                                d="M17 11V19M13 15H21"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />

                        </svg>

                        Register User

                    </button>


                </div>


            </div>


        </div>

    </form>


</div>

@endsection


@section('page-script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const staffIdInput =
        document.getElementById('staff_id');

    const emailInput =
        document.getElementById('email');

    const staffIdLiveError =
        document.getElementById('staffIdLiveError');

    const emailLiveError =
        document.getElementById('emailLiveError');


    const availabilityUrl =
        @json(route('admin.users.check-availability'));

    const roleSelect =
        document.getElementById('role');

    const ptjSelect =
        document.getElementById('ptj_id');

    const ptjPlaceholder =
        document.getElementById('ptjPlaceholder');

    const ptjNoteText =
        document.getElementById('ptjNoteText');

    const requiredMark =
        document.getElementById('ptjRequiredMark');

    const password =
        document.getElementById('password');

    const passwordConfirmation =
        document.getElementById('password_confirmation');

    const passwordMatchMessage =
        document.getElementById('passwordMatchMessage');


    /*
    |--------------------------------------------------------------------------
    | Role → PTJ Department Behaviour
    |--------------------------------------------------------------------------
    |
    | PTJ / KJ:
    | - Department enabled
    | - Department required
    |
    | Bendahari:
    | - Department disabled
    | - PTJ value cleared
    | - "Not Applicable" shown
    |
    */

    function updatePtjField() {

        const selectedRole =
            roleSelect.value;


        if (
            selectedRole === 'ptj'
            ||
            selectedRole === 'kj'
        ) {

            ptjSelect.disabled =
                false;

            ptjSelect.required =
                true;

            requiredMark.style.display =
                'inline';

            ptjPlaceholder.textContent =
                'Select PTJ Department';

            ptjNoteText.textContent =
                'PTJ Department is required for PTJ and KJ users.';


        } else if (
            selectedRole === 'bendahari'
        ) {

            ptjSelect.value =
                '';

            ptjSelect.disabled =
                true;

            ptjSelect.required =
                false;

            requiredMark.style.display =
                'none';

            ptjPlaceholder.textContent =
                'Not Applicable for Bendahari';

            ptjNoteText.textContent =
                'Not Applicable — Bendahari is a system-level role.';


        } else {

            ptjSelect.value =
                '';

            ptjSelect.disabled =
                true;

            ptjSelect.required =
                false;

            requiredMark.style.display =
                'none';

            ptjPlaceholder.textContent =
                'Select a system role first';

            ptjNoteText.textContent =
                'Select a system role first.';
        }
    }


    roleSelect.addEventListener(
        'change',
        updatePtjField
    );


    /*
    |--------------------------------------------------------------------------
    | Restore State After Laravel Validation Error
    |--------------------------------------------------------------------------
    */

    updatePtjField();


    @if (old('role') === 'ptj' || old('role') === 'kj')

        ptjSelect.value =
            @json((string) old('ptj_id'));

    @endif


    /*
    |--------------------------------------------------------------------------
    | Show / Hide Password
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.password-toggle')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const targetId =
                        this.dataset.target;

                    const target =
                        document.getElementById(targetId);


                    if (
                        target.type === 'password'
                    ) {

                        target.type =
                            'text';

                        this.setAttribute(
                            'aria-label',
                            'Hide password'
                        );

                    } else {

                        target.type =
                            'password';

                        this.setAttribute(
                            'aria-label',
                            'Show password'
                        );
                    }

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Password Match Helper
    |--------------------------------------------------------------------------
    |
    | Laravel backend validation remains the real validation.
    | This only provides immediate UI feedback.
    |
    */

    function updatePasswordMatchMessage() {

        if (
            passwordConfirmation.value === ''
        ) {

            passwordMatchMessage.innerHTML =
                '<span class="requirement-dot"></span> Re-enter the same temporary password';

            passwordMatchMessage.style.color =
                '#79869a';

            return;
        }


        if (
            password.value
            ===
            passwordConfirmation.value
        ) {

            passwordMatchMessage.innerHTML =
                '<span class="requirement-dot" style="background: var(--success);"></span> Passwords match';

            passwordMatchMessage.style.color =
                'var(--success)';

        } else {

            passwordMatchMessage.innerHTML =
                '<span class="requirement-dot" style="background: var(--danger);"></span> Passwords do not match';

            passwordMatchMessage.style.color =
                'var(--danger)';
        }
    }


    password.addEventListener(
        'input',
        updatePasswordMatchMessage
    );


    passwordConfirmation.addEventListener(
        'input',
        updatePasswordMatchMessage
    );


    /*
    |--------------------------------------------------------------------------
    | Live Staff ID Duplicate Check
    |--------------------------------------------------------------------------
    */

    let staffIdCheckTimer = null;


    function showStaffIdError(message) {

        staffIdInput.classList.add('is-invalid');

        staffIdLiveError.textContent =
            message;

        staffIdLiveError.style.display =
            'block';
    }


    function clearStaffIdError() {

        staffIdInput.classList.remove('is-invalid');

        staffIdLiveError.textContent =
            '';

        staffIdLiveError.style.display =
            'none';
    }


    async function checkStaffIdAvailability() {

        const staffId =
            staffIdInput.value.trim();


        clearStaffIdError();


        if (staffId === '') {
            return;
        }


        try {

            const url =
                new URL(
                    availabilityUrl,
                    window.location.origin
                );


            url.searchParams.set(
                'staff_id',
                staffId
            );


            const response =
                await fetch(url, {

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }

                });


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | Prevent Old AJAX Response Affecting New Input
            |--------------------------------------------------------------------------
            */

            if (
                staffIdInput.value.trim()
                !==
                staffId
            ) {
                return;
            }


            if (data.staff_id_exists) {

                showStaffIdError(
                    'This Staff ID is already registered in UCPMS.'
                );

            } else {

                clearStaffIdError();
            }


        } catch (error) {

            /*
            |--------------------------------------------------------------------------
            | Do Not Block Registration if Live Check Fails
            |--------------------------------------------------------------------------
            |
            | Laravel store() validation will still perform the final duplicate
            | validation when the form is submitted.
            |
            */

            console.error(
                'Staff ID availability check failed.',
                error
            );
        }
    }


    staffIdInput.addEventListener(
        'input',
        function () {

            clearTimeout(
                staffIdCheckTimer
            );


            clearStaffIdError();


            staffIdCheckTimer =
                setTimeout(
                    checkStaffIdAvailability,
                    400
                );
        }
    );


    staffIdInput.addEventListener(
        'blur',
        checkStaffIdAvailability
    );


    /*
    |--------------------------------------------------------------------------
    | Live Email Validation
    |--------------------------------------------------------------------------
    */

    let emailCheckTimer = null;


    function showEmailError(message) {

        emailInput.classList.add('is-invalid');

        emailLiveError.textContent =
            message;

        emailLiveError.style.display =
            'block';
    }


    function clearEmailError() {

        emailInput.classList.remove('is-invalid');

        emailLiveError.textContent =
            '';

        emailLiveError.style.display =
            'none';
    }


    async function validateEmailField() {

        const email =
            emailInput.value.trim();


        clearEmailError();


        if (email === '') {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Email Format Validation
        |--------------------------------------------------------------------------
        */

        if (emailInput.validity.typeMismatch) {

            showEmailError(
                'Please enter a valid email address.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Email Check
        |--------------------------------------------------------------------------
        */

        try {

            const url =
                new URL(
                    availabilityUrl,
                    window.location.origin
                );


            url.searchParams.set(
                'email',
                email
            );


            const response =
                await fetch(url, {

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }

                });


            if (!response.ok) {
                return;
            }


            const data =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | Ignore Response if User Has Already Changed the Input
            |--------------------------------------------------------------------------
            */

            if (
                emailInput.value.trim()
                !==
                email
            ) {
                return;
            }


            if (data.email_exists) {

                showEmailError(
                    'This email address is already registered in UCPMS.'
                );

            } else {

                clearEmailError();
            }


        } catch (error) {

            console.error(
                'Email availability check failed.',
                error
            );
        }
    }


    emailInput.addEventListener(
        'input',
        function () {

            clearTimeout(
                emailCheckTimer
            );


            const email =
                emailInput.value.trim();


            clearEmailError();


            /*
            |--------------------------------------------------------------------------
            | Immediately Show Invalid Email Format
            |--------------------------------------------------------------------------
            */

            if (
                email !== ''
                &&
                emailInput.validity.typeMismatch
            ) {

                showEmailError(
                    'Please enter a valid email address.'
                );

                return;
            }


            if (email !== '') {

                emailCheckTimer =
                    setTimeout(
                        validateEmailField,
                        400
                    );
            }
        }
    );


    emailInput.addEventListener(
        'blur',
        validateEmailField
    );

});

</script>

@endsection