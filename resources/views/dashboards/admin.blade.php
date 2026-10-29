@extends('layouts.ucpms')

@section('title', 'Admin Dashboard - UCPMS')


@section('page-style')

    .admin-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .admin-card {
        min-height: 190px;
        padding: 23px;
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid #ebe7f3;
        box-shadow: 0 10px 30px rgba(80, 40, 140, 0.05);
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .admin-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px rgba(80, 40, 140, 0.08);
    }

    .admin-card-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
    }

    .admin-card-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .admin-card-icon svg {
        width: 27px;
        height: 27px;
    }

    .admin-card-icon.purple {
        color: #6d28d9;
        background: #f1e8ff;
    }

    .admin-card-icon.blue {
        color: #2563eb;
        background: #e9f0ff;
    }

    .admin-card-icon.green {
        color: #079455;
        background: #e8f7ef;
    }

    .admin-card-icon.orange {
        color: #ea580c;
        background: #fff0e6;
    }

    .admin-card-label {
        font-size: 14px;
        line-height: 1.4;
        color: #536078;
    }

    .admin-card-value {
        margin-top: 4px;
        font-size: 33px;
        line-height: 1;
        font-weight: 800;
        color: #17233c;
    }

    .admin-card-subtext {
        margin-top: 10px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf0f5;
        font-size: 12px;
        color: #7c8799;
    }

    .admin-card-footer {
        margin-top: 13px;
        font-size: 12px;
        font-weight: 700;
    }

    .admin-card-footer a {
        color: inherit;
        text-decoration: none;
    }

    .admin-card-footer.purple { color: #6d28d9; }
    .admin-card-footer.blue { color: #2563eb; }
    .admin-card-footer.green { color: #079455; }
    .admin-card-footer.orange { color: #ea580c; }

    .admin-panel {
        background: #ffffff;
        border: 1px solid #ebe7f3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(80, 40, 140, 0.05);
        margin-bottom: 24px;
    }

    .admin-panel-header {
        min-height: 68px;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .admin-panel-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-panel-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3ecff;
        color: #6d28d9;
    }

    .admin-panel-icon svg {
        width: 18px;
        height: 18px;
    }

    .admin-panel-title {
        font-size: 17px;
        font-weight: 750;
        color: #17233c;
    }

    .log-label {
        padding: 5px 8px;
        border-radius: 7px;
        background: #f4efff;
        color: #7c3aed;
        font-size: 10px;
        font-weight: 700;
    }

    .admin-panel-note {
        color: #7c8799;
        font-size: 12px;
        font-weight: 600;
    }

    .admin-table-wrapper {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        min-width: 1000px;
        border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
        padding: 16px 21px;
        text-align: left;
        border-bottom: 1px solid #edf0f5;
        font-size: 12px;
        vertical-align: top;
    }

    .admin-table th {
        background: #fcfbfe;
        color: #6a758a;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .admin-table td {
        color: #364259;
    }

    .audit-user {
        font-weight: 650;
        color: #26334d;
    }

    .audit-subtext {
        display: block;
        margin-top: 3px;
        color: #8a94a6;
        font-size: 10px;
        font-weight: 400;
    }

    .audit-action {
        font-weight: 650;
        color: #39465e;
    }

    .audit-description {
        max-width: 430px;
        line-height: 1.45;
        color: #647087;
    }

    .reference-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 9px;
        border-radius: 999px;
        background: #f4efff;
        color: #6d28d9;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .empty-state {
        padding: 34px 20px !important;
        text-align: center !important;
        color: #8a94a6 !important;
    }

    .system-status-panel {
        display: grid;
        grid-template-columns: 1.4fr repeat(4, 0.65fr) auto;
        align-items: center;
        gap: 0;
        padding: 20px;
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid #ebe7f3;
        box-shadow: 0 10px 30px rgba(80, 40, 140, 0.05);
    }

    .system-status-main {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .security-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6d28d9;
        background: #f1e8ff;
    }

    .security-icon svg {
        width: 27px;
        height: 27px;
    }

    .security-title {
        font-size: 14px;
        font-weight: 750;
        color: #17233c;
    }

    .security-description {
        margin-top: 4px;
        font-size: 11px;
        color: #7b8799;
    }

    .system-item {
        padding: 0 18px;
        border-left: 1px solid #ececf2;
    }

    .system-item-title {
        font-size: 11px;
        color: #617088;
    }

    .system-health {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 11px;
        color: #4f5d73;
    }

    .health-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #0fa958;
    }

    .health-dot.unavailable {
        background: #dc2626;
    }

    .system-status-action {
        margin-left: 15px;
        padding: 11px 15px;
        border: none;
        border-radius: 10px;
        background: #f3ecff;
        color: #6d28d9;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
    }

    .dashboard-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        padding: 10px 2px 5px;
        font-size: 11px;
        color: #7c889d;
    }

    @media (max-width: 1150px) {
        .admin-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .system-status-panel {
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .system-item {
            border-left: none;
            padding: 0;
        }
    }

    @media (max-width: 650px) {
        .admin-summary-grid {
            grid-template-columns: 1fr;
        }

        .admin-panel-header {
            align-items: flex-start;
            flex-direction: column;
            padding-top: 18px;
            padding-bottom: 18px;
        }

        .system-status-panel {
            grid-template-columns: 1fr;
        }

        .system-status-action {
            margin-left: 0;
        }

        .dashboard-footer {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
    }

@endsection


@section('content')

<div class="welcome-section">

    <div>

        <div class="welcome-small">
            {{ $greeting }},
        </div>

        <h1 class="welcome-title">
            {{ auth()->user()->user_name }} 👋
        </h1>

        <div class="welcome-description">
            System Administrator
        </div>

    </div>

    <div class="welcome-decoration">
        <div class="welcome-circle-one"></div>
        <div class="welcome-circle-two"></div>
        <div class="welcome-circle-three"></div>
    </div>

</div>


<div class="admin-summary-grid">

    <div class="admin-card">

        <div class="admin-card-top">

            <div class="admin-card-icon purple">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.8"/>
                    <circle cx="16.5" cy="9" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M3.5 19C4.3 15.6 6.1 14 9 14C11.9 14 13.7 15.6 14.5 19" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M14 14.5C17.5 14.2 19.5 15.5 20.2 18.5" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </div>

            <div class="admin-card-label">
                Total Registered<br>
                System Users
            </div>

        </div>

        <div class="admin-card-value">
            {{ number_format($totalUsers) }}
        </div>

        <div class="admin-card-subtext">
            All registered UCPMS accounts
        </div>

        <div class="admin-card-footer purple">
            <a href="{{ route('admin.users.index') }}">
                View user directory →
            </a>
        </div>

    </div>


    <div class="admin-card">

        <div class="admin-card-top">

            <div class="admin-card-icon blue">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L19 6V11C19 15.5 16.2 19.4 12 21C7.8 19.4 5 15.5 5 11V6L12 3Z" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M9 12L11 14L15.5 9.5" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </div>

            <div class="admin-card-label">
                Active Accounts
            </div>

        </div>

        <div class="admin-card-value">
            {{ number_format($activeAccounts) }}
        </div>

        <div class="admin-card-subtext">
            Currently allowed to access UCPMS
        </div>

        <div class="admin-card-footer blue">
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}">
                View active accounts →
            </a>
        </div>

    </div>


    <div class="admin-card">

        <div class="admin-card-top">

            <div class="admin-card-icon green">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M3.5 19C4.2 15.8 6 14.5 9 14.5C10.3 14.5 11.4 14.8 12.4 15.4" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M17 11V19M13 15H21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="admin-card-label">
                New Users<br>
                This Month
            </div>

        </div>

        <div class="admin-card-value">
            {{ number_format($newUsersThisMonth) }}
        </div>

        <div class="admin-card-subtext">
            Accounts registered in {{ now()->format('F Y') }}
        </div>

        <div class="admin-card-footer green">
            User registration activity
        </div>

    </div>


    <div class="admin-card">

        <div class="admin-card-top">

            <div class="admin-card-icon orange">
                <svg viewBox="0 0 24 24" fill="none">
                    <rect x="4" y="4" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="14" y="4" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="4" y="14" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="14" y="14" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </div>

            <div class="admin-card-label">
                System Modules
            </div>

        </div>

        <div class="admin-card-value">
            {{ $systemModules }}
        </div>

        <div class="admin-card-subtext">
            Documented UCPMS modules
        </div>

        <div class="admin-card-footer orange">
            UCPMS-UC-100 to UCPMS-UC-700
        </div>

    </div>

</div>


<div class="admin-panel">

    <div class="admin-panel-header">

        <div class="admin-panel-heading">

            <div class="admin-panel-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L19 6V11C19 15.5 16.2 19.4 12 21C7.8 19.4 5 15.5 5 11V6L12 3Z" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </div>

            <div class="admin-panel-title">
                Recent System Audit Trail
            </div>

            <span class="log-label">
                Live Data
            </span>

        </div>

        <span class="admin-panel-note">
            Latest 5 recorded activities
        </span>

    </div>


    <div class="admin-table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Reference</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($recentAuditLogs as $auditLog)

                    <tr>

                        <td>
                            {{ \Illuminate\Support\Carbon::parse($auditLog->created_at)->format('d M Y H:i:s') }}
                        </td>

                        <td class="audit-user">

                            {{ $auditLog->actor_name ?? 'System / Unknown User' }}

                            @if ($auditLog->actor_staff_id)
                                <span class="audit-subtext">
                                    {{ $auditLog->actor_staff_id }}
                                </span>
                            @elseif ($auditLog->actor_email)
                                <span class="audit-subtext">
                                    {{ $auditLog->actor_email }}
                                </span>
                            @endif

                        </td>

                        <td class="audit-action">
                            {{ $auditLog->action }}
                        </td>

                        <td class="audit-description">
                            {{ $auditLog->description }}
                        </td>

                        <td>

                            @if ($auditLog->procurement_request_id)

                                <span class="reference-badge">
                                    REQ-{{ str_pad($auditLog->procurement_request_id, 4, '0', STR_PAD_LEFT) }}
                                </span>

                            @else

                                <span class="reference-badge">
                                    System
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="empty-state">
                            No audit log records are available yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<div class="system-status-panel">

    <div class="system-status-main">

        <div class="security-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M12 3L19 6V11C19 15.5 16.2 19.4 12 21C7.8 19.4 5 15.5 5 11V6L12 3Z" stroke="currentColor" stroke-width="1.8"/>
                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="1.8"/>
            </svg>
        </div>

        <div>
            <div class="security-title">
                System Security & Status
            </div>

            <div class="security-description">
                Current UCPMS runtime status
            </div>
        </div>

    </div>


    @php
        $statusItems = [
            'Database' => $systemStatus['database'],
            'Web Server' => $systemStatus['web_server'],
            'Application' => $systemStatus['application'],
            'Authentication' => $systemStatus['authentication'],
        ];
    @endphp

    @foreach ($statusItems as $label => $value)

        <div class="system-item">

            <div class="system-item-title">
                {{ $label }}
            </div>

            <div class="system-health">

                <span
                    class="health-dot {{ in_array($value, ['Unavailable', 'Disconnected'], true) ? 'unavailable' : '' }}"
                ></span>

                {{ $value }}

            </div>

        </div>

    @endforeach


    <a
        href="{{ route('admin.users.index') }}"
        class="system-status-action"
    >
        User Management →
    </a>

</div>


<div class="dashboard-footer">

    <div>
        © 2026 UCPMS. All rights reserved.
    </div>

    <div>
        Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA)
    </div>

</div>

@endsection