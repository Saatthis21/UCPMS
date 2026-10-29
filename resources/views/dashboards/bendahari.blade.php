@extends('layouts.ucpms')

@section('title', 'Bendahari Dashboard - UCPMS')


@section('page-style')

    .bendahari-grid {

        display: grid;

        grid-template-columns:
            1fr
            1fr
            1.1fr;

        gap: 20px;

        margin-bottom: 24px;

    }


    .treasury-card {

        min-height: 165px;

        padding: 24px;

        border-radius: 18px;

        background: #ffffff;

        border: 1px solid #e8eee9;

        box-shadow:
            0 10px 30px
            rgba(20, 80, 35, 0.06);

        position: relative;

        overflow: hidden;

    }


    .treasury-card.queue {

        border-left: 4px solid #65a2d5;

    }


    .treasury-card.approved {

        border-left: 4px solid #4da966;

    }


    .treasury-icon {

        width: 54px;
        height: 54px;

        border-radius: 16px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

    }


    .treasury-icon.queue {

        color: #2870a8;

        background: #e9f3fb;

    }


    .treasury-icon.approved {

        color: #18763a;

        background: #e8f5e9;

    }


    .treasury-icon svg {

        width: 26px;
        height: 26px;

    }


    .treasury-label {

        font-size: 14px;

        color: #58657d;

        margin-bottom: 6px;

    }


    .treasury-value {

        font-size: 32px;

        font-weight: 800;

        color: #17233c;

        margin-bottom: 5px;

        letter-spacing: -0.5px;

    }


    .treasury-value.green {

        color: #19843a;

    }


    .treasury-subtext {

        font-size: 12px;

        color: #7d899c;

    }


    /* =========================================================
       PRIMARY ACTION CARD
    ========================================================= */

    .treasury-action {

        min-height: 165px;

        padding: 24px;

        border-radius: 18px;

        color: #ffffff;

        background:
            linear-gradient(
                135deg,
                #176d32,
                #2da348
            );

        display: flex;

        align-items: center;
        justify-content: space-between;

        position: relative;

        overflow: hidden;

        box-shadow:
            0 12px 28px
            rgba(25, 132, 58, 0.17);

    }


    .treasury-action::after {

        content: "";

        width: 180px;
        height: 180px;

        border-radius: 50%;

        position: absolute;

        right: -60px;
        bottom: -90px;

        background:
            rgba(
                255,
                255,
                255,
                0.06
            );

    }


    .treasury-action-content {

        display: flex;

        align-items: center;

        gap: 18px;

        position: relative;

        z-index: 2;

    }


    .treasury-action-icon {

        width: 62px;
        height: 62px;

        border-radius: 17px;

        background: #ffffff;

        color: #19843a;

        display: flex;

        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }


    .treasury-action-icon svg {

        width: 29px;
        height: 29px;

    }


    .treasury-action-title {

        font-size: 17px;

        font-weight: 750;

        margin-bottom: 6px;

    }


    .treasury-action-description {

        font-size: 13px;

        line-height: 1.45;

        opacity: 0.9;

    }


    .treasury-action-arrow {

        width: 44px;
        height: 44px;

        border-radius: 50%;

        border:
            1px solid
            rgba(255,255,255,0.8);

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 25px;

        position: relative;

        z-index: 2;

    }


    /* =========================================================
       TREASURY QUEUE PANEL
    ========================================================= */

    .treasury-panel {

        background: #ffffff;

        border:
            1px solid
            #e8eee9;

        border-radius: 18px;

        overflow: hidden;

        box-shadow:
            0 10px 30px
            rgba(20,80,35,0.06);

    }


    .treasury-panel-header {

        min-height: 66px;

        padding: 0 24px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        border-bottom:
            1px solid
            #edf1ed;

    }


    .treasury-panel-title {

        font-size: 18px;

        font-weight: 750;

        color: #17233c;

    }


    .queue-link {

        color: #19843a;

        font-size: 13px;

        font-weight: 700;

    }


    /* =========================================================
       FILTERS
    ========================================================= */

    .treasury-toolbar {

        padding: 16px 24px;

        display: grid;

        grid-template-columns:
            1.5fr
            0.7fr
            0.7fr;

        gap: 14px;

        border-bottom:
            1px solid
            #edf1ed;

    }


    .treasury-control {

        width: 100%;

        height: 42px;

        border:
            1px solid
            #dfe7e1;

        border-radius: 10px;

        background: #ffffff;

        padding: 0 14px;

        color: #536078;

        outline: none;

    }


    .treasury-control:focus {

        border-color: #19843a;

        box-shadow:
            0 0 0 3px
            rgba(25,132,58,0.08);

    }


    /* =========================================================
       TABLE
    ========================================================= */

    .treasury-table-wrapper {

        overflow-x: auto;

    }


    .treasury-table {

        width: 100%;

        border-collapse: collapse;

        min-width: 1000px;

    }


    .treasury-table th,
    .treasury-table td {

        padding: 17px 20px;

        text-align: left;

        border-bottom:
            1px solid
            #edf1ed;

        font-size: 13px;

    }


    .treasury-table th {

        background: #fbfcfb;

        color: #5b687e;

        font-weight: 700;

    }


    .pr-id {

        color: #19843a;

        font-weight: 750;

    }


    .amount {

        color: #19843a;

        font-weight: 750;

    }


    .review-badge {

        display: inline-flex;

        align-items: center;

        padding:
            7px 11px;

        border-radius: 999px;

        background: #e9f6ea;

        color: #18743a;

        font-size: 11px;

        font-weight: 650;

    }


    .review-button {

        width: 38px;
        height: 38px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border:
            1px solid
            #bedac4;

        border-radius: 10px;

        background: #ffffff;

        color: #19843a;

        cursor: pointer;

    }


    .review-button svg {

        width: 18px;
        height: 18px;

    }


    /* =========================================================
       FOOTER / PAGINATION
    ========================================================= */

    .table-footer {

        padding: 16px 20px;

        display: flex;

        justify-content: space-between;

        align-items: center;

    }


    .table-info {

        font-size: 12px;

        color: #758199;

    }


    .pagination {

        display: flex;

        gap: 7px;

    }


    .page-button {

        min-width: 38px;
        height: 38px;

        padding: 0 12px;

        border:
            1px solid
            #dfe7e1;

        border-radius: 9px;

        background: #ffffff;

        color: #526079;

        cursor: pointer;

    }


    .page-button.active {

        background: #19843a;

        color: #ffffff;

        border-color: #19843a;

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

        .bendahari-grid {

            grid-template-columns:
                1fr
                1fr;

        }


        .treasury-action {

            grid-column:
                span 2;

        }


        .treasury-toolbar {

            grid-template-columns:
                1fr;

        }

    }


    @media (max-width: 650px) {

        .bendahari-grid {

            grid-template-columns:
                1fr;

        }


        .treasury-action {

            grid-column:
                span 1;

        }


        .table-footer,
        .dashboard-footer {

            flex-direction:
                column;

            align-items:
                flex-start;

            gap: 12px;

        }

    }

