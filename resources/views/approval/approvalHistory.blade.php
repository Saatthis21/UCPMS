@extends('layouts.ucpms')

@section('title', 'Approval History - UCPMS')

@php

    $approvalRole = $approvalRole ?? 'kj';

    $isBendahari =
        $approvalRole === 'bendahari';


    $historyRoute =
        $isBendahari
            ? 'bendahari.approvals.history'
            : 'kj.approvals.history';


    $historyDetailsRoute =
        $isBendahari
            ? 'bendahari.approvals.history.details'
            : 'kj.approvals.history.details';


    $historyPageTitle =
        $isBendahari
            ? 'Bendahari Approval History'
            : 'KJ Approval History';


    $historyPageDescription =
        $isBendahari
            ? 'Review procurement requests previously approved or rejected by you.'
            : 'Review procurement requests previously approved, rejected or returned by you.';


    $historyCardTitle =
        $isBendahari
            ? 'Bendahari Decision Records'
            : 'KJ Decision Records';


    $historyCardDescription =
        $isBendahari
            ? 'Historical Bendahari approval decisions across PTJ departments.'
            : 'Historical approval actions for your PTJ.';

@endphp


@section('page-style')

<style>

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .history-header {
        margin-bottom: 22px;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 20px;
    }


    .history-title {
        margin: 0;

        color: var(--navy);

        font-size: 27px;
        font-weight: 800;

        letter-spacing: -0.4px;
    }


    .history-description {
        margin-top: 6px;

        color: var(--muted);

        font-size: 12px;
        line-height: 1.6;
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .summary-card {
        margin-bottom: 20px;

        padding: 18px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 15px;

        box-shadow: var(--shadow);
    }


    .summary-label {
        color: var(--muted);

        font-size: 11px;
        font-weight: 650;
    }


    .summary-value {
        margin-top: 4px;

        color: var(--navy);

        font-size: 22px;
        font-weight: 850;
    }


    .summary-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: var(--primary-soft);

        color: var(--primary);
    }


    .summary-icon svg {
        width: 21px;
        height: 21px;
    }


    /* =========================================================
       FILTER
    ========================================================= */

    .filter-card {
        margin-bottom: 20px;

        padding: 17px 18px;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 15px;

        box-shadow: var(--shadow);
    }


    .filter-form {
        display: grid;

        grid-template-columns:
            minmax(240px, 1fr)
            210px
            auto;

        gap: 12px;

        align-items: end;
    }


    .filter-group {
        min-width: 0;
    }


    .filter-label {
        margin-bottom: 6px;

        display: block;

        color: var(--muted);

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.3px;
    }


    .filter-input,
    .filter-select {
        width: 100%;
        height: 42px;

        padding: 0 12px;

        border: 1px solid #dfe5ed;
        border-radius: 9px;

        background: #ffffff;

        color: var(--text);

        outline: none;

        font-size: 12px;
    }


    .filter-input:focus,
    .filter-select:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px var(--primary-soft);
    }


    .filter-actions {
        display: flex;

        gap: 8px;
    }


    .filter-button {
        min-height: 42px;

        padding: 0 15px;

        border: none;
        border-radius: 9px;

        cursor: pointer;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        font-size: 11px;
        font-weight: 800;
    }


    .filter-submit {
        background: var(--primary);

        color: #ffffff;
    }


    .filter-reset {
        border: 1px solid var(--border);

        background: #ffffff;

        color: #64748b;
    }


    /* =========================================================
       TABLE CARD
    ========================================================= */

    .history-card {
        overflow: hidden;

        background: var(--surface);

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow: var(--shadow);
    }


    .history-card-header {
        padding: 17px 20px;

        border-bottom: 1px solid var(--border);

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;
    }


    .history-card-title {
        color: var(--navy);

        font-size: 15px;
        font-weight: 800;
    }


    .history-card-subtitle {
        margin-top: 3px;

        color: var(--muted);

        font-size: 10px;
    }


    .history-count {
        padding: 6px 10px;

        border-radius: 999px;

        background: var(--primary-soft);

        color: var(--primary);

        font-size: 10px;
        font-weight: 800;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;
    }


    .history-table {
        width: 100%;
        min-width: 1050px;

        border-collapse: collapse;
    }


    .history-table th {
        padding: 12px 14px;

        background: #f8fafc;

        border-bottom: 1px solid var(--border);

        text-align: left;

        color: #68768b;

        font-size: 10px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.2px;
    }


    .history-table td {
        padding: 14px;

        border-bottom: 1px solid #edf1f5;

        color: var(--text);

        font-size: 12px;

        vertical-align: middle;
    }


    .history-table tbody tr:last-child td {
        border-bottom: none;
    }


    .history-table tbody tr:hover {
        background: #fcfdff;
    }


    .request-id {
        color: var(--primary);

        font-weight: 800;

        white-space: nowrap;
    }


    .request-title {
        color: var(--navy);

        font-weight: 750;
    }


    .department-name {
        color: var(--navy);

        font-weight: 700;
    }


    .staff-id {
        margin-top: 3px;

        color: var(--muted);

        font-size: 10px;
    }


    .amount {
        color: var(--navy);

        font-weight: 750;

        white-space: nowrap;
    }


    /* =========================================================
       DECISION BADGES
    ========================================================= */

    .decision-badge {
        padding: 6px 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 999px;

        font-size: 10px;
        font-weight: 800;

        white-space: nowrap;
    }


    .decision-approved {
        background: var(--success-soft);

        color: var(--success);
    }


    .decision-rejected {
        background: var(--danger-soft);

        color: var(--danger);
    }


    .decision-returned {
        background: #fff0e5;

        color: #c86613;
    }


    /* =========================================================
       ACTION
    ========================================================= */

    .view-button {
        min-height: 35px;

        padding: 0 12px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        border: 1px solid var(--border);
        border-radius: 8px;

        background: #ffffff;

        color: var(--primary);

        font-size: 10px;
        font-weight: 800;

        transition: 0.2s;
    }


    .view-button:hover {
        border-color: var(--primary);

        background: var(--primary-soft);
    }


    .view-button svg {
        width: 15px;
        height: 15px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 55px 20px;

        text-align: center;
    }


    .empty-icon {
        width: 50px;
        height: 50px;

        margin: 0 auto 13px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: var(--primary-soft);

        color: var(--primary);
    }


    .empty-icon svg {
        width: 23px;
        height: 23px;
    }


    .empty-title {
        color: var(--navy);

        font-size: 14px;
        font-weight: 800;
    }


    .empty-description {
        margin-top: 5px;

        color: var(--muted);

        font-size: 11px;
    }


    /* =========================================================
        PAGINATION
        ========================================================= */

        .pagination-wrapper {
            padding: 16px 20px;

            border-top: 1px solid var(--border);
        }

        /* Keep pagination controls in a normal horizontal layout */
        .pagination-wrapper nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Prevent Laravel pagination SVG arrows from becoming oversized */
        .pagination-wrapper nav svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px;
            max-height: 16px;
            display: inline-block;
        }

        /* Keep pagination links and buttons compact */
        .pagination-wrapper nav a,
        .pagination-wrapper nav span {
            font-size: 12px;
            line-height: 1.5;
        }

        /* Align the arrow icons with their pagination controls */
        .pagination-wrapper nav a,
        .pagination-wrapper nav button {
            align-items: center;
        }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .filter-form {
            grid-template-columns: 1fr;
        }


        .filter-actions {
            width: 100%;
        }


        .filter-button {
            flex: 1;
        }

    }


    @media (max-width: 700px) {

        .history-header {
            flex-direction: column;
        }

    }

