@extends('layouts.ucpms')

@section('title', 'KJ Dashboard - UCPMS')

@section('page-style')

    .kj-dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1.1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .kj-card {
        background: #ffffff;
        border: 1px solid #eceff4;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(80, 20, 30, 0.06);
        min-height: 165px;
        position: relative;
        overflow: hidden;
    }

    .kj-card-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
    }

    .kj-card-icon.red {
        background: #fdebed;
        color: #c9152d;
    }

    .kj-card-icon.green {
        background: #e4f7ee;
        color: #119963;
    }

    .kj-card-icon svg {
        width: 26px;
        height: 26px;
    }

    .kj-card-label {
        font-size: 14px;
        color: #58657d;
        margin-bottom: 6px;
    }

    .kj-card-value {
        font-size: 32px;
        font-weight: 800;
        color: #17233c;
        margin-bottom: 5px;
        letter-spacing: -0.5px;
    }

    .kj-card-value.red {
        color: #c9152d;
    }

    .kj-card-value.green {
        color: #119963;
    }

    .kj-card-subtext {
        font-size: 12px;
        color: #7d899c;
    }

    .kj-action-card {
        background: linear-gradient(135deg, #bd1028, #df213a);
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .kj-action-card::after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        right: -55px;
        bottom: -75px;
        background: rgba(255, 255, 255, 0.05);
    }

    .kj-action-content {
        display: flex;
        align-items: center;
        gap: 18px;
        position: relative;
        z-index: 2;
    }

    .kj-action-icon {
        width: 62px;
        height: 62px;
        border-radius: 17px;
        background: #ffffff;
        color: #c9152d;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .kj-action-icon svg {
        width: 29px;
        height: 29px;
    }

    .kj-action-title {
        font-size: 17px;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .kj-action-description {
        font-size: 13px;
        line-height: 1.45;
        opacity: 0.9;
    }

    .kj-action-arrow {
        width: 44px;
        height: 44px;
        border: 1px solid rgba(255,255,255,0.75);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        position: relative;
        z-index: 2;
    }

    .history-panel {
        background: #ffffff;
        border: 1px solid #eceff4;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(80, 20, 30, 0.06);
        overflow: hidden;
    }

    .history-header {
        min-height: 65px;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #edf0f4;
    }

    .history-title {
        font-size: 18px;
        font-weight: 750;
        color: #17233c;
    }

    .history-link {
        color: #c9152d;
        font-size: 13px;
        font-weight: 650;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .history-table-wrapper {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .history-table th,
    .history-table td {
        padding: 16px 22px;
        text-align: left;
        border-bottom: 1px solid #edf0f4;
        font-size: 13px;
    }

    .history-table th {
        background: #fbfcfe;
        color: #5c687f;
        font-weight: 700;
    }

    .history-request-id {
        color: #c9152d;
        font-weight: 700;
    }

    .history-title-text {
        font-weight: 600;
        color: #26324a;
    }

    .decision-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 650;
    }

    .decision-badge.approved {
        background: #e6f7ef;
        color: #0d9964;
    }

    .decision-badge.rejected {
        background: #fdecef;
        color: #cc2337;
    }

    .decision-badge.returned {
        background: #f1e8ff;
        color: #6d28d9;
    }

    .decision-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .history-action-button {
        width: 36px;
        height: 36px;
        border: 1px solid #dfe5ed;
        background: #ffffff;
        color: #34425b;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .history-action-button svg {
        width: 18px;
        height: 18px;
    }

    .dashboard-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        padding: 10px 2px 5px;
        font-size: 11px;
        color: #7c889d;
    }

    @media (max-width: 1000px) {
        .kj-dashboard-grid {
            grid-template-columns: 1fr 1fr;
        }

        .kj-action-card {
            grid-column: span 2;
        }
    }

    @media (max-width: 650px) {
        .kj-dashboard-grid {
            grid-template-columns: 1fr;
        }

        .kj-action-card {
            grid-column: span 1;
        }

        .kj-action-content {
            align-items: flex-start;
        }

        .history-header,
        .dashboard-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding-top: 16px;
            padding-bottom: 16px;
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
            Ketua Jabatan (KJ)
            @if ($department)
                - {{ $department->department_code ?? $department->department_name }}
            @endif
        </div>

    </div>

    <div class="welcome-decoration">
        <div class="welcome-circle-one"></div>
        <div class="welcome-circle-two"></div>
        <div class="welcome-circle-three"></div>
    </div>

</div>


<div class="kj-dashboard-grid">

    <div class="kj-card">

        <div class="kj-card-icon red">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8" />
                <path d="M12 7V12L15 14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </div>

        <div class="kj-card-label">My Pending Approvals Queue</div>

        <div class="kj-card-value red">
            {{ number_format($pendingApprovalCount) }}
        </div>

        <div class="kj-card-subtext">
            Requests from {{ $department->department_name ?? 'your PTJ' }} to review
        </div>

    </div>


    <div class="kj-card">

        <div class="kj-card-icon green">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8" />
                <path d="M8.5 12L10.8 14.3L15.7 9.4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <div class="kj-card-label">Decisions (Last 30 Days)</div>

        <div class="kj-card-value green">
            {{ number_format($decisionsLast30Days) }}
        </div>

        <div class="kj-card-subtext">
            Approved, Rejected or Returned by you
        </div>

    </div>


    <a
        href="{{ route('kj.approvals.index') }}"
        class="kj-card kj-action-card"
        style="text-decoration:none;"
    >
        <div class="kj-action-content">

            <div class="kj-action-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <rect x="5" y="3" width="14" height="18" rx="2" stroke="currentColor" stroke-width="1.8" />
                    <path d="M8 8H16M8 12H16M8 16H13" stroke="currentColor" stroke-width="1.8" />
                </svg>
            </div>

            <div>
                <div class="kj-action-title">Review Pending Approvals</div>
                <div class="kj-action-description">Open and process pending requests</div>
            </div>

        </div>

        <div class="kj-action-arrow">→</div>
    </a>

</div>


<div class="history-panel">

    <div class="history-header">

        <div class="history-title">My Recent Decision History</div>

        <a
            href="{{ route('kj.approvals.history') }}"
            class="history-link"
            style="text-decoration:none;"
        >
            View All History <span>→</span>
        </a>

    </div>


    <div class="history-table-wrapper">

        <table class="history-table">

            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Title</th>
                    <th>Department</th>
                    <th>Decision</th>
                    <th>Date Actioned</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($recentDecisions as $decisionRecord)

                    @php
                        $decisionClass = match ($decisionRecord->decision) {
                            'Approved' => 'approved',
                            'Rejected' => 'rejected',
                            'Returned' => 'returned',
                            default => '',
                        };

                        $procurementRequest = $decisionRecord->procurementRequest;
                    @endphp

                    <tr>
                        <td class="history-request-id">
                            REQ-{{ str_pad(
                                $decisionRecord->procurement_request_id,
                                4,
                                '0',
                                STR_PAD_LEFT
                            ) }}
                        </td>

                        <td class="history-title-text">
                            {{ $procurementRequest?->request_title ?? 'Request unavailable' }}
                        </td>

                        <td>
                            {{ $procurementRequest?->ptjDepartment?->department_name ?? 'N/A' }}
                        </td>

                        <td>
                            <span
                                class="decision-badge {{ $decisionClass }}"
                            >
                                <span class="decision-dot"></span>
                                {{ $decisionRecord->decision }}
                            </span>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($decisionRecord->created_at)->format('d M Y') }}
                        </td>

                        <td>
                            <a
                                href="{{ route('kj.approvals.history.details', $decisionRecord->approval_id) }}"
                                class="history-action-button"
                                title="View Decision Details"
                                style="text-decoration:none;"
                            >
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M2 12C4.5 7.5 7.8 5.5 12 5.5C16.2 5.5 19.5 7.5 22 12C19.5 16.5 16.2 18.5 12 18.5C7.8 18.5 4.5 16.5 2 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    />
                                    <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.6" />
                                </svg>
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align:center; padding:34px; color:#7d899c;">
                            No KJ decisions have been recorded yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<div class="dashboard-footer">
    <div>© {{ now()->year }} UCPMS. All rights reserved.</div>
    <div>Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA)</div>
</div>

@endsection