@endsection


@section('content')

<div class="welcome-section">

    <div>
        <div class="welcome-small">{{ $greeting }},</div>

        <h1 class="welcome-title">
            {{ auth()->user()->user_name }} 👋
        </h1>

        <div class="welcome-description">
            Bendahari - Finance Department
        </div>
    </div>

    <div class="welcome-decoration">
        <div class="welcome-circle-one"></div>
        <div class="welcome-circle-two"></div>
        <div class="welcome-circle-three"></div>
    </div>

</div>


<div class="bendahari-grid">

    <div class="treasury-card queue">

        <div class="treasury-icon queue">
            <svg viewBox="0 0 24 24" fill="none">
                <rect x="6" y="4" width="12" height="16" rx="2" stroke="currentColor" stroke-width="1.8" />
                <path d="M9 8H15M9 12H15M9 16H13" stroke="currentColor" stroke-width="1.8" />
            </svg>
        </div>

        <div class="treasury-label">Treasury Review Queue</div>

        <div class="treasury-value">
            {{ number_format($pendingQueueCount) }}
        </div>

        <div class="treasury-subtext">
            Requests awaiting final Bendahari review
        </div>

    </div>


    <div class="treasury-card approved">

        <div class="treasury-icon approved">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8" />
                <path d="M8.5 12L10.8 14.3L15.8 9.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <div class="treasury-label">Approved This Month</div>

        <div class="treasury-value green">
            {{ number_format($approvedThisMonth) }}
        </div>

        <div class="treasury-subtext">
            Total amount: RM {{ number_format($approvedAmountThisMonth, 2) }}
        </div>

    </div>


    <a
        href="{{ route('bendahari.approvals.index') }}"
        class="treasury-action"
        style="text-decoration:none;"
    >

        <div class="treasury-action-content">

            <div class="treasury-action-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <rect x="5" y="3" width="14" height="18" rx="2" stroke="currentColor" stroke-width="1.8" />
                    <path d="M8 8H16M8 12H16M8 16H13" stroke="currentColor" stroke-width="1.8" />
                </svg>
            </div>

            <div>
                <div class="treasury-action-title">Review Pending Approvals</div>
                <div class="treasury-action-description">Open and process pending requests</div>
            </div>

        </div>

        <div class="treasury-action-arrow">→</div>

    </a>

</div>


