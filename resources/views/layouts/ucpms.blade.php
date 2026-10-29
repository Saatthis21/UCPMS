@php
    $user = auth()->user();
    $role = $user->role;

    /*
    |--------------------------------------------------------------------------
    | UCPMS Role Themes
    |--------------------------------------------------------------------------
    | PTJ        = Blue / Teal
    | KJ         = Red / Pink
    | Bendahari  = Green
    | Admin      = Purple / Lavender
    */

    if ($role === 'kj') {

        $primary = '#c9152d';
        $primaryDark = '#a80f24';
        $secondary = '#e63950';

        $soft = '#fff0f2';
        $softStrong = '#ffe4e8';

        $roleLabel = 'Ketua Jabatan (KJ)';

    } elseif ($role === 'bendahari') {

        $primary = '#19843a';
        $primaryDark = '#12682d';
        $secondary = '#58b947';

        $soft = '#eff8ef';
        $softStrong = '#def1dd';

        $roleLabel = 'Bendahari';

    } elseif ($role === 'admin') {

        $primary = '#6d28d9';
        $primaryDark = '#5520ad';
        $secondary = '#9b5de5';

        $soft = '#f5f0ff';
        $softStrong = '#eadcff';

        $roleLabel = 'System Administrator';

    } else {

        $primary = '#1769e8';
        $primaryDark = '#1256c4';
        $secondary = '#18b8b2';

        $soft = '#edf3ff';
        $softStrong = '#e0f2fe';

        $roleLabel = 'PTJ Staff';
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'UCPMS')
    </title>


    <style>

        :root {

            --primary: {{ $primary }};
            --primary-dark: {{ $primaryDark }};
            --secondary: {{ $secondary }};

            --primary-soft: {{ $soft }};
            --primary-soft-strong: {{ $softStrong }};

            --navy: #17233c;
            --text: #24324a;
            --muted: #718096;

            --background: #f5f8fc;
            --surface: #ffffff;
            --border: #e8edf5;

            --warning: #f59e0b;
            --warning-soft: #fff4d8;

            --success: #0f9f6e;
            --success-soft: #e7f8f1;

            --danger: #dc3545;
            --danger-soft: #fdecee;

            --shadow:
                0 10px 30px rgba(30, 64, 175, 0.07);
        }


        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            min-height: 100vh;

            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Arial,
                sans-serif;

            background: var(--background);

            color: var(--text);
        }


        a {
            text-decoration: none;
            color: inherit;
        }


        button,
        input,
        select,
        textarea {
            font: inherit;
        }


        /* =========================================================
           TOP ACCENT
        ========================================================= */

        .top-accent {

            height: 4px;

            background:
                linear-gradient(
                    90deg,
                    var(--primary),
                    var(--secondary)
                );
        }


        /* =========================================================
           NAVBAR
        ========================================================= */

        .navbar {

            min-height: 90px;

            background: #ffffff;

            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;

            padding: 0 32px;

            position: sticky;
            top: 0;

            z-index: 1000;
        }


        .navbar-inner {

            width: 100%;
            max-width: 1600px;

            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 22px;
        }


        /* =========================================================
           BRAND
        ========================================================= */

        .brand {

            min-width: 245px;

            display: flex;
            align-items: center;
        }


        .brand-icon {
            width: 46px;
            height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            background: transparent;
            box-shadow: none;
        }

        .brand-icon img {
            width: 46px;
            height: 46px;

            object-fit: contain;
            display: block;
        }


        .brand-name {

            font-size: 25px;
            font-weight: 800;

            letter-spacing: -0.5px;

            color: var(--navy);
        }


        .brand-description {

            margin-top: 2px;

            font-size: 10px;
            line-height: 1.4;

            color: var(--muted);
        }


        /* =========================================================
           NAVIGATION
        ========================================================= */

        .main-nav {

            flex: 1;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 5px;
        }


        .nav-link {

            display: flex;
            align-items: center;

            gap: 8px;

            padding: 11px 12px;

            border-radius: 10px;

            font-size: 13px;
            font-weight: 500;

            color: #4a5873;

            white-space: nowrap;

            transition: 0.2s ease;
        }


        .nav-link svg {

            width: 19px;
            height: 19px;

            flex-shrink: 0;
        }


        a.nav-link:hover {

            background: var(--primary-soft);

            color: var(--primary);
        }


        .nav-link.active {

            background: var(--primary-soft);

            color: var(--primary);

            font-weight: 700;
        }


        .nav-link.disabled {

            cursor: default;
        }


        /* =========================================================
           USER AREA
        ========================================================= */

        .navbar-user {

            min-width: 235px;

            display: flex;
            align-items: center;
            justify-content: flex-end;

            gap: 11px;
        }


        .user-avatar {

            width: 44px;
            height: 44px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--secondary)
                );

            color: #ffffff;
        }


        .user-avatar svg {

            width: 24px;
            height: 24px;
        }


        .user-details {

            line-height: 1.3;
        }


        .user-name {

            max-width: 155px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            font-size: 13px;
            font-weight: 700;

            color: var(--navy);
        }


        .user-role {

            margin-top: 3px;

            font-size: 11px;

            color: var(--muted);
        }


        .logout-button {

            padding: 8px;

            border: none;

            border-radius: 8px;

            background: transparent;

            color: #65738b;

            cursor: pointer;

            transition: 0.2s;
        }


        .logout-button:hover {

            background: var(--primary-soft);

            color: var(--primary);
        }


        .logout-button svg {

            width: 20px;
            height: 20px;
        }


        /* =========================================================
           PAGE CONTAINER
        ========================================================= */

        .page-container {

            width: 100%;
            max-width: 1600px;

            margin: auto;

            padding: 36px 38px 20px;
        }


        /* =========================================================
           COMMON WELCOME SECTION
        ========================================================= */

        .welcome-section {

            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 26px;
        }


        .welcome-small {

            margin-bottom: 5px;

            font-size: 14px;

            color: #506079;
        }


        .welcome-title {

            font-size: 29px;

            line-height: 1.2;

            letter-spacing: -0.6px;

            color: var(--navy);
        }


        .welcome-description {

            margin-top: 8px;

            font-size: 13px;

            color: var(--muted);
        }


        .welcome-decoration {

            width: 180px;
            height: 80px;

            position: relative;

            opacity: 0.75;
        }


        .welcome-circle-one,
        .welcome-circle-two,
        .welcome-circle-three {

            position: absolute;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    rgba(80, 100, 180, 0.14),
                    rgba(100, 180, 160, 0.10)
                );
        }


        .welcome-circle-one {

            width: 75px;
            height: 75px;

            right: 15px;
            top: 2px;
        }


        .welcome-circle-two {

            width: 43px;
            height: 43px;

            right: 80px;
            top: 25px;
        }


        .welcome-circle-three {

            width: 20px;
            height: 20px;

            right: 123px;
            top: 7px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1180px) {

            .navbar {
                padding: 0 20px;
            }


            .brand {
                min-width: auto;
            }


            .brand-description {
                display: none;
            }


            .nav-link span {
                display: none;
            }


            .nav-link {
                padding: 11px;
            }


            .navbar-user {
                min-width: auto;
            }
        }


        @media (max-width: 760px) {

            .navbar {

                position: static;

                padding: 16px;
            }


            .navbar-inner {
                flex-wrap: wrap;
            }


            .main-nav {

                order: 3;

                width: 100%;

                justify-content: flex-start;

                overflow-x: auto;

                padding-bottom: 3px;
            }


            .nav-link span {
                display: inline;
            }


            .user-details {
                display: none;
            }


            .page-container {
                padding: 25px 17px;
            }


            .welcome-decoration {
                display: none;
            }


            .welcome-title {
                font-size: 24px;
            }
        }


        /* =========================================================
           PAGE-SPECIFIC CSS
        ========================================================= */

        @yield('page-style')

    </style>

