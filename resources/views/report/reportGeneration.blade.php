@extends('layouts.ucpms')

@section('title', 'Generate Report - UCPMS')

@section('page-style')
<style>
    /* =========================================================
       MODULE 7 - FOCUSED REPORTING UI
       All report views remain in this documented Blade file.
    ========================================================= */

    .report-page-heading { margin-bottom: 14px; }
    .report-page-heading h1 {
        margin: 0;
        color: var(--navy);
        font-size: 28px;
        line-height: 1.2;
        font-weight: 850;
        letter-spacing: -.025em;
    }
    .report-page-heading p {
        max-width: 850px;
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .report-surface {
        background: var(--surface, #fff);
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 16px;
        box-shadow: var(--shadow, 0 10px 30px rgba(15,23,42,.06));
    }

    .report-filter-card {
        padding: 20px;
        margin-bottom: 22px;
    }

    .report-result {
        padding: 22px;
        margin-bottom: 24px;
    }

    .report-alert {
        margin-bottom: 14px;
        padding: 13px 15px;
        border-radius: 12px;
        font-size: 12px;
        line-height: 1.6;
    }
    .report-alert-danger {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }
    .report-alert-warning {
        color: #92400e;
        background: #fffbeb;
        border: 1px solid #fde68a;
    }

    .section-intro {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 15px;
    }
    .section-intro h2 {
        margin: 0;
        color: var(--navy);
        font-size: 16px;
        font-weight: 850;
    }
    .section-intro p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.55;
    }

    /* =========================================================
       FILTERS
    ========================================================= */

    .report-filter-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(155px, 1fr));
        gap: 12px;
        align-items: end;
    }
    .report-filter-grid.six {
        grid-template-columns: repeat(6, minmax(145px, 1fr));
    }
    .report-field label {
        display: block;
        margin-bottom: 6px;
        color: #40516a;
        font-size: 10.5px;
        font-weight: 800;
    }
    .report-control {
        width: 100%;
        min-height: 40px;
        padding: 8px 11px;
        border: 1px solid #d9e2ec;
        border-radius: 9px;
        background: #fff;
        color: #17324d;
        font: inherit;
        font-size: 12px;
        outline: none;
        transition: border-color .16s ease, box-shadow .16s ease;
    }
    .report-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 12%, transparent);
    }
    .report-control:disabled,
    .muted-control {
        background: #f7f9fc;
        color: #667085;
    }
    .report-generate-button {
        width: 100%;
        min-height: 40px;
        padding: 9px 15px;
        border: 0;
        border-radius: 9px;
        background: var(--primary);
        color: #fff;
        font-size: 11px;
        font-weight: 850;
        cursor: pointer;
        transition: transform .15s ease, filter .15s ease;
    }
    .report-generate-button:hover {
        filter: brightness(.96);
        transform: translateY(-1px);
    }

    .report-export-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 18px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }
    .report-export-copy strong {
        display: block;
        color: var(--navy);
        font-size: 11px;
        font-weight: 850;
    }
    .report-export-copy span {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.45;
    }
    .report-export-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .report-export-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 36px;
        padding: 8px 13px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: #fff;
        color: #334963;
        font-size: 10.5px;
        font-weight: 850;
        text-decoration: none;
        transition: transform .15s ease, border-color .15s ease, box-shadow .15s ease;
    }
    .report-export-button:hover {
        transform: translateY(-1px);
        border-color: #b8c7d8;
        box-shadow: 0 6px 16px rgba(31, 52, 78, .08);
    }
    .report-export-button.pdf {
        color: #a33232;
        background: #fffafa;
        border-color: #f0d1d1;
    }
    .report-export-button.excel {
        color: #20764a;
        background: #f7fcf9;
        border-color: #cfe8da;
    }
    .export-format-mark {
        min-width: 26px;
        padding: 3px 5px;
        border-radius: 6px;
        background: currentColor;
        color: #fff;
        font-size: 7px;
        font-weight: 900;
        letter-spacing: .3px;
        text-align: center;
    }
    .report-export-button.pdf .export-format-mark { background: #b94747; }
    .report-export-button.excel .export-format-mark { background: #2e8055; }

    /* =========================================================
       REPORT IDENTITY / SCOPE
    ========================================================= */

    .report-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        padding-bottom: 18px;
        margin-bottom: 17px;
        border-bottom: 1px solid var(--border);
    }
    .report-heading h2 {
        margin: 0;
        color: var(--navy);
        font-size: 20px;
        font-weight: 900;
        letter-spacing: -.025em;
    }
    .report-heading p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.55;
    }
    .report-live-badge {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        background: #ecfdf3;
        color: #067647;
        font-size: 9.5px;
        font-weight: 850;
    }
    .report-live-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #12b76a;
    }
    .scope-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
    }
    .scope-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 9px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #f8fafc;
        color: #526071;
        font-size: 10px;
    }
    .scope-pill strong { color: #243b53; }
    .scope-note {
        margin-top: -3px;
        margin-bottom: 18px;
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.5;
    }

    .report-section { margin-top: 24px; }
    .report-section:first-of-type { margin-top: 0; }
    .report-section-title {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 16px;
        margin-bottom: 11px;
    }
    .report-section-title h3 {
        margin: 0;
        color: var(--navy);
        font-size: 14px;
        font-weight: 900;
    }
    .report-section-title p {
        margin: 4px 0 0;
        color: var(--muted);
        font-size: 10.5px;
        line-height: 1.5;
    }
    .report-section-kicker {
        margin-bottom: 3px;
        color: var(--primary);
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .09em;
    }

    /* =========================================================
       EXECUTIVE METRICS
    ========================================================= */

    .headline-grid {
        display: grid;
        grid-template-columns: minmax(260px, 1.35fr) repeat(3, minmax(150px, 1fr));
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
    }
    .headline-grid.five {
        grid-template-columns: minmax(230px, 1.25fr) repeat(4, minmax(130px, .9fr));
    }
    .headline-grid.three {
        grid-template-columns: minmax(260px, 1.35fr) repeat(2, minmax(180px, 1fr));
    }
    .headline-primary {
        position: relative;
        overflow: hidden;
        padding: 20px;
        border-right: 1px solid var(--border);
        background: linear-gradient(
            135deg,
            color-mix(in srgb, var(--primary) 10%, white),
            color-mix(in srgb, var(--primary) 2%, white)
        );
    }
    .headline-primary::after {
        content: '';
        position: absolute;
        right: -32px;
        bottom: -50px;
        width: 125px;
        height: 125px;
        border-radius: 50%;
        background: color-mix(in srgb, var(--primary) 8%, transparent);
    }
    .headline-label,
    .metric-label {
        color: #617085;
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .07em;
    }
    .headline-value {
        position: relative;
        z-index: 1;
        margin-top: 8px;
        color: var(--navy);
        font-size: 28px;
        font-weight: 900;
        letter-spacing: -.025em;
    }
    .headline-note,
    .metric-note {
        margin-top: 9px;
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.45;
    }
    .metric-block {
        padding: 20px 16px;
        border-right: 1px solid var(--border);
    }
    .metric-block:last-child { border-right: 0; }
    .metric-value {
        margin-top: 8px;
        color: var(--navy);
        font-size: 22px;
        font-weight: 900;
        letter-spacing: -.02em;
    }
    .metric-value.small { font-size: 17px; }

    .mini-metric-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
    }
    .mini-metric {
        padding: 15px;
        border-right: 1px solid var(--border);
    }
    .mini-metric:last-child { border-right: 0; }
    .mini-metric .value {
        margin-top: 5px;
        color: var(--navy);
        font-size: 18px;
        font-weight: 900;
    }
    .mini-metric .note {
        margin-top: 5px;
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.4;
    }

    /* =========================================================
       ANALYSIS / LIFECYCLE / CHARTS
    ========================================================= */

    .equal-two-column {
        display: grid;
        grid-template-columns: repeat(2, minmax(0,1fr));
        gap: 14px;
    }
    .two-column {
        display: grid;
        grid-template-columns: minmax(0,1.35fr) minmax(290px,.85fr);
        gap: 14px;
    }
    .analysis-card {
        padding: 16px;
        border: 1px solid var(--border);
        border-radius: 13px;
        background: #fff;
    }
    .analysis-card h4 {
        margin: 0;
        color: var(--navy);
        font-size: 12.5px;
        font-weight: 900;
    }
    .analysis-card p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 9.8px;
        line-height: 1.5;
    }
    .chart-box {
        position: relative;
        height: 245px;
        margin-top: 12px;
    }
    .chart-box.compact { height: 215px; }

    .lifecycle-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0,1fr));
        gap: 9px;
    }
    .lifecycle-card {
        padding: 12px;
        border: 1px solid var(--border);
        border-top: 3px solid var(--primary);
        border-radius: 10px;
        background: #fbfcfe;
    }
    .lifecycle-card.warning { border-top-color: #f59e0b; }
    .lifecycle-card.danger { border-top-color: #ef4444; }
    .lifecycle-card.success { border-top-color: #10b981; }
    .lifecycle-card.purple { border-top-color: #8b5cf6; }
    .lifecycle-card.blue { border-top-color: #3b82f6; }
    .lifecycle-name {
        color: #526071;
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .lifecycle-count {
        margin-top: 7px;
        color: var(--navy);
        font-size: 19px;
        font-weight: 900;
    }
    .tiny-progress {
        height: 4px;
        margin-top: 8px;
        overflow: hidden;
        border-radius: 999px;
        background: #e9eef5;
    }
    .tiny-progress span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: var(--primary);
    }

    /* =========================================================
       BUDGET PRESENTATION
    ========================================================= */

    .budget-hero {
        display: grid;
        grid-template-columns: 1.15fr 2fr;
        gap: 14px;
    }
    .budget-score {
        padding: 20px;
        border: 1px solid var(--border);
        border-radius: 13px;
        background: linear-gradient(145deg, color-mix(in srgb, var(--primary) 9%, white), #fff);
    }
    .budget-score .year {
        color: var(--primary);
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
    }
    .budget-score .amount {
        margin-top: 10px;
        color: var(--navy);
        font-size: 28px;
        font-weight: 900;
    }
    .budget-score .caption {
        margin-top: 5px;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.45;
    }
    .budget-stat-grid {
        display: grid;
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 10px;
    }
    .budget-stat {
        padding: 14px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: #fff;
    }
    .budget-stat strong {
        display: block;
        margin-top: 6px;
        color: var(--navy);
        font-size: 18px;
    }
    .budget-stat small {
        color: var(--muted);
        font-size: 9.5px;
    }
    .budget-stat.wide { grid-column: 1 / -1; }
    .budget-position-track {
        display: flex;
        height: 13px;
        margin-top: 17px;
        overflow: hidden;
        border-radius: 999px;
        background: #edf1f5;
    }
    .budget-position-track span { display: block; height: 100%; }
    .budget-used { background: #3b82f6; }
    .budget-reserved { background: #f59e0b; }
    .budget-available { background: #10b981; }
    .budget-position-legend {
        display: grid;
        grid-template-columns: repeat(3,minmax(0,1fr));
        gap: 8px;
        margin-top: 12px;
    }
    .budget-position-item {
        padding: 10px 11px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: #f8fafc;
    }
    .budget-position-item .name {
        color: #526071;
        font-size: 9px;
        font-weight: 850;
    }
    .budget-position-item .number {
        margin-top: 4px;
        color: var(--navy);
        font-size: 13px;
        font-weight: 900;
    }

    /* =========================================================
       ORDER / PAYMENT POSITION
    ========================================================= */

    .position-bar {
        display: flex;
        height: 13px;
        margin: 16px 0 13px;
        overflow: hidden;
        border-radius: 999px;
        background: #edf1f5;
    }
    .position-bar span { min-width: 0; height: 100%; }
    .position-awaiting { background: #f59e0b; }
    .position-pending { background: #3b82f6; }
    .position-paid { background: #10b981; }
    .position-legend {
        display: grid;
        grid-template-columns: repeat(3,minmax(0,1fr));
        gap: 9px;
    }
    .position-item {
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #f8fafc;
    }
    .position-item .label {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #536273;
        font-size: 9.5px;
        font-weight: 850;
    }
    .position-item .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }
    .position-item .number {
        margin-top: 6px;
        color: var(--navy);
        font-size: 19px;
        font-weight: 900;
    }
    .position-item .percent {
        margin-top: 3px;
        color: var(--muted);
        font-size: 9px;
    }

    /* =========================================================
       AUDIT / COMPLIANCE
    ========================================================= */

    .compliance-summary {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
    }
    .compliance-summary-row {
        display: grid;
        grid-template-columns: minmax(220px, 1.1fr) minmax(0, 2fr) auto;
        gap: 16px;
        align-items: center;
        padding: 13px 15px;
        border-bottom: 1px solid var(--border);
    }
    .compliance-summary-row:last-child { border-bottom: 0; }
    .compliance-summary-name {
        color: var(--navy);
        font-size: 10px;
        font-weight: 900;
    }
    .compliance-summary-rule {
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.45;
    }
    .compliance-result {
        min-width: 94px;
        text-align: right;
    }
    .attention-panel {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #a6f4c5;
        border-left: 4px solid #12b76a;
        border-radius: 11px;
        background: #f6fef9;
    }
    .attention-panel.warning {
        border-color: #fedf89;
        border-left-color: #f79009;
        background: #fffcf5;
    }
    .attention-icon {
        display: grid;
        place-items: center;
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        border-radius: 50%;
        background: #d1fadf;
        color: #027a48;
        font-weight: 900;
    }
    .attention-panel.warning .attention-icon {
        background: #fef0c7;
        color: #b54708;
    }
    .attention-title {
        color: var(--navy);
        font-size: 11px;
        font-weight: 900;
    }
    .attention-copy {
        margin-top: 3px;
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.45;
    }
    .exception-list { display: grid; gap: 8px; }
    .exception-row {
        display: grid;
        grid-template-columns: 88px 1fr 150px;
        gap: 10px;
        align-items: center;
        padding: 10px 11px;
        border: 1px solid #fecdca;
        border-radius: 9px;
        background: #fff7f6;
        font-size: 9.5px;
    }
    .exception-row strong { color: #b42318; }
    .exception-title { color: var(--navy); font-weight: 750; }
    .exception-ptj { color: var(--muted); }

    /* =========================================================
       ROLE / DISTRIBUTION BARS
    ========================================================= */

    .role-bars {
        display: grid;
        gap: 10px;
        margin-top: 13px;
    }
    .role-bar-row {
        display: grid;
        grid-template-columns: 165px 1fr 50px;
        gap: 9px;
        align-items: center;
    }
    .role-bar-label {
        color: #475467;
        font-size: 9.5px;
        font-weight: 750;
    }
    .role-bar-track {
        height: 7px;
        overflow: hidden;
        border-radius: 999px;
        background: #edf1f5;
    }
    .role-bar-track span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: var(--primary);
    }
    .role-bar-value {
        text-align: right;
        color: var(--navy);
        font-size: 9.5px;
        font-weight: 900;
    }

    /* =========================================================
       TABLES / BADGES
    ========================================================= */

    .report-table-wrap {
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 11px;
    }
    .report-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        background: #fff;
    }
    .report-table.wide { min-width: 1150px; }
    .report-table th {
        padding: 10px 11px;
        border-bottom: 1px solid var(--border);
        background: #f7f9fc;
        color: #536273;
        font-size: 8.8px;
        font-weight: 900;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .045em;
        white-space: nowrap;
    }
    .report-table td {
        padding: 11px;
        border-bottom: 1px solid #edf1f5;
        color: #344054;
        font-size: 10px;
        line-height: 1.45;
        vertical-align: middle;
    }
    .report-table tr:last-child td { border-bottom: 0; }
    .report-table tbody tr:hover { background: #fbfcfe; }
    .id-cell {
        color: var(--navy) !important;
        font-weight: 900;
        white-space: nowrap;
    }
    .money-cell {
        color: var(--navy) !important;
        font-weight: 800;
        white-space: nowrap;
    }
    .title-cell {
        min-width: 190px;
        color: var(--navy) !important;
        font-weight: 750;
    }
    .remarks-cell {
        min-width: 190px;
        max-width: 300px;
    }
    .muted-text { color: var(--muted); }
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 8.8px;
        font-weight: 900;
        white-space: nowrap;
    }
    .badge-success { background: #ecfdf3; color: #067647; }
    .badge-danger { background: #fef3f2; color: #b42318; }
    .badge-warning { background: #fffaeb; color: #b54708; }
    .badge-info { background: #eff8ff; color: #175cd3; }
    .badge-neutral { background: #f2f4f7; color: #475467; }

    .progress-cell { min-width: 150px; }
    .progress-meta {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        color: #536273;
        font-size: 9px;
        font-weight: 800;
    }
    .progress-track {
        height: 6px;
        margin-top: 6px;
        overflow: hidden;
        border-radius: 999px;
        background: #e9eef5;
    }
    .progress-track span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: var(--primary);
    }
    .progress-track.success span { background: #12b76a; }
    .progress-track.warning span { background: #f79009; }
    .progress-track.danger span { background: #f04438; }

    .pagination-wrap { margin-top: 14px; }
    .pagination-wrap nav {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }
    /* Laravel's default paginator uses Tailwind utility classes. UCPMS does
       not rely on Tailwind, so these rules keep report pagination compact. */
    .pagination-wrap nav > div:first-child { display: none !important; }
    .pagination-wrap nav > div:last-child {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
    }
    .pagination-wrap nav p {
        margin: 0;
        color: var(--muted);
        font-size: 9.5px;
    }
    .pagination-wrap nav svg {
        width: 14px !important;
        height: 14px !important;
    }
    .pagination-wrap nav a,
    .pagination-wrap nav span[aria-current] > span,
    .pagination-wrap nav span[aria-disabled] > span {
        min-width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 9px;
        border: 1px solid var(--border) !important;
        background: #fff !important;
        color: #526079 !important;
        font-size: 9.5px !important;
        text-decoration: none !important;
        box-shadow: none !important;
    }
    .pagination-wrap nav span[aria-current] > span {
        border-color: var(--primary) !important;
        background: var(--primary) !important;
        color: #fff !important;
    }
    .pagination-wrap nav a:hover {
        border-color: var(--primary) !important;
        color: var(--primary) !important;
    }

    .empty-state {
        padding: 26px 18px;
        border: 1px dashed #d7dee8;
        border-radius: 11px;
        background: #fbfcfe;
        color: var(--muted);
        font-size: 10.5px;
        text-align: center;
    }
    .report-placeholder {
        padding: 52px 24px;
        text-align: center;
    }
    .placeholder-mark {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        margin: 0 auto 12px;
        border-radius: 13px;
        background: var(--primary-soft, #eef4ff);
        color: var(--primary);
        font-size: 21px;
        font-weight: 900;
    }
    .report-placeholder h2 {
        margin: 0;
        color: var(--navy);
        font-size: 16px;
    }
    .report-placeholder p {
        max-width: 560px;
        margin: 8px auto 0;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.6;
    }

    @media (max-width: 1280px) {
        .report-filter-grid,
        .report-filter-grid.six { grid-template-columns: repeat(3,minmax(170px,1fr)); }
        .headline-grid,
        .headline-grid.five,
        .headline-grid.three { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .headline-primary { border-bottom: 1px solid var(--border); }
    }
    @media (max-width: 920px) {
        .equal-two-column,
        .two-column,
        .budget-hero { grid-template-columns: 1fr; }
        .mini-metric-grid,
        .lifecycle-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .position-legend,
        .compliance-grid,
        .budget-position-legend { grid-template-columns: 1fr; }
    }
    @media (max-width: 680px) {
        .report-filter-grid,
        .report-filter-grid.six,
        .headline-grid,
        .headline-grid.five,
        .headline-grid.three,
        .mini-metric-grid,
        .lifecycle-grid,
        .budget-stat-grid { grid-template-columns: 1fr; }
        .compliance-summary-row { grid-template-columns: 1fr; gap: 5px; }
        .compliance-result { text-align: left; }
        .headline-primary,
        .metric-block,
        .mini-metric { border-right: 0; border-bottom: 1px solid var(--border); }
        .report-heading { flex-direction: column; }
        .report-export-bar { align-items: flex-start; flex-direction: column; }
        .report-export-actions { width: 100%; }
        .report-export-button { flex: 1 1 140px; }
        .role-bar-row { grid-template-columns: 110px 1fr 40px; }
        .exception-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <section class="report-page-heading">
        <h1>Generate Report</h1>
        <p>
            Generate focused, role-authorised UCPMS reports using current system data.
            Each role receives only the reporting information needed for its responsibilities.
        </p>
    </section>

    @if ($errors->any())
        <div class="report-alert report-alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- =========================================================
         REPORT FILTERS
    ========================================================== --}}

    <section class="report-surface report-filter-card">
        <div class="section-intro">
            <div>
                <h2>Report Filters</h2>
                <p>Choose the report scope and only the filters relevant to that report.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('report.index') }}">

            @php
                $filterGridClass = (
                    auth()->user()->role === 'kj'
                    && $selectedReportType === 'department_procurement_budget_approval'
                ) ? 'report-filter-grid six' : 'report-filter-grid';
            @endphp

            <div class="{{ $filterGridClass }}">

                <div class="report-field">
                    <label for="report_type">Report Type</label>
                    <select name="report_type" id="report_type" class="report-control" required>
                        @foreach ($reportTypes as $value => $label)
                            <option value="{{ $value }}" {{ $selectedReportType === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PTJ FILTERS --}}
                @if (
                    auth()->user()->role === 'ptj'
                    && $selectedReportType === 'department_procurement_order'
                )
                    <div class="report-field">
                        <label>PTJ Department</label>
                        <input type="text" class="report-control muted-control"
                               value="{{ $ptjDepartment->department_name }}" disabled>
                    </div>
                    <div class="report-field">
                        <label for="ptj_date_from">Date From</label>
                        <input type="date" name="date_from" id="ptj_date_from"
                               class="report-control" value="{{ $ptjDateFrom }}">
                    </div>
                    <div class="report-field">
                        <label for="ptj_date_to">Date To</label>
                        <input type="date" name="date_to" id="ptj_date_to"
                               class="report-control" value="{{ $ptjDateTo }}">
                    </div>
                    <div class="report-field">
                        <label for="ptj_status">Procurement Status</label>
                        <select name="status" id="ptj_status" class="report-control">
                            <option value="all" {{ $ptjStatus === 'all' ? 'selected' : '' }}>All Statuses</option>
                            @foreach ($ptjStatusLabels as $value => $label)
                                <option value="{{ $value }}" {{ $ptjStatus === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- KJ FILTERS --}}
                @if (
                    auth()->user()->role === 'kj'
                    && $selectedReportType === 'department_procurement_budget_approval'
                )
                    <div class="report-field">
                        <label>PTJ Department</label>
                        <input type="text" class="report-control muted-control"
                               value="{{ $kjDepartment->department_name }}" disabled>
                    </div>
                    <div class="report-field">
                        <label for="kj_budget_year">Budget Year</label>
                        <select name="budget_year" id="kj_budget_year" class="report-control">
                            @if ($kjBudgetYears->isEmpty())
                                <option value="{{ $kjBudgetYear }}">{{ $kjBudgetYear }}</option>
                            @else
                                @foreach ($kjBudgetYears as $year)
                                    <option value="{{ $year }}" {{ (int)$kjBudgetYear === (int)$year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="kj_date_from">Date From</label>
                        <input type="date" name="date_from" id="kj_date_from"
                               class="report-control" value="{{ $kjDateFrom }}">
                    </div>
                    <div class="report-field">
                        <label for="kj_date_to">Date To</label>
                        <input type="date" name="date_to" id="kj_date_to"
                               class="report-control" value="{{ $kjDateTo }}">
                    </div>
                    <div class="report-field">
                        <label for="kj_status">Procurement Status</label>
                        <select name="status" id="kj_status" class="report-control">
                            <option value="all" {{ $kjStatus === 'all' ? 'selected' : '' }}>All Statuses</option>
                            @foreach ($kjStatusLabels as $value => $label)
                                <option value="{{ $value }}" {{ $kjStatus === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="kj_decision">Approval Decision</label>
                        <select name="decision" id="kj_decision" class="report-control">
                            <option value="all" {{ $kjDecision === 'all' ? 'selected' : '' }}>All Decisions</option>
                            @foreach ($kjDecisionLabels as $value => $label)
                                <option value="{{ $value }}" {{ $kjDecision === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- BENDAHARI NORMAL REPORT FILTERS --}}
                @if (
                    auth()->user()->role === 'bendahari'
                    && $selectedReportType === 'university_procurement_financial'
                )
                    <div class="report-field">
                        <label for="bend_ptj_id">PTJ Department</label>
                        <select name="ptj_id" id="bend_ptj_id" class="report-control">
                            <option value="">All PTJ Departments</option>
                            @foreach ($bendDepartments as $department)
                                <option value="{{ $department->ptj_id }}"
                                    {{ (int)$bendPtjId === (int)$department->ptj_id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="bend_budget_year">Budget Year</label>
                        <select name="budget_year" id="bend_budget_year" class="report-control">
                            @if ($bendBudgetYears->isEmpty())
                                <option value="{{ $bendBudgetYear }}">{{ $bendBudgetYear }}</option>
                            @else
                                @foreach ($bendBudgetYears as $year)
                                    <option value="{{ $year }}" {{ (int)$bendBudgetYear === (int)$year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="bend_date_from">Date From</label>
                        <input type="date" name="date_from" id="bend_date_from"
                               class="report-control" value="{{ $bendDateFrom }}">
                    </div>
                    <div class="report-field">
                        <label for="bend_date_to">Date To</label>
                        <input type="date" name="date_to" id="bend_date_to"
                               class="report-control" value="{{ $bendDateTo }}">
                    </div>
                @endif

                {{-- BENDAHARI AUDIT FILTERS --}}
                @if (
                    auth()->user()->role === 'bendahari'
                    && $selectedReportType === 'procurement_approval_audit'
                )
                    <div class="report-field">
                        <label for="bend_audit_ptj">PTJ Department</label>
                        <select name="ptj_id" id="bend_audit_ptj" class="report-control">
                            <option value="">All PTJ Departments</option>
                            @foreach ($bendAuditDepartments as $department)
                                <option value="{{ $department->ptj_id }}"
                                    {{ (int)$bendAuditPtjId === (int)$department->ptj_id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="bend_audit_from">Date From</label>
                        <input type="date" name="date_from" id="bend_audit_from"
                               class="report-control" value="{{ $bendAuditDateFrom }}">
                    </div>
                    <div class="report-field">
                        <label for="bend_audit_to">Date To</label>
                        <input type="date" name="date_to" id="bend_audit_to"
                               class="report-control" value="{{ $bendAuditDateTo }}">
                    </div>
                    <div class="report-field">
                        <label for="bend_audit_decision">Decision</label>
                        <select name="decision" id="bend_audit_decision" class="report-control">
                            <option value="all" {{ $bendAuditDecision === 'all' ? 'selected' : '' }}>All Decisions</option>
                            @foreach ($bendAuditDecisionLabels as $value => $label)
                                <option value="{{ $value }}" {{ $bendAuditDecision === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- ADMIN OVERVIEW FILTERS --}}
                @if (
                    auth()->user()->role === 'admin'
                    && $selectedReportType === 'user_system_overview'
                )
                    <div class="report-field">
                        <label for="admin_role_filter">User Role</label>
                        <select name="role_filter" id="admin_role_filter" class="report-control">
                            <option value="all" {{ $adminRoleFilter === 'all' ? 'selected' : '' }}>All Roles</option>
                            @foreach ($adminRoleLabels as $value => $label)
                                <option value="{{ $value }}" {{ $adminRoleFilter === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="admin_account_status">Account Status</label>
                        <select name="account_status" id="admin_account_status" class="report-control">
                            <option value="all" {{ $adminAccountStatus === 'all' ? 'selected' : '' }}>All Accounts</option>
                            <option value="active" {{ $adminAccountStatus === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $adminAccountStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="report-field">
                        <label for="admin_ptj_id">PTJ Department</label>
                        <select name="ptj_id" id="admin_ptj_id" class="report-control">
                            <option value="">All / Not Applicable</option>
                            @foreach ($adminDepartments as $department)
                                <option value="{{ $department->ptj_id }}"
                                    {{ (int)$adminPtjId === (int)$department->ptj_id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- ADMIN AUDIT FILTERS --}}
                @if (
                    auth()->user()->role === 'admin'
                    && $selectedReportType === 'system_audit'
                )
                    <div class="report-field">
                        <label for="admin_audit_from">Date From</label>
                        <input type="date" name="date_from" id="admin_audit_from"
                               class="report-control" value="{{ $adminAuditDateFrom }}">
                    </div>
                    <div class="report-field">
                        <label for="admin_audit_to">Date To</label>
                        <input type="date" name="date_to" id="admin_audit_to"
                               class="report-control" value="{{ $adminAuditDateTo }}">
                    </div>
                    <div class="report-field">
                        <label for="audit_category">Audit Category</label>
                        <select name="audit_category" id="audit_category" class="report-control">
                            @foreach ($adminAuditCategoryLabels as $value => $label)
                                <option value="{{ $value }}" {{ $adminAuditCategory === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="report-field">
                    <label>&nbsp;</label>
                    <button type="submit" name="generate" value="1" class="report-generate-button">
                        Generate Report
                    </button>
                </div>
            </div>
        </form>

        @if (($reportGenerated ?? false))
            @php
                $exportQuery = request()->except([
                    'export',
                    'ptj_report_page',
                    'kj_procurement_page',
                    'kj_approval_page',
                    'bend_financial_page',
                    'bend_audit_decision_page',
                    'bend_audit_log_page',
                    'admin_users_page',
                    'admin_audit_page',
                ]);

                $exportQuery['report_type'] = $selectedReportType;
                $exportQuery['generate'] = 1;
            @endphp

            <div class="report-export-bar">
                <div class="report-export-copy">
                    <strong>Export Current Filtered Report</strong>
                    <span>
                        PDF creates a formal printable report. Excel exports all filtered
                        register records for detailed review.
                    </span>
                </div>

                <div class="report-export-actions">
                    <a
                        href="{{ route('report.index', array_merge($exportQuery, ['export' => 'pdf'])) }}"
                        class="report-export-button pdf"
                    >
                        <span class="export-format-mark">PDF</span>
                        Export PDF
                    </a>

                    <a
                        href="{{ route('report.index', array_merge($exportQuery, ['export' => 'excel'])) }}"
                        class="report-export-button excel"
                    >
                        <span class="export-format-mark">XLSX</span>
                        Export Excel
                    </a>
                </div>
            </div>
        @endif
    </section>

    {{-- =========================================================
         RESULTS
    ========================================================== --}}

    @if (!($reportGenerated ?? false))

        <section class="report-surface report-placeholder">
            <div class="placeholder-mark">▦</div>
            <h2>Ready to Generate Report</h2>
            <p>
                Apply the relevant filters above and click Generate Report.
                UCPMS will display a focused summary, only the most useful visual analysis,
                and the supporting records for your role.
            </p>
        </section>

    {{-- =========================================================
         PTJ - DEPARTMENT PROCUREMENT & ORDER REPORT
    ========================================================== --}}

    @elseif (
        auth()->user()->role === 'ptj'
        && $selectedReportType === 'department_procurement_order'
    )

        <section class="report-surface report-result">
            <div class="report-heading">
                <div>
                    <h2>Department Procurement & Order Report</h2>
                    <p>
                        {{ $ptjDepartment->department_name }} · requests submitted
                        {{ \Carbon\Carbon::parse($ptjDateFrom)->format('d M Y') }}–{{ \Carbon\Carbon::parse($ptjDateTo)->format('d M Y') }}
                    </p>
                </div>
                <span class="report-live-badge"><span class="report-live-dot"></span>Live UCPMS Data</span>
            </div>

            <div class="scope-row">
                <span class="scope-pill"><strong>Scope:</strong> Own PTJ Department</span>
                <span class="scope-pill"><strong>Status:</strong> {{ $ptjStatus === 'all' ? 'All Statuses' : ($ptjStatusLabels[$ptjStatus] ?? $ptjStatus) }}</span>
                <span class="scope-pill"><strong>Date Basis:</strong> Request Submission Date</span>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">Department portfolio</div>
                        <h3>Procurement Snapshot</h3>
                        <p>Essential procurement position for the selected period.</p>
                    </div>
                </div>

                <div class="headline-grid">
                    <div class="headline-primary">
                        <div class="headline-label">Final Approved Procurement Value</div>
                        <div class="headline-value">RM {{ number_format($ptjFinalApprovedValue, 2) }}</div>
                        <div class="headline-note">Only requests that have passed final Bendahari approval are counted.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Procurement Requests</div>
                        <div class="metric-value">{{ number_format($ptjTotalRequests) }}</div>
                        <div class="metric-note">Requests submitted in the selected reporting period.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Requires Amendment</div>
                        <div class="metric-value">{{ number_format($ptjRequestsRequiringAction) }}</div>
                        <div class="metric-note">Requests currently returned by KJ for correction.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Completed</div>
                        <div class="metric-value">{{ number_format($ptjCompletedRequests) }}</div>
                        <div class="metric-note">Requests currently at Completed workflow status.</div>
                    </div>
                </div>
            </div>

            @php
                $ptjLifecycleTotal = max(1, array_sum($ptjLifecycle));
            @endphp

            <div class="report-section">
                <div class="equal-two-column">
                    <div class="analysis-card">
                        <h4>Procurement Activity by Submission Month</h4>
                        <p>Total request volume compared with requests that later reached final Bendahari approval.</p>
                        @if ($ptjTotalRequests > 0)
                            <div class="chart-box compact"><canvas id="ptjMonthlyChart"></canvas></div>
                        @else
                            <div class="empty-state" style="margin-top:12px;">No procurement activity matches the selected criteria.</div>
                        @endif
                    </div>

                    <div class="analysis-card">
                        <h4>Current Procurement Position</h4>
                        <p>A compact operational view of the selected request cohort.</p>
                        <div class="lifecycle-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); margin-top:12px;">
                            @foreach ([
                                ['Under Review', $ptjLifecycle['under_review'], 'blue'],
                                ['Amendment', $ptjLifecycle['amendment'], 'warning'],
                                ['Rejected', $ptjLifecycle['rejected'], 'danger'],
                                ['Approved / Execution', $ptjLifecycle['execution'], 'purple'],
                                ['Completed', $ptjLifecycle['completed'], 'success'],
                            ] as [$label, $count, $class])
                                <div class="lifecycle-card {{ $class }}">
                                    <div class="lifecycle-name">{{ $label }}</div>
                                    <div class="lifecycle-count">{{ number_format($count) }}</div>
                                    <div class="tiny-progress"><span style="width:{{ ($count / $ptjLifecycleTotal) * 100 }}%"></span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">Post-approval execution</div>
                        <h3>Order & Delivery Position</h3>
                        <p>Local Orders connected to the same procurement request cohort.</p>
                    </div>
                </div>

                <div class="mini-metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr));">
                    <div class="mini-metric">
                        <div class="metric-label">Local Orders</div>
                        <div class="value">{{ number_format($ptjLocalOrders) }}</div>
                        <div class="note">Generated Local Orders in this request cohort.</div>
                    </div>
                    <div class="mini-metric">
                        <div class="metric-label">Awaiting Delivery</div>
                        <div class="value">{{ number_format($ptjAwaitingDelivery) }}</div>
                        <div class="note">No delivery record has been submitted yet.</div>
                    </div>
                    <div class="mini-metric">
                        <div class="metric-label">Payment Pending / Paid</div>
                        <div class="value">{{ number_format($ptjPaymentPending) }} / {{ number_format($ptjPaidOrders) }}</div>
                        <div class="note">Financial position after PTJ delivery confirmation.</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <h3>Department Procurement Register</h3>
                        <p>One focused register linking procurement, selected vendor and later order execution.</p>
                    </div>
                </div>

                @if ($ptjReportRecords->isEmpty())
                    <div class="empty-state">No procurement records were found for the selected criteria.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead>
                                <tr>
                                    <th>Request</th>
                                    <th>Submitted</th>
                                    <th>Title</th>
                                    <th>Estimated</th>
                                    <th>Final Approved</th>
                                    <th>Selected Vendor</th>
                                    <th>Local Order</th>
                                    <th>Execution / Payment</th>
                                    <th>Workflow Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ptjReportRecords as $record)
                                    @php
                                        $isFinal = in_array($record->request_status, $ptjFinalApprovedStatuses, true);
                                        $lo = $record->localOrder;
                                        $delivery = $lo?->deliveryOrder;

                                        if (!$lo) {
                                            $execution = 'Not Yet Ordered';
                                            $executionClass = 'badge-neutral';
                                        } elseif (!$delivery) {
                                            $execution = 'Awaiting Delivery';
                                            $executionClass = 'badge-warning';
                                        } elseif ($delivery->payment_status === 'Paid') {
                                            $execution = 'Paid';
                                            $executionClass = 'badge-success';
                                        } else {
                                            $execution = 'Payment Pending';
                                            $executionClass = 'badge-info';
                                        }

                                        $statusClass = match ($record->request_status) {
                                            'Rejected_KJ', 'Rejected_Bendahari' => 'badge-danger',
                                            'Pending_KJ', 'Pending_Edit', 'Approved_KJ' => 'badge-warning',
                                            'Approved_Bendahari', 'LO_Generated', 'Delivery_Received', 'Completed' => 'badge-success',
                                            default => 'badge-neutral',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="id-cell">REQ-{{ str_pad($record->procurement_request_id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $record->created_at->format('d M Y') }}</td>
                                        <td class="title-cell">{{ $record->request_title }}</td>
                                        <td class="money-cell">RM {{ number_format((float)$record->estimated_amount, 2) }}</td>
                                        <td class="money-cell">
                                            {{ $isFinal && $record->approved_amount !== null ? 'RM '.number_format((float)$record->approved_amount, 2) : '—' }}
                                        </td>
                                        <td>{{ $record->selectedQuotation?->vendor?->company_name ?? 'Not Selected' }}</td>
                                        <td>{{ $lo?->lo_number ?? '—' }}</td>
                                        <td><span class="badge {{ $executionClass }}">{{ $execution }}</span></td>
                                        <td><span class="badge {{ $statusClass }}">{{ $ptjStatusLabels[$record->request_status] ?? $record->request_status }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $ptjReportRecords->links() }}</div>
                @endif
            </div>
        </section>

    {{-- =========================================================
         KJ - DEPARTMENT PROCUREMENT, BUDGET & APPROVAL REPORT
    ========================================================== --}}

    @elseif (
        auth()->user()->role === 'kj'
        && $selectedReportType === 'department_procurement_budget_approval'
    )

        <section class="report-surface report-result">
            <div class="report-heading">
                <div>
                    <h2>Department Procurement, Budget & Approval Report</h2>
                    <p>
                        {{ $kjDepartment->department_name }} · Budget Year {{ $kjBudgetYear }} · activity and KJ decisions
                        {{ \Carbon\Carbon::parse($kjDateFrom)->format('d M Y') }}–{{ \Carbon\Carbon::parse($kjDateTo)->format('d M Y') }}
                    </p>
                </div>
                <span class="report-live-badge"><span class="report-live-dot"></span>Live UCPMS Data</span>
            </div>

            <div class="scope-row">
                <span class="scope-pill"><strong>Scope:</strong> Own PTJ Department</span>
                <span class="scope-pill"><strong>Budget Year:</strong> {{ $kjBudgetYear }}</span>
                <span class="scope-pill"><strong>Procurement:</strong> {{ $kjStatus === 'all' ? 'All Statuses' : ($kjStatusLabels[$kjStatus] ?? $kjStatus) }}</span>
                <span class="scope-pill"><strong>Decisions:</strong> {{ $kjDecision === 'all' ? 'All Decisions' : ($kjDecisionLabels[$kjDecision] ?? $kjDecision) }}</span>
            </div>
            <div class="scope-note">
                Budget Year applies to the annual budget position. Procurement Status applies to procurement sections and the procurement register, while Approval Decision applies to KJ approval performance and the KJ decision register.
            </div>

            @if (!$kjBudgetRecord)
                <div class="report-alert report-alert-warning">
                    No budget record was found for {{ $kjDepartment->department_name }} in {{ $kjBudgetYear }}.
                    Procurement and approval activity is still shown, while budget amounts remain RM 0.00.
                </div>
            @endif

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">Department financial control</div>
                        <h3>Annual Budget Position</h3>
                        <p>Current annual budget record for the selected financial year.</p>
                    </div>
                </div>

                <div class="budget-hero">
                    <div class="budget-score">
                        <div class="year">{{ $kjBudgetYear }} Allocation</div>
                        <div class="amount">RM {{ number_format($kjAllocatedAmount, 2) }}</div>
                        <div class="caption">
                            Used {{ number_format($kjUtilizationPercentage, 2) }}% · Total committed
                            {{ number_format($kjCommitmentPercentage, 2) }}%
                        </div>

                        @php
                            $kjUsedPct = $kjAllocatedAmount > 0 ? min(100, ($kjUsedAmount / $kjAllocatedAmount) * 100) : 0;
                            $kjReservedPct = $kjAllocatedAmount > 0 ? min(100, ($kjReservedAmount / $kjAllocatedAmount) * 100) : 0;
                            $kjAvailablePct = max(0, 100 - $kjUsedPct - $kjReservedPct);
                        @endphp

                        <div class="budget-position-track">
                            <span class="budget-used" style="width:{{ $kjUsedPct }}%"></span>
                            <span class="budget-reserved" style="width:{{ $kjReservedPct }}%"></span>
                            <span class="budget-available" style="width:{{ $kjAvailablePct }}%"></span>
                        </div>
                        <div class="budget-position-legend">
                            <div class="budget-position-item"><div class="name">Used</div><div class="number">RM {{ number_format($kjUsedAmount, 2) }}</div></div>
                            <div class="budget-position-item"><div class="name">Reserved</div><div class="number">RM {{ number_format($kjReservedAmount, 2) }}</div></div>
                            <div class="budget-position-item"><div class="name">Available</div><div class="number">RM {{ number_format($kjAvailableBalance, 2) }}</div></div>
                        </div>
                    </div>

                    <div class="budget-stat-grid">
                        <div class="budget-stat">
                            <small>Procurement Requests</small>
                            <strong>{{ number_format($kjTotalRequests) }}</strong>
                            <small>Requests submitted within the selected activity period.</small>
                        </div>
                        <div class="budget-stat">
                            <small>Final Approved Value</small>
                            <strong>RM {{ number_format($kjFinalApprovedValue, 2) }}</strong>
                            <small>Requests that have passed Bendahari final approval.</small>
                        </div>
                        <div class="budget-stat wide">
                            <small>Completed Procurements</small>
                            <strong>{{ number_format($kjCompletedProcurements) }}</strong>
                            <small>Requests currently at Completed workflow status.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="equal-two-column">
                    <div class="analysis-card">
                        <h4>Current Procurement Status</h4>
                        <p>Exact current workflow distribution of procurement requests in the selected period.</p>

                        @php
                            $kjStatusMax = max(1, (int) collect($kjStatusChartValues)->max());
                        @endphp

                        @if (empty($kjStatusChartValues))
                            <div class="empty-state" style="margin-top:12px;">No procurement records match the selected criteria.</div>
                        @else
                            <div class="role-bars">
                                @foreach ($kjStatusChartLabels as $index => $label)
                                    @php
                                        $value = (int) ($kjStatusChartValues[$index] ?? 0);
                                    @endphp
                                    <div class="role-bar-row">
                                        <div class="role-bar-label">{{ $label }}</div>
                                        <div class="role-bar-track"><span style="width:{{ ($value / $kjStatusMax) * 100 }}%"></span></div>
                                        <div class="role-bar-value">{{ $value }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="analysis-card">
                        <h4>KJ Approval Performance</h4>
                        <p>Decision activity made by the logged-in KJ during the selected period.</p>
                        <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:14px; margin-top:14px;">
                            <div>
                                <div class="metric-label">Approval Rate</div>
                                <div class="headline-value" style="font-size:30px;">{{ number_format($kjApprovalRate, 2) }}%</div>
                            </div>
                            <div class="muted-text" style="font-size:9.5px; text-align:right;">
                                {{ number_format($kjTotalDecisions) }} total decision(s)
                            </div>
                        </div>
                        <div class="mini-metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr)); margin-top:14px;">
                            <div class="mini-metric"><div class="metric-label">Approved</div><div class="value">{{ $kjApprovedDecisions }}</div></div>
                            <div class="mini-metric"><div class="metric-label">Rejected</div><div class="value">{{ $kjRejectedDecisions }}</div></div>
                            <div class="mini-metric"><div class="metric-label">Returned</div><div class="value">{{ $kjReturnedDecisions }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="analysis-card">
                    <h4>Approval Decisions by Month</h4>
                    <p>Monthly KJ decisions separated into Approved, Rejected and Returned outcomes.</p>
                    @if (!empty($kjApprovalMonthlyLabels))
                        <div class="chart-box"><canvas id="kjApprovalMonthlyChart"></canvas></div>
                    @else
                        <div class="empty-state" style="margin-top:12px;">No approval decisions match the selected criteria.</div>
                    @endif
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <h3>Department Procurement Register</h3>
                        <p>Current procurement records for department oversight.</p>
                    </div>
                </div>
                @if ($kjProcurementRecords->isEmpty())
                    <div class="empty-state">No procurement records were found for the selected criteria.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead><tr><th>Request</th><th>Submitted</th><th>Title</th><th>Requester</th><th>Estimated</th><th>Final Approved</th><th>Selected Vendor</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach ($kjProcurementRecords as $record)
                                    @php
                                        $isFinal = in_array($record->request_status, $kjFinalApprovedStatuses, true);
                                        $statusClass = match ($record->request_status) {
                                            'Rejected_KJ', 'Rejected_Bendahari' => 'badge-danger',
                                            'Pending_KJ', 'Pending_Edit', 'Approved_KJ' => 'badge-warning',
                                            'Approved_Bendahari', 'LO_Generated', 'Delivery_Received', 'Completed' => 'badge-success',
                                            default => 'badge-neutral',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="id-cell">REQ-{{ str_pad($record->procurement_request_id,4,'0',STR_PAD_LEFT) }}</td>
                                        <td>{{ $record->created_at->format('d M Y') }}</td>
                                        <td class="title-cell">{{ $record->request_title }}</td>
                                        <td>{{ $record->user?->user_name ?? 'N/A' }}</td>
                                        <td class="money-cell">RM {{ number_format((float)$record->estimated_amount,2) }}</td>
                                        <td class="money-cell">{{ $isFinal && $record->approved_amount !== null ? 'RM '.number_format((float)$record->approved_amount,2) : '—' }}</td>
                                        <td>{{ $record->selectedQuotation?->vendor?->company_name ?? 'Not Selected' }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ $kjStatusLabels[$record->request_status] ?? $record->request_status }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $kjProcurementRecords->links() }}</div>
                @endif
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <h3>KJ Decision Register</h3>
                        <p>Historical KJ decisions made by the currently logged-in KJ.</p>
                    </div>
                </div>
                @if ($kjApprovalRecords->isEmpty())
                    <div class="empty-state">No approval records were found for the selected criteria.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table">
                            <thead><tr><th>Request</th><th>Decision Date</th><th>Request Title</th><th>Requester</th><th>Decision</th><th>Remarks</th><th>Current Status</th></tr></thead>
                            <tbody>
                                @foreach ($kjApprovalRecords as $approval)
                                    @php
                                        $requestRecord = $approval->procurementRequest;
                                        $decisionClass = match ($approval->decision) {
                                            'Approved' => 'badge-success',
                                            'Rejected' => 'badge-danger',
                                            'Returned' => 'badge-warning',
                                            default => 'badge-neutral',
                                        };
                                        $currentStatus = $requestRecord?->request_status;
                                    @endphp
                                    <tr>
                                        <td class="id-cell">{{ $requestRecord ? 'REQ-'.str_pad($requestRecord->procurement_request_id,4,'0',STR_PAD_LEFT) : 'N/A' }}</td>
                                        <td>{{ $approval->created_at->format('d M Y') }}</td>
                                        <td class="title-cell">{{ $requestRecord?->request_title ?? 'N/A' }}</td>
                                        <td>{{ $requestRecord?->user?->user_name ?? 'N/A' }}</td>
                                        <td><span class="badge {{ $decisionClass }}">{{ $kjDecisionLabels[$approval->decision] ?? $approval->decision }}</span></td>
                                        <td class="remarks-cell">{{ $approval->remarks ?: 'No remarks recorded' }}</td>
                                        <td><span class="badge badge-neutral">{{ $currentStatus ? ($kjStatusLabels[$currentStatus] ?? $currentStatus) : 'N/A' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $kjApprovalRecords->links() }}</div>
                @endif
            </div>
        </section>

    {{-- =========================================================
         BENDAHARI - UNIVERSITY PROCUREMENT & FINANCIAL OPERATIONS
    ========================================================== --}}

    @elseif (
        auth()->user()->role === 'bendahari'
        && $selectedReportType === 'university_procurement_financial'
    )

        @php
            $selectedBendDepartment = $bendPtjId
                ? $bendDepartments->firstWhere('ptj_id', $bendPtjId)
                : null;
            $bendLifecycleTotal = max(1, array_sum($bendLifecycle));
            $bendAwaitingPct = $bendTotalOrders > 0 ? ($bendAwaitingDelivery / $bendTotalOrders) * 100 : 0;
            $bendPendingPct = $bendTotalOrders > 0 ? ($bendPaymentPending / $bendTotalOrders) * 100 : 0;
            $bendPaidPct = $bendTotalOrders > 0 ? ($bendPaidOrders / $bendTotalOrders) * 100 : 0;
            $bendUsedPct = $bendTotalAllocated > 0 ? min(100, ($bendTotalUsed / $bendTotalAllocated) * 100) : 0;
            $bendReservedPct = $bendTotalAllocated > 0 ? min(100, ($bendTotalReserved / $bendTotalAllocated) * 100) : 0;
            $bendAvailablePct = max(0, 100 - $bendUsedPct - $bendReservedPct);
        @endphp

        <section class="report-surface report-result">
            <div class="report-heading">
                <div>
                    <h2>University Procurement & Financial Operations Report</h2>
                    <p>
                        {{ $selectedBendDepartment?->department_name ?? 'All PTJ Departments' }} ·
                        procurement and Local Order activity {{ \Carbon\Carbon::parse($bendDateFrom)->format('d M Y') }}–{{ \Carbon\Carbon::parse($bendDateTo)->format('d M Y') }} · Budget Year {{ $bendBudgetYear }}
                    </p>
                </div>
                <span class="report-live-badge"><span class="report-live-dot"></span>Live UCPMS Data</span>
            </div>

            <div class="scope-row">
                <span class="scope-pill"><strong>Department:</strong> {{ $selectedBendDepartment?->department_name ?? 'All PTJ Departments' }}</span>
                <span class="scope-pill"><strong>Budget Year:</strong> {{ $bendBudgetYear }}</span>
                <span class="scope-pill"><strong>Procurement Date:</strong> Request Submission Date</span>
                <span class="scope-pill"><strong>Order Date:</strong> Local Order Date</span>
            </div>
            <div class="scope-note">
                Budget Year controls the annual PTJ budget figures. Date From and Date To control procurement submissions and Local Order activity for the operational sections.
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">University executive view</div>
                        <h3>Procurement & Financial Snapshot</h3>
                        <p>Only the most important university-level procurement and financial indicators.</p>
                    </div>
                </div>

                <div class="headline-grid">
                    <div class="headline-primary">
                        <div class="headline-label">Final Approved Procurement Value</div>
                        <div class="headline-value">RM {{ number_format($bendFinalApprovedValue, 2) }}</div>
                        <div class="headline-note">{{ number_format($bendFinalApprovedRequests) }} request(s) have passed final Bendahari approval in this procurement cohort.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Procurement Requests</div>
                        <div class="metric-value">{{ number_format($bendTotalRequests) }}</div>
                        <div class="metric-note">Requests submitted within the selected procurement period.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Annual Budget Allocated</div>
                        <div class="metric-value small">RM {{ number_format($bendTotalAllocated, 2) }}</div>
                        <div class="metric-note">Budget records for {{ $bendBudgetYear }} in the selected PTJ scope.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Payment Pending Value</div>
                        <div class="metric-value small">RM {{ number_format($bendPaymentPendingValue, 2) }}</div>
                        <div class="metric-note">Delivered Local Orders still marked Pending for payment.</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="equal-two-column">
                    <div class="analysis-card">
                        <h4>{{ $bendBudgetYear }} University Budget Position</h4>
                        <p>Current annual distribution across Used, Reserved and Available budget.</p>

                        @if ($bendTotalAllocated > 0)
                            <div class="budget-position-track">
                                <span class="budget-used" style="width:{{ $bendUsedPct }}%"></span>
                                <span class="budget-reserved" style="width:{{ $bendReservedPct }}%"></span>
                                <span class="budget-available" style="width:{{ $bendAvailablePct }}%"></span>
                            </div>
                            <div class="budget-position-legend">
                                <div class="budget-position-item"><div class="name">Used · {{ number_format($bendOverallUtilization,2) }}%</div><div class="number">RM {{ number_format($bendTotalUsed,2) }}</div></div>
                                <div class="budget-position-item"><div class="name">Reserved</div><div class="number">RM {{ number_format($bendTotalReserved,2) }}</div></div>
                                <div class="budget-position-item"><div class="name">Available</div><div class="number">RM {{ number_format($bendTotalAvailable,2) }}</div></div>
                            </div>
                        @else
                            <div class="empty-state" style="margin-top:12px;">No budget allocation records match this scope for {{ $bendBudgetYear }}.</div>
                        @endif
                    </div>

                    <div class="analysis-card">
                        <h4>Order, Delivery & Payment Position</h4>
                        <p>Current mutually exclusive position of Local Orders in the selected Local Order date range.</p>

                        @if ($bendTotalOrders > 0)
                            <div class="position-bar">
                                <span class="position-awaiting" style="width:{{ $bendAwaitingPct }}%"></span>
                                <span class="position-pending" style="width:{{ $bendPendingPct }}%"></span>
                                <span class="position-paid" style="width:{{ $bendPaidPct }}%"></span>
                            </div>
                            <div class="position-legend">
                                <div class="position-item"><div class="label"><span class="dot position-awaiting"></span>Awaiting Delivery</div><div class="number">{{ $bendAwaitingDelivery }}</div><div class="percent">Generated LO with no delivery confirmation.</div></div>
                                <div class="position-item"><div class="label"><span class="dot position-pending"></span>Payment Pending</div><div class="number">{{ $bendPaymentPending }}</div><div class="percent">RM {{ number_format($bendPaymentPendingValue,2) }}</div></div>
                                <div class="position-item"><div class="label"><span class="dot position-paid"></span>Paid</div><div class="number">{{ $bendPaidOrders }}</div><div class="percent">RM {{ number_format($bendPaidValue,2) }}</div></div>
                            </div>
                        @else
                            <div class="empty-state" style="margin-top:12px;">No Local Orders match the selected period and PTJ scope.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="equal-two-column">
                    <div class="analysis-card">
                        <h4>University Procurement Lifecycle</h4>
                        <p>Executive grouping of current request positions in the selected procurement cohort.</p>
                        <div class="lifecycle-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); margin-top:12px;">
                            @foreach ([
                                ['Under Review', $bendLifecycle['under_review'], 'blue'],
                                ['Amendment', $bendLifecycle['amendment'], 'warning'],
                                ['Rejected', $bendLifecycle['rejected'], 'danger'],
                                ['In Execution', $bendLifecycle['execution'], 'purple'],
                                ['Completed', $bendLifecycle['completed'], 'success'],
                            ] as [$label, $count, $class])
                                <div class="lifecycle-card {{ $class }}">
                                    <div class="lifecycle-name">{{ $label }}</div>
                                    <div class="lifecycle-count">{{ number_format($count) }}</div>
                                    <div class="tiny-progress"><span style="width:{{ ($count / $bendLifecycleTotal) * 100 }}%"></span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="analysis-card">
                        <h4>Procurement Activity by Submission Month</h4>
                        <p>Total request volume compared with final-approved requests, grouped by original request submission month.</p>
                        @if ($bendTotalRequests > 0)
                            <div class="chart-box compact"><canvas id="bendMonthlyChart"></canvas></div>
                        @else
                            <div class="empty-state" style="margin-top:12px;">No procurement activity matches the selected period.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">Department comparison</div>
                        <h3>PTJ Financial & Operations Portfolio</h3>
                        <p>One consolidated comparison of budget utilization, procurement value and payment exposure.</p>
                    </div>
                </div>

                @php
                    $visibleBendPtjPortfolio = $bendPtjPortfolio
                        ->filter(function ($row) {
                            return (float) $row->used_amount > 0
                                || (float) $row->reserved_amount > 0
                                || (int) $row->request_count > 0
                                || (float) $row->final_approved_value > 0
                                || (int) $row->completed_count > 0
                                || (int) $row->order_count > 0
                                || (float) $row->pending_payment_value > 0
                                || (float) $row->paid_value > 0;
                        })
                        ->values();
                @endphp

                @if ($visibleBendPtjPortfolio->isEmpty())
                    <div class="empty-state">No PTJ with a budget allocation, procurement activity or Local Order activity matches the selected scope.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead>
                                <tr>
                                    <th>PTJ Department</th>
                                    <th>Budget Utilization</th>
                                    <th>Requests</th>
                                    <th>Final Approved Value</th>
                                    <th>Completed</th>
                                    <th>Orders</th>
                                    <th>Pending Payment</th>
                                    <th>Paid Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($visibleBendPtjPortfolio as $row)
                                    <tr>
                                        <td class="title-cell">{{ $row->department_name }}<div class="muted-text">{{ $row->department_code }}</div></td>
                                        <td class="progress-cell">
                                            <div class="progress-meta"><span>{{ number_format($row->utilization_percentage,2) }}%</span><span>RM {{ number_format($row->used_amount,0) }}</span></div>
                                            <div class="progress-track success"><span style="width:{{ min(100,max(0,$row->utilization_percentage)) }}%"></span></div>
                                        </td>
                                        <td>{{ number_format($row->request_count) }}</td>
                                        <td class="money-cell">RM {{ number_format($row->final_approved_value,2) }}</td>
                                        <td>{{ number_format($row->completed_count) }}</td>
                                        <td>{{ number_format($row->order_count) }}</td>
                                        <td class="money-cell">RM {{ number_format($row->pending_payment_value,2) }}</td>
                                        <td class="money-cell">RM {{ number_format($row->paid_value,2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <h3>University Procurement Register</h3>
                        <p>Supporting procurement records for the selected university or PTJ reporting scope.</p>
                    </div>
                </div>

                @if ($bendProcurementRecords->isEmpty())
                    <div class="empty-state">No procurement records were found for the selected scope.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead><tr><th>Request</th><th>Submitted</th><th>PTJ</th><th>Request Title</th><th>Requester</th><th>Estimated</th><th>Final Approved</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach ($bendProcurementRecords as $record)
                                    @php
                                        $isFinal = in_array($record->request_status, $bendFinalApprovedStatuses, true);
                                        $statusClass = match ($record->request_status) {
                                            'Rejected_KJ', 'Rejected_Bendahari' => 'badge-danger',
                                            'Pending_KJ', 'Pending_Edit', 'Approved_KJ' => 'badge-warning',
                                            'Approved_Bendahari', 'LO_Generated', 'Delivery_Received', 'Completed' => 'badge-success',
                                            default => 'badge-neutral',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="id-cell">REQ-{{ str_pad($record->procurement_request_id,4,'0',STR_PAD_LEFT) }}</td>
                                        <td>{{ $record->created_at->format('d M Y') }}</td>
                                        <td>{{ $record->ptjDepartment?->department_name ?? 'N/A' }}</td>
                                        <td class="title-cell">{{ $record->request_title }}</td>
                                        <td>{{ $record->user?->user_name ?? 'N/A' }}</td>
                                        <td class="money-cell">RM {{ number_format((float)$record->estimated_amount,2) }}</td>
                                        <td class="money-cell">{{ $isFinal && $record->approved_amount !== null ? 'RM '.number_format((float)$record->approved_amount,2) : '—' }}</td>
                                        <td><span class="badge {{ $statusClass }}">{{ $bendStatusLabels[$record->request_status] ?? $record->request_status }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $bendProcurementRecords->links() }}</div>
                @endif
            </div>
        </section>

    {{-- =========================================================
         BENDAHARI - PROCUREMENT & APPROVAL AUDIT
    ========================================================== --}}

    @elseif (
        auth()->user()->role === 'bendahari'
        && $selectedReportType === 'procurement_approval_audit'
    )

        @php
            $selectedAuditDepartment = $bendAuditPtjId
                ? $bendAuditDepartments->firstWhere('ptj_id', $bendAuditPtjId)
                : null;
        @endphp

        <section class="report-surface report-result">
            <div class="report-heading">
                <div>
                    <h2>Procurement & Approval Audit Report</h2>
                    <p>
                        Bendahari decisions {{ \Carbon\Carbon::parse($bendAuditDateFrom)->format('d M Y') }}–{{ \Carbon\Carbon::parse($bendAuditDateTo)->format('d M Y') }} · formal decision, compliance and audit evidence
                    </p>
                </div>
                <span class="report-live-badge"><span class="report-live-dot"></span>Read-only Audit Evidence</span>
            </div>

            <div class="scope-row">
                <span class="scope-pill"><strong>Department:</strong> {{ $selectedAuditDepartment?->department_name ?? 'All PTJ Departments' }}</span>
                <span class="scope-pill"><strong>Decision:</strong> {{ $bendAuditDecision === 'all' ? 'All Decisions' : ($bendAuditDecisionLabels[$bendAuditDecision] ?? $bendAuditDecision) }}</span>
                <span class="scope-pill"><strong>Approval Scope:</strong> Decisions made by logged-in Bendahari</span>
                <span class="scope-pill"><strong>Compliance:</strong> Current-record integrity checks</span>
            </div>

            <div class="report-section">
                <div class="headline-grid three">
                    <div class="headline-primary">
                        <div class="headline-label">Compliance Exceptions</div>
                        <div class="headline-value">{{ number_format($bendAuditExceptionCount) }}</div>
                        <div class="headline-note">Unique procurement requests flagged by the documented/current checks below.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Bendahari Decisions</div>
                        <div class="metric-value">{{ number_format($bendAuditTotalDecisions) }}</div>
                        <div class="metric-note">Decisions recorded in the selected decision period.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Requests Checked</div>
                        <div class="metric-value">{{ number_format($bendAuditCheckedRequestCount) }}</div>
                        <div class="metric-note">Unique requests represented in the selected decision scope.</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">Compliance control</div>
                        <h3>Current-Record Compliance Checks</h3>
                        <p>Focused checks using requirements already implemented in UCPMS.</p>
                    </div>
                </div>

                <div class="compliance-summary">
                    <div class="compliance-summary-row">
                        <div class="compliance-summary-name">Direct Purchase Limit</div>
                        <div class="compliance-summary-rule">Estimated or approved value must not exceed the RM50,000 Direct Purchase limit.</div>
                        <div class="compliance-result"><span class="badge {{ $bendAuditDirectPurchaseExceptions->isEmpty() ? 'badge-success' : 'badge-danger' }}">{{ $bendAuditDirectPurchaseExceptions->count() }} exception(s)</span></div>
                    </div>
                    <div class="compliance-summary-row">
                        <div class="compliance-summary-name">Minimum Quotations</div>
                        <div class="compliance-summary-rule">Selected procurement requests must retain at least three quotation records.</div>
                        <div class="compliance-result"><span class="badge {{ $bendAuditQuotationExceptions->isEmpty() ? 'badge-success' : 'badge-danger' }}">{{ $bendAuditQuotationExceptions->count() }} exception(s)</span></div>
                    </div>
                    <div class="compliance-summary-row">
                        <div class="compliance-summary-name">Final Approval Integrity</div>
                        <div class="compliance-summary-rule">Final-approved records must contain a selected quotation and approved amount.</div>
                        <div class="compliance-result"><span class="badge {{ $bendAuditIntegrityExceptions->isEmpty() ? 'badge-success' : 'badge-danger' }}">{{ $bendAuditIntegrityExceptions->count() }} exception(s)</span></div>
                    </div>
                </div>
            </div>

            @if ($bendAuditExceptionCount > 0)
                <div class="report-section">
                    <div class="attention-panel warning">
                        <div class="attention-icon">!</div>
                        <div>
                            <div class="attention-title">Compliance exceptions require review</div>
                            <div class="attention-copy">The records below are current-data exceptions. They are not automatic allegations of misconduct and should be reviewed against the underlying procurement record.</div>
                        </div>
                    </div>

                    <div class="exception-list" style="margin-top:10px;">
                        @foreach ($bendAuditDirectPurchaseExceptions as $record)
                            <div class="exception-row">
                                <strong>REQ-{{ str_pad($record->procurement_request_id,4,'0',STR_PAD_LEFT) }}</strong>
                                <div class="exception-title">Direct Purchase limit exception · {{ $record->request_title }}</div>
                                <div class="exception-ptj">{{ $record->ptjDepartment?->department_name ?? 'N/A' }}</div>
                            </div>
                        @endforeach
                        @foreach ($bendAuditQuotationExceptions as $record)
                            <div class="exception-row">
                                <strong>REQ-{{ str_pad($record->procurement_request_id,4,'0',STR_PAD_LEFT) }}</strong>
                                <div class="exception-title">Only {{ $record->quotations_count }} quotation(s) recorded · {{ $record->request_title }}</div>
                                <div class="exception-ptj">{{ $record->ptjDepartment?->department_name ?? 'N/A' }}</div>
                            </div>
                        @endforeach
                        @foreach ($bendAuditIntegrityExceptions as $record)
                            <div class="exception-row">
                                <strong>REQ-{{ str_pad($record->procurement_request_id,4,'0',STR_PAD_LEFT) }}</strong>
                                <div class="exception-title">Final approval record integrity exception · {{ $record->request_title }}</div>
                                <div class="exception-ptj">{{ $record->ptjDepartment?->department_name ?? 'N/A' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="report-section">
                    <div class="attention-panel">
                        <div class="attention-icon">✓</div>
                        <div>
                            <div class="attention-title">No procurement compliance exception detected</div>
                            <div class="attention-copy">The requests in this selected decision scope passed the three current-record checks implemented in this report.</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <h3>Bendahari Decision Register</h3>
                        <p>Formal approval/rejection evidence for the currently logged-in Bendahari officer.</p>
                    </div>
                </div>

                @if ($bendAuditDecisionRecords->isEmpty())
                    <div class="empty-state">No Bendahari decision records were found for the selected criteria.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead><tr><th>Request</th><th>Decision Date</th><th>PTJ</th><th>Request Title</th><th>Requester</th><th>Decision</th><th>Selected Vendor</th><th>Remarks</th></tr></thead>
                            <tbody>
                                @foreach ($bendAuditDecisionRecords as $approval)
                                    @php
                                        $req = $approval->procurementRequest;
                                        $decisionClass = $approval->decision === 'Approved' ? 'badge-success' : 'badge-danger';
                                    @endphp
                                    <tr>
                                        <td class="id-cell">{{ $req ? 'REQ-'.str_pad($req->procurement_request_id,4,'0',STR_PAD_LEFT) : 'N/A' }}</td>
                                        <td>{{ $approval->created_at->format('d M Y') }}</td>
                                        <td>{{ $req?->ptjDepartment?->department_name ?? 'N/A' }}</td>
                                        <td class="title-cell">{{ $req?->request_title ?? 'N/A' }}</td>
                                        <td>{{ $req?->user?->user_name ?? 'N/A' }}</td>
                                        <td><span class="badge {{ $decisionClass }}">{{ $bendAuditDecisionLabels[$approval->decision] ?? $approval->decision }}</span></td>
                                        <td>{{ $req?->selectedQuotation?->vendor?->company_name ?? 'Not Selected' }}</td>
                                        <td class="remarks-cell">{{ $approval->remarks ?: 'No remarks recorded' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $bendAuditDecisionRecords->links() }}</div>
                @endif
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <h3>Procurement Audit Trail</h3>
                        <p>Timestamped UCPMS actions linked to the procurement requests represented in the decision scope.</p>
                    </div>
                </div>

                @if ($bendAuditRecords->isEmpty())
                    <div class="empty-state">No audit trail records were found for the selected criteria.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead><tr><th>Date / Time</th><th>Request</th><th>PTJ</th><th>Actor</th><th>Role</th><th>Action</th><th>Description</th></tr></thead>
                            <tbody>
                                @foreach ($bendAuditRecords as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</td>
                                        <td class="id-cell">{{ $log->procurement_request_id ? 'REQ-'.str_pad($log->procurement_request_id,4,'0',STR_PAD_LEFT) : '—' }}</td>
                                        <td>{{ $log->department_name ?? 'N/A' }}</td>
                                        <td>{{ $log->actor_name ?? 'Unknown' }}<div class="muted-text">{{ $log->actor_staff_id ?? '' }}</div></td>
                                        <td>{{ $log->actor_role ? ucfirst($log->actor_role) : 'N/A' }}</td>
                                        <td><span class="badge badge-info">{{ $log->action }}</span></td>
                                        <td class="remarks-cell">{{ $log->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $bendAuditRecords->links() }}</div>
                @endif
            </div>
        </section>

    {{-- =========================================================
         ADMIN - USER & SYSTEM OVERVIEW
    ========================================================== --}}

    @elseif (
        auth()->user()->role === 'admin'
        && $selectedReportType === 'user_system_overview'
    )

        @php
            $selectedAdminDepartment = $adminPtjId
                ? $adminDepartments->firstWhere('ptj_id', $adminPtjId)
                : null;
        @endphp

        <section class="report-surface report-result">
            <div class="report-heading">
                <div>
                    <h2>User & System Overview Report</h2>
                    <p>Current UCPMS account structure, role assignment and PTJ mapping for System Administrator oversight.</p>
                </div>
                <span class="report-live-badge"><span class="report-live-dot"></span>Live UCPMS Data</span>
            </div>

            <div class="scope-row">
                <span class="scope-pill"><strong>User Role:</strong> {{ $adminRoleFilter === 'all' ? 'All Roles' : ($adminRoleLabels[$adminRoleFilter] ?? $adminRoleFilter) }}</span>
                <span class="scope-pill"><strong>Account Status:</strong> {{ $adminAccountStatus === 'all' ? 'All Accounts' : ucfirst($adminAccountStatus) }}</span>
                <span class="scope-pill"><strong>PTJ:</strong> {{ $selectedAdminDepartment?->department_name ?? 'All / Not Applicable' }}</span>
            </div>

            <div class="report-section">
                <div class="headline-grid">
                    <div class="headline-primary">
                        <div class="headline-label">Active Account Rate</div>
                        <div class="headline-value">{{ number_format($adminActiveRate,2) }}%</div>
                        <div class="headline-note">{{ number_format($adminActiveUsers) }} active of {{ number_format($adminTotalUsers) }} account(s) matching the current filters.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Total Users</div>
                        <div class="metric-value">{{ number_format($adminTotalUsers) }}</div>
                        <div class="metric-note">Accounts in the filtered user population.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Inactive Accounts</div>
                        <div class="metric-value">{{ number_format($adminInactiveUsers) }}</div>
                        <div class="metric-note">Accounts currently unable to authenticate.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Protected Admin Accounts</div>
                        <div class="metric-value">{{ number_format($adminProtectedAdminUsers) }}</div>
                        <div class="metric-note">Protected System Administrator account(s) visible in this filtered scope.</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="equal-two-column">
                    <div class="analysis-card">
                        <h4>User Role Distribution</h4>
                        <p>Current role composition after applying the selected filters.</p>
                        @if ($adminRoleDistribution->isEmpty())
                            <div class="empty-state" style="margin-top:12px;">No user accounts match the selected filters.</div>
                        @else
                            <div class="role-bars">
                                @foreach ($adminRoleDistribution as $row)
                                    <div class="role-bar-row">
                                        <div class="role-bar-label">{{ $row->label }}</div>
                                        <div class="role-bar-track"><span style="width:{{ min(100,max(0,$row->percentage)) }}%"></span></div>
                                        <div class="role-bar-value">{{ $row->total }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="analysis-card">
                        <h4>PTJ Account Mapping</h4>
                        <p>PTJ and KJ account distribution for departments represented by the selected filters.</p>
                        @if ($adminDepartmentDistribution->isEmpty())
                            <div class="empty-state" style="margin-top:12px;">No PTJ-mapped accounts match the selected filters.</div>
                        @else
                            <div class="report-table-wrap" style="margin-top:12px;">
                                <table class="report-table" style="min-width:520px;">
                                    <thead><tr><th>PTJ Department</th><th>PTJ Staff</th><th>KJ</th><th>Total</th></tr></thead>
                                    <tbody>
                                        @foreach ($adminDepartmentDistribution as $row)
                                            <tr>
                                                <td class="title-cell">{{ $row->department_name }}<div class="muted-text">{{ $row->department_code }}</div></td>
                                                <td>{{ $row->ptj_users }}</td>
                                                <td>{{ $row->kj_users }}</td>
                                                <td><strong>{{ $row->total_users }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">Account governance</div>
                        <h3>User Account Register</h3>
                        <p>Current account, role and PTJ assignments. This report is read-only.</p>
                    </div>
                </div>

                @if ($adminUsers->isEmpty())
                    <div class="empty-state">No user accounts match the selected filters.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead><tr><th>Staff ID</th><th>User Name</th><th>Email</th><th>Role</th><th>PTJ Department</th><th>Account Status</th><th>Registered</th></tr></thead>
                            <tbody>
                                @foreach ($adminUsers as $account)
                                    <tr>
                                        <td class="id-cell">{{ $account->staff_id }}</td>
                                        <td class="title-cell">{{ $account->user_name }}</td>
                                        <td>{{ $account->email }}</td>
                                        <td><span class="badge badge-info">{{ $adminRoleLabels[$account->role] ?? $account->role }}</span></td>
                                        <td>{{ $account->ptjDepartment?->department_name ?? 'Not Applicable' }}</td>
                                        <td><span class="badge {{ $account->is_active ? 'badge-success' : 'badge-danger' }}">{{ $account->is_active ? 'Active' : 'Inactive' }}</span></td>
                                        <td>{{ $account->created_at?->format('d M Y') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $adminUsers->links() }}</div>
                @endif
            </div>
        </section>

    {{-- =========================================================
         ADMIN - SYSTEM AUDIT
    ========================================================== --}}

    @elseif (
        auth()->user()->role === 'admin'
        && $selectedReportType === 'system_audit'
    )

        <section class="report-surface report-result">
            <div class="report-heading">
                <div>
                    <h2>System Audit Report</h2>
                    <p>
                        Timestamped UCPMS audit events {{ \Carbon\Carbon::parse($adminAuditDateFrom)->format('d M Y') }}–{{ \Carbon\Carbon::parse($adminAuditDateTo)->format('d M Y') }}
                    </p>
                </div>
                <span class="report-live-badge"><span class="report-live-dot"></span>Read-only Audit Evidence</span>
            </div>

            <div class="scope-row">
                <span class="scope-pill"><strong>Audit Category:</strong> {{ $adminAuditCategoryLabels[$adminAuditCategory] ?? $adminAuditCategory }}</span>
                <span class="scope-pill"><strong>Period:</strong> {{ \Carbon\Carbon::parse($adminAuditDateFrom)->format('d M Y') }}–{{ \Carbon\Carbon::parse($adminAuditDateTo)->format('d M Y') }}</span>
                <span class="scope-pill"><strong>Scope:</strong> System-wide audit log</span>
            </div>

            <div class="report-section">
                <div class="headline-grid five">
                    <div class="headline-primary">
                        <div class="headline-label">Audit Events</div>
                        <div class="headline-value">{{ number_format($adminAuditTotalEvents) }}</div>
                        <div class="headline-note">Timestamped audit records matching the selected period and category.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">User Management</div>
                        <div class="metric-value">{{ number_format($adminAuditUserManagementEvents) }}</div>
                        <div class="metric-note">Create, assignment and account status changes.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Approval Actions</div>
                        <div class="metric-value">{{ number_format($adminAuditApprovalEvents) }}</div>
                        <div class="metric-note">KJ and Bendahari approval decisions.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Order / Delivery</div>
                        <div class="metric-value">{{ number_format($adminAuditExecutionEvents) }}</div>
                        <div class="metric-note">Local Order generation and delivery confirmation.</div>
                    </div>
                    <div class="metric-block">
                        <div class="metric-label">Payment Actions</div>
                        <div class="metric-value">{{ number_format($adminAuditPaymentEvents) }}</div>
                        <div class="metric-note">Bendahari payment completion audit events.</div>
                    </div>
                </div>
            </div>

            <div class="report-section">
                <div class="analysis-card">
                    <h4>Most Frequent Audit Actions</h4>
                    <p>Top action types within the currently filtered audit scope.</p>
                    @if ($adminAuditActionDistribution->isEmpty())
                        <div class="empty-state" style="margin-top:12px;">No audit actions match the selected criteria.</div>
                    @else
                        <div class="role-bars">
                            @foreach ($adminAuditActionDistribution as $row)
                                @php
                                    $barWidth = $adminAuditMaxActionCount > 0
                                        ? ((int) $row->total / $adminAuditMaxActionCount) * 100
                                        : 0;
                                @endphp
                                <div class="role-bar-row" style="grid-template-columns:minmax(220px,1.4fr) 2fr 55px;">
                                    <div class="role-bar-label">{{ $row->action }}</div>
                                    <div class="role-bar-track"><span style="width:{{ $barWidth }}%"></span></div>
                                    <div class="role-bar-value">{{ $row->total }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="report-section">
                <div class="report-section-title">
                    <div>
                        <div class="report-section-kicker">System evidence</div>
                        <h3>System Audit Trail</h3>
                        <p>Chronological read-only evidence of significant UCPMS actions.</p>
                    </div>
                </div>

                @if ($adminAuditRecords->isEmpty())
                    <div class="empty-state">No audit records were found for the selected period and category.</div>
                @else
                    <div class="report-table-wrap">
                        <table class="report-table wide">
                            <thead><tr><th>Date / Time</th><th>Actor</th><th>Role</th><th>Request</th><th>PTJ</th><th>Action</th><th>Description</th></tr></thead>
                            <tbody>
                                @foreach ($adminAuditRecords as $log)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</td>
                                        <td>{{ $log->actor_name ?? 'Unknown' }}<div class="muted-text">{{ $log->actor_staff_id ?? '' }}</div></td>
                                        <td>{{ $log->actor_role ? ($adminAuditRoleLabels[$log->actor_role] ?? ucfirst($log->actor_role)) : 'N/A' }}</td>
                                        <td class="id-cell">{{ $log->procurement_request_id ? 'REQ-'.str_pad($log->procurement_request_id,4,'0',STR_PAD_LEFT) : '—' }}</td>
                                        <td>{{ $log->department_name ?? '—' }}</td>
                                        <td><span class="badge badge-neutral">{{ $log->action }}</span></td>
                                        <td class="remarks-cell">{{ $log->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">{{ $adminAuditRecords->links() }}</div>
                @endif
            </div>
        </section>

    @endif

@endsection

@section('page-script')

    @if (($reportGenerated ?? false))
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const textColor = '#60708b';
                const gridColor = '#edf1f6';
                const primaryColor = getComputedStyle(document.documentElement)
                    .getPropertyValue('--primary')
                    .trim() || '#1769e8';

                /* PTJ monthly procurement activity */
                const ptjCanvas = document.getElementById('ptjMonthlyChart');
                if (ptjCanvas) {
                    new Chart(ptjCanvas, {
                        data: {
                            labels: @json($ptjMonthlyLabels ?? []),
                            datasets: [
                                {
                                    type: 'bar',
                                    label: 'Submitted Requests',
                                    data: @json($ptjMonthlyRequestValues ?? []),
                                    backgroundColor: primaryColor,
                                    borderRadius: 6,
                                    maxBarThickness: 45
                                },
                                {
                                    type: 'line',
                                    label: 'Final-Approved Requests',
                                    data: @json($ptjMonthlyFinalApprovedValues ?? []),
                                    borderColor: '#10b981',
                                    backgroundColor: '#10b981',
                                    borderWidth: 2,
                                    pointRadius: 3,
                                    tension: .28
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { color: textColor, usePointStyle: true, padding: 14 } }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: textColor } },
                                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, precision: 0, stepSize: 1 } }
                            }
                        }
                    });
                }

                /* KJ monthly approval decisions */
                const kjCanvas = document.getElementById('kjApprovalMonthlyChart');
                if (kjCanvas) {
                    new Chart(kjCanvas, {
                        type: 'bar',
                        data: {
                            labels: @json($kjApprovalMonthlyLabels ?? []),
                            datasets: [
                                { label: 'Approved', data: @json($kjApprovalMonthlyApproved ?? []), backgroundColor: '#10b981', borderRadius: 5, maxBarThickness: 38 },
                                { label: 'Rejected', data: @json($kjApprovalMonthlyRejected ?? []), backgroundColor: '#ef4444', borderRadius: 5, maxBarThickness: 38 },
                                { label: 'Returned for Edit', data: @json($kjApprovalMonthlyReturned ?? []), backgroundColor: '#f59e0b', borderRadius: 5, maxBarThickness: 38 }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { color: textColor, usePointStyle: true, padding: 14 } }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: textColor } },
                                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, precision: 0, stepSize: 1 } }
                            }
                        }
                    });
                }

                /* Bendahari monthly university procurement activity */
                const bendCanvas = document.getElementById('bendMonthlyChart');
                if (bendCanvas) {
                    new Chart(bendCanvas, {
                        data: {
                            labels: @json($bendMonthlyLabels ?? []),
                            datasets: [
                                {
                                    type: 'bar',
                                    label: 'Submitted Requests',
                                    data: @json($bendMonthlyRequestValues ?? []),
                                    backgroundColor: primaryColor,
                                    borderRadius: 6,
                                    maxBarThickness: 42
                                },
                                {
                                    type: 'line',
                                    label: 'Final-Approved Requests',
                                    data: @json($bendMonthlyFinalApprovedValues ?? []),
                                    borderColor: '#10b981',
                                    backgroundColor: '#10b981',
                                    borderWidth: 2,
                                    pointRadius: 3,
                                    tension: .28
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { color: textColor, usePointStyle: true, padding: 14 } }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: textColor } },
                                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, precision: 0, stepSize: 1 } }
                            }
                        }
                    });
                }
            });
        </script>
    @endif

@endsection