<div class="treasury-panel">

    <div class="treasury-panel-header">

        <div class="treasury-panel-title">
            Treasury Review Queue
            (Status: Approved_KJ)
        </div>

        <a
            href="{{ route('bendahari.approvals.index') }}"
            class="queue-link"
            style="text-decoration:none;"
        >
            View All Queue →
        </a>

    </div>


    <form
        method="GET"
        action="{{ route('bendahari.dashboard') }}"
        class="treasury-toolbar"
    >

        <input
            type="text"
            name="search"
            class="treasury-control"
            placeholder="Search by Request ID, Title, or Department..."
            value="{{ $search }}"
        >

        <select
            name="ptj_id"
            class="treasury-control"
            onchange="this.form.submit()"
        >
            <option value="">All Departments</option>

            @foreach ($departments as $department)
                <option
                    value="{{ $department->ptj_id }}"
                    {{ $ptjId === (int) $department->ptj_id ? 'selected' : '' }}
                >
                    {{ $department->department_name }}
                </option>
            @endforeach
        </select>

        <select
            name="amount"
            class="treasury-control"
            onchange="this.form.submit()"
        >
            <option value="all" {{ $amountFilter === 'all' ? 'selected' : '' }}>
                All Amounts
            </option>
            <option value="under-10000" {{ $amountFilter === 'under-10000' ? 'selected' : '' }}>
                Below RM 10,000
            </option>
            <option value="10000-30000" {{ $amountFilter === '10000-30000' ? 'selected' : '' }}>
                RM 10,000 - RM 30,000
            </option>
            <option value="over-30000" {{ $amountFilter === 'over-30000' ? 'selected' : '' }}>
                Above RM 30,000
            </option>
        </select>

    </form>


    <div class="treasury-table-wrapper">

        <table class="treasury-table">

            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Title</th>
                    <th>PTJ Department</th>
                    <th>Approved Amount</th>
                    <th>Submission Date</th>
                    <th>Awaiting Decision From</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($queueRequests as $procurementRequest)

                                        <tr>
                        <td class="pr-id">
                            REQ-{{ str_pad(
                                $procurementRequest->procurement_request_id,
                                4,
                                '0',
                                STR_PAD_LEFT
                            ) }}
                        </td>

                        <td>
                            {{ $procurementRequest->request_title }}
                        </td>

                        <td>
                            {{ $procurementRequest->ptjDepartment->department_name ?? 'N/A' }}
                        </td>

                        <td class="amount">
                            RM {{ number_format((float) $procurementRequest->approved_amount, 2) }}
                        </td>

                        <td>
                            {{ $procurementRequest->created_at->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            <span class="review-badge">Final Treasury Review</span>
                        </td>

                        <td>
                            <a
                                href="{{ route('bendahari.approvals.show', $procurementRequest) }}"
                                class="review-button"
                                title="Review Request"
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
                        <td colspan="7" style="text-align:center; padding:34px; color:#7d899c;">
                            No requests are currently waiting for Bendahari review.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="table-footer">

        <div class="table-info">
            @if ($queueRequests->total() > 0)
                Showing {{ $queueRequests->firstItem() }} to
                {{ $queueRequests->lastItem() }} of
                {{ $queueRequests->total() }} results
            @else
                Showing 0 results
            @endif
        </div>

        @if ($queueRequests->hasPages())
            <div class="pagination">

                @if ($queueRequests->onFirstPage())
                    <span class="page-button" style="opacity:.5; cursor:not-allowed; display:inline-flex; align-items:center; justify-content:center;">
                        Previous
                    </span>
                @else
                    <a
                        href="{{ $queueRequests->previousPageUrl() }}"
                        class="page-button"
                        style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;"
                    >
                        Previous
                    </a>
                @endif

                @foreach (
                    $queueRequests->getUrlRange(
                        max(1, $queueRequests->currentPage() - 2),
                        min($queueRequests->lastPage(), $queueRequests->currentPage() + 2)
                    ) as $page => $url
                )
                    <a
                        href="{{ $url }}"
                        class="page-button {{ $page === $queueRequests->currentPage() ? 'active' : '' }}"
                        style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;"
                    >
                        {{ $page }}
                    </a>
                @endforeach

                @if ($queueRequests->hasMorePages())
                    <a
                        href="{{ $queueRequests->nextPageUrl() }}"
                        class="page-button"
                        style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;"
                    >
                        Next
                    </a>
                @else
                    <span class="page-button" style="opacity:.5; cursor:not-allowed; display:inline-flex; align-items:center; justify-content:center;">
                        Next
                    </span>
                @endif

            </div>
        @endif

    </div>

</div>


<div class="dashboard-footer">
    <div>© {{ now()->year }} UCPMS. All rights reserved.</div>
    <div>Universiti Malaysia Pahang Al-Sultan Abdullah (UMPSA)</div>
</div>

@endsection