</head>


<body>


<div class="top-accent"></div>


<header class="navbar">

    <div class="navbar-inner">


        <!-- =====================================================
             UCPMS BRAND
        ====================================================== -->

        <div class="brand">

            <div class="brand-icon">
                <img
                    src="{{ asset('images/ucpms-logo.png') }}"
                    alt="UCPMS Logo"
                >
            </div>


            <div>

                <div class="brand-name">
                    UCPMS
                </div>

                <div class="brand-description">
                    University Centralized<br>
                    Procurement Management System
                </div>

            </div>

        </div>



        <!-- =====================================================
             ROLE NAVIGATION
        ====================================================== -->

        <nav class="main-nav">


            {{-- =================================================
                 PTJ NAVIGATION
            ================================================== --}}

            @if ($role === 'ptj')


                <a
                    href="{{ route('ptj.dashboard') }}"
                    class="nav-link {{ request()->routeIs('ptj.dashboard') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="3"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="14"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="3"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="14"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Dashboard</span>

                </a>


                <a
                    href="{{ route('profile.show') }}"
                    class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <circle
                            cx="12"
                            cy="8"
                            r="4"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M4 21C4.8 16.8 7.4 15 12 15C16.6 15 19.2 16.8 20 21"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Profile</span>

                </a>


                <a
                    href="{{ route('ptj.procurement.index') }}"
                    class="nav-link {{ request()->routeIs('ptj.procurement.*') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="5"
                            y="3"
                            width="14"
                            height="18"
                            rx="2"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M8 8H16M8 12H16M8 16H13"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Procurement Request</span>

                </a>


                <a
                    href="{{ route('ptj.orders.index') }}"
                    class="nav-link {{ request()->routeIs('ptj.orders.*') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M4 8L12 4L20 8L12 12L4 8Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M4 8V16L12 20L20 16V8"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M12 12V20"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Active Orders</span>

                </a>


            {{-- =================================================
                 KJ NAVIGATION
            ================================================== --}}

            @elseif ($role === 'kj')


                <a
                    href="{{ route('kj.dashboard') }}"
                    class="nav-link {{ request()->routeIs('kj.dashboard') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="3"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="14"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="3"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="14"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Dashboard</span>

                </a>


                <a
                    href="{{ route('profile.show') }}"
                    class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <circle
                            cx="12"
                            cy="8"
                            r="4"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M4 21C4.8 16.8 7.4 15 12 15C16.6 15 19.2 16.8 20 21"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Profile</span>

                </a>


                <a
                    href="{{ route('kj.approvals.index') }}"
                    class="nav-link {{
                        request()->routeIs('kj.approvals.index') ||
                        request()->routeIs('kj.approvals.show')
                            ? 'active'
                            : ''
                    }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="5"
                            y="3"
                            width="14"
                            height="18"
                            rx="2"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M8 8H16M8 12H16M8 16H13"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Pending Approvals</span>

                </a>


                <a
                    href="{{ route('kj.approvals.history') }}"
                    class="nav-link {{
                        request()->routeIs('kj.approvals.history') ||
                        request()->routeIs('kj.approvals.history.details')
                            ? 'active'
                            : ''
                    }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M4 12A8 8 0 1 0 6.3 6.3"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M4 4V9H9"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M12 8V12L15 14"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Approval History</span>

                </a>



            {{-- =================================================
                 BENDAHARI NAVIGATION
            ================================================== --}}

            @elseif ($role === 'bendahari')


                <a
                    href="{{ route('bendahari.dashboard') }}"
                    class="nav-link {{ request()->routeIs('bendahari.dashboard') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="3"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="14"
                            y="3"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="3"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <rect
                            x="14"
                            y="14"
                            width="7"
                            height="7"
                            rx="1"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Dashboard</span>

                </a>


                <a
                    href="{{ route('profile.show') }}"
                    class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <circle
                            cx="12"
                            cy="8"
                            r="4"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M4 21C4.8 16.8 7.4 15 12 15C16.6 15 19.2 16.8 20 21"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Profile</span>

                </a>


                <a
                    href="{{ route('bendahari.approvals.index') }}"
                    class="nav-link {{
                        request()->routeIs('bendahari.approvals.index') ||
                        request()->routeIs('bendahari.approvals.show')
                            ? 'active'
                            : ''
                    }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="5"
                            y="3"
                            width="14"
                            height="18"
                            rx="2"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M8 8H16M8 12H16M8 16H13"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Pending Approvals</span>

                </a>


                <a
                    href="{{ route('bendahari.approvals.history') }}"
                    class="nav-link {{
                        request()->routeIs('bendahari.approvals.history') ||
                        request()->routeIs('bendahari.approvals.history.details')
                            ? 'active'
                            : ''
                    }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M4 12A8 8 0 1 0 6.3 6.3"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M4 4V9H9"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M12 8V12L15 14"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                    <span>Approval History</span>

                </a>


                <a
                    href="{{ route('bendahari.payments.index') }}"
                    class="nav-link {{
                        request()->routeIs('bendahari.payments.*')
                            ? 'active'
                            : ''
                    }}"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <rect
                            x="3"
                            y="6"
                            width="18"
                            height="13"
                            rx="2"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <path
                            d="M3 10H21"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <path
                            d="M7 15H11"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />

                    </svg>

                    <span>Payment</span>

                </a>


                {{-- =================================================
                    ADMIN NAVIGATION
                ================================================== --}}

                @elseif ($role === 'admin')


                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >

                        <svg viewBox="0 0 24 24" fill="none">

                            <rect
                                x="3"
                                y="3"
                                width="7"
                                height="7"
                                rx="1"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <rect
                                x="14"
                                y="3"
                                width="7"
                                height="7"
                                rx="1"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <rect
                                x="3"
                                y="14"
                                width="7"
                                height="7"
                                rx="1"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <rect
                                x="14"
                                y="14"
                                width="7"
                                height="7"
                                rx="1"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                        </svg>

                        <span>Dashboard</span>

                    </a>


                    <a
                        href="{{ route('profile.show') }}"
                        class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                    >

                        <svg viewBox="0 0 24 24" fill="none">

                            <circle
                                cx="12"
                                cy="8"
                                r="4"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <path
                                d="M4 21C4.8 16.8 7.4 15 12 15C16.6 15 19.2 16.8 20 21"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                        </svg>

                        <span>Profile</span>

                    </a>


                    <a
                        href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                    >

                        <svg viewBox="0 0 24 24" fill="none">

                            <rect
                                x="4"
                                y="4"
                                width="16"
                                height="16"
                                rx="2"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                            <path
                                d="M8 8H16M8 12H16M8 16H13"
                                stroke="currentColor"
                                stroke-width="1.8"
                            />

                        </svg>

                        <span>User Directory</span>

                    </a>


                    <a
                        href="{{ route('admin.users.create') }}"
                        class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
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

                        <span>Add User</span>

                    </a>


            @endif


            {{-- =================================================
                COMMON REPORT
            ================================================== --}}

            <a
                href="{{ route('report.index') }}"
                class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}"
            >

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M5 20V12M12 20V5M19 20V9"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                </svg>

                <span>Report</span>

            </a>


            {{-- =================================================
                 COMMON HELP
            ================================================== --}}
{{--
            <span class="nav-link disabled">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M9.8 9.5C10 8.2 10.9 7.5 12.2 7.5C13.6 7.5 14.5 8.3 14.5 9.5C14.5 10.5 14 11 13 11.7C12.2 12.2 12 12.8 12 13.5"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                    />

                    <circle
                        cx="12"
                        cy="16.8"
                        r="1"
                        fill="currentColor"
                    />

                </svg>

                <span>Help & Support</span>

            </span>
--}}

        </nav>



        <!-- =====================================================
             LOGGED-IN USER
        ====================================================== -->

        <div class="navbar-user">


            <div class="user-avatar">

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


            <div class="user-details">

                <div class="user-name">
                    {{ $user->user_name }}
                </div>

                <div class="user-role">
                    {{ $roleLabel }}
                </div>

            </div>


            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                    title="Logout"
                >

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M10 5H5V19H10"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M14 8L18 12L14 16"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M8 12H18"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                    </svg>

                </button>

            </form>


        </div>


    </div>

</header>



<!-- =========================================================
     PAGE CONTENT
========================================================= -->

<main class="page-container">

    @yield('content')

</main>


@yield('page-script')


</body>

</html>