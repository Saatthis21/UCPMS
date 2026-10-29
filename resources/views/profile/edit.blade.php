@extends('layouts.ucpms')

@section('title', 'Secure Profile Update')

@section('page-style')

.profile-page-header {
    margin-bottom: 24px;
}

.profile-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 10px;
    font-size: 14px;
    color: #718096;
}

.profile-breadcrumb a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.profile-heading-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.profile-heading h1 {
    margin: 0;
    color: #17233c;
    font-size: 28px;
    font-weight: 700;
}

.profile-heading p {
    margin: 7px 0 0;
    color: #718096;
    font-size: 14px;
}

.profile-header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-close-btn {
    width: 46px;
    height: 46px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    border: 1px solid #dce3ed;
    border-radius: 10px;
    color: #34435d;
    text-decoration: none;
    font-size: 22px;
    transition: 0.2s ease;
}

.profile-close-btn:hover {
    background: #f7f9fc;
    color: #17233c;
}

.profile-top-save {
    min-height: 46px;
    padding: 0 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #ffffff;
    border: 1px solid var(--primary);
    border-radius: 10px;
    color: var(--primary);
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s ease;
}

.profile-top-save:hover {
    background: var(--primary-soft);
}

.profile-update-card {
    background: #ffffff;
    border: 1px solid #e4eaf2;
    border-radius: 16px;
    box-shadow: 0 6px 22px rgba(23, 35, 60, 0.06);
    padding: 28px 30px;
}

.profile-section {
    position: relative;
}

.profile-section + .profile-section {
    margin-top: 27px;
    padding-top: 27px;
    border-top: 1px solid #e7ecf3;
}

.profile-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.profile-section-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: #ffffff;
    flex-shrink: 0;
}

.profile-section-icon svg {
    width: 20px;
    height: 20px;
}

.profile-section-title {
    margin: 0;
    color: #17233c;
    font-size: 18px;
    font-weight: 700;
}

.profile-section-note {
    margin-left: 6px;
    color: #8793a7;
    font-size: 13px;
    font-weight: 400;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px 28px;
}

.profile-grid-four {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

.profile-form-group {
    min-width: 0;
}

.profile-form-group.full-width {
    grid-column: 1 / -1;
}

.profile-label {
    display: block;
    margin-bottom: 8px;
    color: #24324a;
    font-size: 13px;
    font-weight: 600;
}

.required-mark {
    color: #dc2626;
}

.profile-input,
.profile-readonly {
    width: 100%;
    min-height: 48px;
    border: 1px solid #dce3ed;
    border-radius: 9px;
    padding: 12px 14px;
    font-size: 14px;
    color: #24324a;
    background: #ffffff;
    outline: none;
    box-sizing: border-box;
    transition: 0.2s ease;
}

.profile-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}

.profile-readonly {
    background: #f6f8fb;
    color: #637089;
    cursor: not-allowed;
}

.readonly-wrap {
    position: relative;
}

.readonly-wrap .profile-readonly {
    padding-right: 40px;
}

.readonly-lock {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #8895aa;
}

.password-wrap {
    position: relative;
}

.password-wrap .profile-input {
    padding-right: 46px;
}

.password-toggle {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: #66748c;
    cursor: pointer;
    padding: 4px;
}

.password-toggle svg {
    width: 19px;
    height: 19px;
}

.profile-error {
    margin-top: 6px;
    color: #dc2626;
    font-size: 12px;
    font-weight: 500;
}

.profile-main-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 280px;
    gap: 38px;
}

.profile-form-side {
    min-width: 0;
}

.profile-security-side {
    display: flex;
    align-items: center;
    justify-content: center;
}

.security-illustration {
    width: 230px;
    min-height: 230px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.security-card {
    width: 190px;
    height: 126px;
    margin-top: 48px;
    border: 1px solid #d9e8fb;
    border-radius: 16px;
    background: linear-gradient(
        145deg,
        #ffffff,
        #f5faff
    );
    box-shadow: 0 12px 30px rgba(23, 105, 232, 0.08);
    display: flex;
    align-items: center;
    padding: 23px;
    gap: 18px;
    box-sizing: border-box;
}

.security-person {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: var(--primary);
    position: relative;
}

.security-person::after {
    content: "";
    position: absolute;
    width: 72px;
    height: 34px;
    left: 50%;
    transform: translateX(-50%);
    bottom: -27px;
    border-radius: 36px 36px 12px 12px;
    background: var(--primary);
}

.security-lines {
    flex: 1;
}

.security-lines span {
    display: block;
    height: 9px;
    margin-bottom: 10px;
    border-radius: 20px;
    background: #cfe2fa;
}

.security-lines span:first-child {
    width: 65%;
    background: var(--primary);
    opacity: 0.8;
}

.security-shield {
    position: absolute;
    top: 9px;
    left: 50%;
    transform: translateX(-50%);
    width: 68px;
    height: 76px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-soft);
    border: 4px solid rgba(23, 105, 232, 0.25);
    border-radius: 50% 50% 45% 45%;
    color: var(--primary);
    z-index: 2;
}

