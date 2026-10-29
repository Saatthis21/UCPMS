@extends('layouts.ucpms')

@section('title', 'My Profile')

@section('page-style')

.profile-header {
    margin-bottom: 24px;
}

.profile-breadcrumb {
    font-size: 14px;
    color: #718096;
    margin-bottom: 10px;
}

.profile-breadcrumb a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.profile-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.profile-title {
    margin: 0;
    color: #17233c;
    font-size: 28px;
    font-weight: 700;
}

.profile-subtitle {
    margin-top: 6px;
    color: #718096;
    font-size: 14px;
}

.edit-profile-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 20px;
    background: var(--primary);
    color: #ffffff;
    border-radius: 9px;
    text-decoration: none;
    font-weight: 600;
    border: none;
    transition: 0.2s ease;
}

.edit-profile-btn:hover {
    background: var(--primary-dark);
    color: #ffffff;
}

.profile-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e8edf5;
    box-shadow: 0 5px 20px rgba(23, 35, 60, 0.06);
    overflow: hidden;
}

.profile-card-header {
    padding: 24px 28px;
    border-bottom: 1px solid #edf1f6;
}

.profile-card-header h2 {
    margin: 0;
    color: #17233c;
    font-size: 19px;
    font-weight: 700;
}

.profile-card-header p {
    margin: 6px 0 0;
    color: #718096;
    font-size: 14px;
}

.profile-content {
    padding: 30px 28px;
}

.profile-section-title {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 18px;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 22px 30px;
}

.profile-field {
    min-width: 0;
}

.profile-label {
    display: block;
    font-size: 13px;
    color: #718096;
    font-weight: 600;
    margin-bottom: 7px;
}

.profile-value {
    min-height: 48px;
    padding: 13px 15px;
    background: #f7f9fc;
    border: 1px solid #e3e8f0;
    border-radius: 9px;
    color: #24324a;
    font-size: 15px;
    font-weight: 500;
    word-break: break-word;
}

.profile-divider {
    height: 1px;
    background: #edf1f6;
    margin: 30px 0;
}

.profile-status {
    margin-bottom: 20px;
    padding: 13px 16px;
    background: #ecfdf3;
    color: #166534;
    border: 1px solid #bbf7d0;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .profile-title-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .profile-grid {
        grid-template-columns: 1fr;
    }

    .edit-profile-btn {
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

    $departmentName = $user->ptjDepartment?->department_name ?? 'Not Applicable';
@endphp


<div class="profile-header">

    <div class="profile-breadcrumb">
        <a href="{{ route($user->role . '.dashboard') }}">
            Dashboard
        </a>
        &nbsp;›&nbsp; Profile
    </div>

    <div class="profile-title-row">

        <div>
            <h1 class="profile-title">My Profile</h1>

            <div class="profile-subtitle">
                View your UCPMS account and contact information.
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
            Edit Profile
        </a>

    </div>

</div>


@if (session('status') === 'profile-updated')
    <div class="profile-status">
        Profile information updated successfully.
    </div>
@endif


<div class="profile-card">

    <div class="profile-card-header">
        <h2>Account Information</h2>

        <p>
            Your official staff and system access information.
        </p>
    </div>


    <div class="profile-content">

        <div class="profile-section-title">
            User Information
        </div>


        <div class="profile-grid">

            <div class="profile-field">
                <span class="profile-label">Full Name</span>

                <div class="profile-value">
                    {{ $user->user_name }}
                </div>
            </div>


            <div class="profile-field">
                <span class="profile-label">Staff ID</span>

                <div class="profile-value">
                    {{ $user->staff_id }}
                </div>
            </div>


            <div class="profile-field">
                <span class="profile-label">Role</span>

                <div class="profile-value">
                    {{ $roleLabel }}
                </div>
            </div>


            <div class="profile-field">
                <span class="profile-label">Department</span>

                <div class="profile-value">
                    {{ $departmentName }}
                </div>
            </div>

        </div>


        <div class="profile-divider"></div>


        <div class="profile-section-title">
            Contact Details
        </div>


        <div class="profile-grid">

            <div class="profile-field">
                <span class="profile-label">Email Address</span>

                <div class="profile-value">
                    {{ $user->email }}
                </div>
            </div>


            <div class="profile-field">
                <span class="profile-label">Phone Number</span>

                <div class="profile-value">
                    {{ $user->user_phone_number ?: 'Not Provided' }}
                </div>
            </div>

        </div>

    </div>

</div>

@endsection