</style>

@endsection


@section('content')


{{-- =========================================================
     PAGE HEADER
========================================================= --}}

<div class="history-header">

    <div>

        <h1 class="history-title">
            {{ $historyPageTitle }}
        </h1>

        <div class="history-description">
            {{ $historyPageDescription }}
        </div>
    </div>

</div>


{{-- =========================================================
     SUMMARY
========================================================= --}}

<div class="summary-card">

    <div>

        <div class="summary-label">
            Total Decision Records
        </div>

        <div class="summary-value">
            {{ $approvals->total() }}
        </div>

    </div>


    <div class="summary-icon">

        <svg viewBox="0 0 24 24" fill="none">

            <path
                d="M7 3H17V21H7V3Z"
                stroke="currentColor"
                stroke-width="1.8"
            />

            <path
                d="M9.5 8H14.5M9.5 12H14.5M9.5 16H13"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
            />

        </svg>

    </div>

</div>


{{-- =========================================================
     FILTER
========================================================= --}}

<div class="filter-card">

    <form
        method="GET"
        action="{{ route($historyRoute) }}"
        class="filter-form"
    >

        <div class="filter-group">

            <label class="filter-label">
                Search Request
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="filter-input"
                placeholder="Search request ID or title..."
            >

        </div>


        <div class="filter-group">

            <label class="filter-label">
                Decision
            </label>

            <select
                name="decision"
                class="filter-select"
            >

                <option value="">
                    All Decisions
                </option>

                <option
                    value="Approved"
                    {{ request('decision') === 'Approved'
                        ? 'selected'
                        : '' }}
                >
                    Approved
                </option>

                <option
                    value="Rejected"
                    {{ request('decision') === 'Rejected'
                        ? 'selected'
                        : '' }}
                >
                    Rejected
                </option>

                @if (!$isBendahari)

                    <option
                        value="Returned"
                        {{ request('decision') === 'Returned'
                            ? 'selected'
                            : '' }}
                    >
                        Returned
                    </option>

                @endif

            </select>

        </div>


        <div class="filter-actions">

            <button
                type="submit"
                class="filter-button filter-submit"
            >
                Apply Filter
            </button>


            <a
                href="{{ route($historyRoute) }}"
                class="filter-button filter-reset"
            >
                Reset
            </a>

        </div>

    </form>