.security-shield svg {
    width: 29px;
    height: 29px;
}

.profile-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 28px;
}

.reset-btn,
.save-btn {
    min-height: 46px;
    padding: 0 23px;
    border-radius: 9px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: 0.2s ease;
}

.reset-btn {
    background: #ffffff;
    color: #34435d;
    border: 1px solid #d9e0ea;
}

.reset-btn:hover {
    background: #f7f9fc;
}

.save-btn {
    border: 1px solid var(--primary);
    background: var(--primary);
    color: #ffffff;
}

.save-btn:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

.profile-success {
    margin-bottom: 20px;
    padding: 13px 16px;
    background: #ecfdf3;
    border: 1px solid #bbf7d0;
    border-radius: 9px;
    color: #166534;
    font-size: 14px;
    font-weight: 600;
}

@media (max-width: 1050px) {
    .profile-main-layout {
        grid-template-columns: 1fr;
    }

    .profile-security-side {
        display: none;
    }

    .profile-grid-four {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 700px) {
    .profile-heading-row {
        flex-direction: column;
    }

    .profile-header-actions {
        width: 100%;
    }

    .profile-top-save {
        flex: 1;
    }

    .profile-update-card {
        padding: 22px 18px;
    }

    .profile-grid,
    .profile-grid-four {
        grid-template-columns: 1fr;
    }

    .profile-actions {
        flex-direction: column-reverse;
    }

    .reset-btn,
    .save-btn {
        width: 100%;
    }
}

@endsection


@section('content')

@php
    $roleLabel = match ($user->role) {
        'ptj' => 'PTJ Administrative Staff',
        'kj' => 'Ketua Jabatan (KJ)',
        'bendahari' => 'Bendahari Officer',
        'admin' => 'System Administrator',
        default => ucfirst($user->role),
    };

    $departmentName =
        $user->ptjDepartment?->department_name ?? 'Not Applicable';
@endphp


<div class="profile-page-header">

    <div class="profile-breadcrumb">

        <a href="{{ route($user->role . '.dashboard') }}">
            Dashboard
        </a>

        <span>›</span>

        <a href="{{ route('profile.show') }}">
            Profile
        </a>

        <span>›</span>

        <span>Edit</span>

    </div>


    <div class="profile-heading-row">

        <div class="profile-heading">

            <h1>Secure Profile Update</h1>

            <p>
                Manage your account information and security settings.
            </p>

        </div>


        <div class="profile-header-actions">

            <a
                href="{{ route('profile.show') }}"
                class="profile-close-btn"
                title="Back to Profile"
            >
                ×
            </a>

            <button
                type="submit"
                form="profile-update-form"
                class="profile-top-save"
            >
                ✓
                Save Changes & Security Update
            </button>

        </div>

    </div>

</div>


@if (session('status') === 'profile-updated')

    <div class="profile-success">
        Profile information updated successfully.
    </div>

@endif


<form
    id="profile-update-form"
    method="POST"
    action="{{ route('profile.update') }}"
>

    @csrf
    @method('PATCH')


    <div class="profile-update-card">

        <div class="profile-main-layout">

            <div class="profile-form-side">


                {{-- OFFICIAL INFORMATION --}}
                <section class="profile-section">

                    <div class="profile-section-heading">

                        <div class="profile-section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M20 21a8 8 0 0 0-16 0"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>

                        </div>

                        <h2 class="profile-section-title">
                            Official Information
                        </h2>

                    </div>


                    <div class="profile-grid profile-grid-four">

                        <div class="profile-form-group">

                            <label class="profile-label">
                                Full Name
                            </label>

                            <div class="readonly-wrap">

                                <input
                                    type="text"
                                    class="profile-readonly"
                                    value="{{ $user->user_name }}"
                                    readonly
                                >

                                <span class="readonly-lock">
                                    🔒
                                </span>

                            </div>

                        </div>


                        <div class="profile-form-group">

                            <label class="profile-label">
                                Staff ID
                            </label>

                            <div class="readonly-wrap">

                                <input
                                    type="text"
                                    class="profile-readonly"
                                    value="{{ $user->staff_id }}"
                                    readonly
                                >

                                <span class="readonly-lock">
                                    🔒
                                </span>

                            </div>

                        </div>


                        <div class="profile-form-group">

                            <label class="profile-label">
                                Role
                            </label>

                            <div class="readonly-wrap">

                                <input
                                    type="text"
                                    class="profile-readonly"
                                    value="{{ $roleLabel }}"
                                    readonly
                                >

                                <span class="readonly-lock">
                                    🔒
                                </span>

                            </div>

                        </div>


                        <div class="profile-form-group">

                            <label class="profile-label">
                                Department
                            </label>

                            <div class="readonly-wrap">

                                <input
                                    type="text"
                                    class="profile-readonly"
                                    value="{{ $departmentName }}"
                                    readonly
                                >

                                <span class="readonly-lock">
                                    🔒
                                </span>

                            </div>

                        </div>

                    </div>

                </section>



                {{-- CONTACT DETAILS --}}
                <section class="profile-section">

                    <div class="profile-section-heading">

                        <div class="profile-section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="2"
                                />
                                <path d="m3 7 9 6 9-6"/>
                            </svg>

                        </div>

                        <h2 class="profile-section-title">
                            Contact Details
                        </h2>

                    </div>


                    <div class="profile-grid">

                        <div class="profile-form-group">

                            <label
                                for="email"
                                class="profile-label"
                            >
                                Email Address
                                <span class="required-mark">*</span>
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="profile-input"
                                value="{{ old('email', $user->email) }}"
                                required
                            >

                            @error('email')
                                <div class="profile-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="profile-form-group">

                            <label
                                for="user_phone_number"
                                class="profile-label"
                            >
                                Phone Number
                            </label>

                            <input
                                id="user_phone_number"
                                name="user_phone_number"
                                type="text"
                                class="profile-input"
                                value="{{ old(
                                    'user_phone_number',
                                    $user->user_phone_number
                                ) }}"
                                placeholder="Enter phone number"
                            >

                            @error('user_phone_number')
                                <div class="profile-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </section>



                {{-- PASSWORD RESET --}}
                <section class="profile-section">

                    <div class="profile-section-heading">

                        <div class="profile-section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect
                                    x="5"
                                    y="11"
                                    width="14"
                                    height="10"
                                    rx="2"
                                />
                                <path
                                    d="M8 11V7a4 4 0 0 1 8 0v4"
                                />
                            </svg>

                        </div>


                        <h2 class="profile-section-title">
                            Password Reset

                            <span class="profile-section-note">
                                — leave blank to keep current password
                            </span>
                        </h2>

                    </div>


                    <div class="profile-grid">


                        <div class="profile-form-group full-width">

                            <label
                                for="current_password"
                                class="profile-label"
                            >
                                Current Password
                            </label>

                            <div class="password-wrap">

                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    class="profile-input"
                                    placeholder="Enter current password"
                                    autocomplete="current-password"
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-password-target="current_password"
                                    aria-label="Show or hide password"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                        />
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>

                                </button>

                            </div>

                            @error('current_password')
                                <div class="profile-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="profile-form-group">

                            <label
                                for="password"
                                class="profile-label"
                            >
                                New Password
                            </label>

                            <div class="password-wrap">

                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="profile-input"
                                    placeholder="Enter new password"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-password-target="password"
                                    aria-label="Show or hide password"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                        />
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>

                                </button>

                            </div>

                            @error('password')
                                <div class="profile-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="profile-form-group">

                            <label
                                for="password_confirmation"
                                class="profile-label"
                            >
                                Confirm New Password
                            </label>

                            <div class="password-wrap">

                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="profile-input"
                                    placeholder="Re-enter new password"
                                    autocomplete="new-password"
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    data-password-target="password_confirmation"
                                    aria-label="Show or hide password"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                                        />
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>

                                </button>

                            </div>

                        </div>

                    </div>

                </section>


            </div>



            {{-- RIGHT SECURITY ILLUSTRATION --}}
            <aside class="profile-security-side">

                <div class="security-illustration">

                    <div class="security-shield">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <rect
                                x="7"
                                y="10"
                                width="10"
                                height="9"
                                rx="2"
                            />

                            <path
                                d="M9 10V7a3 3 0 0 1 6 0v3"
                            />
                        </svg>

                    </div>


                    <div class="security-card">

                        <div class="security-person"></div>

                        <div class="security-lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                    </div>

                </div>

            </aside>

        </div>



        <div class="profile-actions">

            <button
                type="reset"
                class="reset-btn"
            >
                Reset Fields
            </button>

            <button
                type="submit"
                class="save-btn"
            >
                Save Changes
            </button>

        </div>

    </div>

</form>

@endsection



@section('page-script')

<script>

    document.querySelectorAll('.password-toggle')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const targetId =
                    this.getAttribute('data-password-target');

                const input =
                    document.getElementById(targetId);

                if (!input) {
                    return;
                }

                input.type =
                    input.type === 'password'
                        ? 'text'
                        : 'password';
            });

        });

</script>

@endsection