</div>


{{-- =========================================================
     HISTORY TABLE
========================================================= --}}

<div class="history-card">

    <div class="history-card-header">

        <div>

            <div class="history-card-title">
                {{ $historyCardTitle }}
            </div>

            <div class="history-card-subtitle">
                {{ $historyCardDescription }}
            </div>
        </div>


        <div class="history-count">

            {{ $approvals->total() }}
            record(s)

        </div>

    </div>


    @if ($approvals->count() > 0)

        <div class="table-wrapper">

            <table class="history-table">

                <thead>

                    <tr>

                        <th>
                            Request ID
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            PTJ Department
                        </th>

                        <th>
                            Decision
                        </th>

                        <th>
                            Approved Amount
                        </th>

                        <th>
                            Decision Date
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($approvals as $approval)

                        @php

                            $request =
                                $approval->procurementRequest;

                            $decisionClass = match (
                                $approval->decision
                            ) {
                                'Approved'
                                    => 'decision-approved',

                                'Rejected'
                                    => 'decision-rejected',

                                'Returned'
                                    => 'decision-returned',

                                default
                                    => 'decision-returned',
                            };

                        @endphp


                        <tr>

                            {{-- REQUEST ID --}}
                            <td>

                                <span class="request-id">

                                    REQ-{{ str_pad(
                                        $request->procurement_request_id,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                </span>

                            </td>


                            {{-- TITLE --}}
                            <td>

                                <div class="request-title">
                                    {{ $request->request_title }}
                                </div>

                            </td>


                            {{-- PTJ --}}
                            <td>

                                <div class="department-name">

                                    {{
                                        $request
                                            ->ptjDepartment
                                            ->department_name
                                        ?? '-'
                                    }}

                                </div>


                                <div class="staff-id">

                                    Staff ID:
                                    {{
                                        $request
                                            ->user
                                            ->staff_id
                                        ?? '-'
                                    }}

                                </div>

                            </td>


                            {{-- DECISION --}}
                            <td>

                                <span
                                    class="
                                        decision-badge
                                        {{ $decisionClass }}
                                    "
                                >

                                    {{ $approval->decision }}

                                </span>

                            </td>


                            {{-- APPROVED AMOUNT --}}
                            <td>

                                @if (
                                    $approval->decision === 'Approved'
                                    &&
                                    $request->approved_amount !== null
                                )

                                    <span class="amount">

                                        RM
                                        {{ number_format(
                                            (float)
                                            $request->approved_amount,
                                            2
                                        ) }}

                                    </span>

                                @else

                                    <span style="color: var(--muted);">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- DATE --}}
                            <td>

                                {{
                                    $approval->created_at
                                        ? $approval->created_at
                                            ->format(
                                                'd M Y, h:i A'
                                            )
                                        : '-'
                                }}

                            </td>


                            {{-- ACTION --}}
                            <td>

                                <a
                                    href="{{ route(
                                        $historyDetailsRoute,
                                        $approval
                                    ) }}"
                                    class="view-button"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >

                                        <path
                                            d="M2.5 12C4.7 7.8 8 5.7 12 5.7C16 5.7 19.3 7.8 21.5 12C19.3 16.2 16 18.3 12 18.3C8 18.3 4.7 16.2 2.5 12Z"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.7"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                    </svg>

                                    View

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        @if ($approvals->hasPages())

            <div class="pagination-wrapper">

                {{ $approvals->withQueryString()->links() }}

            </div>

        @endif


    @else

        <div class="empty-state">

            <div class="empty-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M7 3H17V21H7V3Z"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M9.5 9H14.5M9.5 13H14.5"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                </svg>

            </div>


            <div class="empty-title">
                No approval history found
            </div>


            <div class="empty-description">

                Approval decisions will appear here after
                you review procurement requests.

            </div>

        </div>

    @endif

</div>

@endsection