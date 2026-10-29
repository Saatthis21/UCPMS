<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>UCPMS Login</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(
                135deg,
                #edf4fb 0%,
                #e5f0f8 50%,
                #dceff5 100%
            );

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 15px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;

            background: #ffffff;

            border-radius: 10px;

            box-shadow:
                0 8px 25px rgba(0, 0, 0, 0.12);

            overflow: hidden;
        }

        .top-line {
            height: 5px;

            background: linear-gradient(
                90deg,
                #246bb2,
                #19a8a4
            );
        }

        .login-content {
            padding: 35px 32px 40px;
        }

        .logo-section {
            text-align: center;
        }

        .logo-icon {
            width: 60px;
            height: 60px;

            margin: 0 auto 15px;

            border-radius: 13px;

            background: linear-gradient(
                135deg,
                #2570b8,
                #1ba6a2
            );

            display: flex;
            align-items: center;
            justify-content: center;

            box-shadow:
                0 5px 12px rgba(30, 117, 164, 0.25);
        }

        .logo-icon svg {
            width: 30px;
            height: 30px;
        }

        .system-title {
            font-size: 22px;
            font-weight: 700;
            color: #25364a;

            margin-bottom: 5px;
        }

        .system-subtitle {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;

            margin-bottom: 8px;
        }

        .login-description {
            font-size: 12px;
            color: #94a3b8;
        }

        .divider {
            border: 0;
            border-top: 1px solid #e7edf3;

            margin: 28px 0;
        }

        .session-status {
            margin-bottom: 18px;

            padding: 10px 12px;

            border-radius: 6px;

            background: #ecfdf5;
            color: #047857;

            font-size: 13px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;

            margin-bottom: 8px;

            font-size: 13px;
            font-weight: 600;

            color: #475569;
        }

        .form-control {
            width: 100%;
            height: 44px;

            padding: 0 14px;

            border: 1px solid #d8e0e8;
            border-radius: 8px;

            background: #f8fafc;

            font-size: 13px;
            color: #334155;

            outline: none;

            transition: 0.2s;
        }

        .form-control::placeholder {
            color: #a8b3c1;
        }

        .form-control:focus {
            border-color: #2b78c5;
            background: #ffffff;

            box-shadow:
                0 0 0 3px rgba(43, 120, 197, 0.10);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;

            right: 12px;
            top: 50%;

            transform: translateY(-50%);

            border: none;
            background: transparent;

            cursor: pointer;

            padding: 5px;

            color: #8291a3;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #2775bd;
        }

        .password-toggle svg {
            width: 19px;
            height: 19px;
        }

        .error-message {
            margin-top: 7px;

            font-size: 12px;
            color: #dc2626;
        }

        .forgot-password {
            display: flex;
            justify-content: flex-end;

            margin-top: 8px;
            margin-bottom: 20px;
        }

        .forgot-password a {
            font-size: 12px;
            font-weight: 600;

            color: #2878c5;

            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            height: 45px;

            border: none;
            border-radius: 7px;

            background: #2879c7;

            color: #ffffff;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;

            box-shadow:
                0 4px 10px rgba(40, 121, 199, 0.25);

            transition: 0.2s;
        }

        .login-button:hover {
            background: #216bb2;
        }

        .login-button:active {
            transform: translateY(1px);
        }

        .help-section {
            margin-top: 24px;

            text-align: center;

            font-size: 11px;
            line-height: 1.6;

            color: #9aa8b8;
        }

        .helpdesk-text {
            color: #2b78c5;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .login-content {
                padding: 30px 22px 35px;
            }

            .system-title {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="top-line"></div>

    <div class="login-content">

        <!-- UCPMS Logo / Header -->
        <div class="logo-section">

            <div class="logo-icon">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M12 3L19 6V11C19 15.5 16.2 19.4 12 21C7.8 19.4 5 15.5 5 11V6L12 3Z"
                        stroke="white"
                        stroke-width="1.8"
                        stroke-linejoin="round"
                    />
                </svg>
            </div>

            <h1 class="system-title">
                UCPMS
            </h1>

            <p class="system-subtitle">
                University Centralized Procurement Management System
            </p>

            <p class="login-description">
                Sign in to access your dashboard.
            </p>

        </div>

        <hr class="divider">

        <!-- Session Status -->
        @if (session('status'))
            <div class="session-status">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">

            @csrf

            <!-- Staff ID -->
            <div class="form-group">

                <label
                    for="staff_id"
                    class="form-label"
                >
                    Staff ID
                </label>

                <input
                    id="staff_id"
                    class="form-control"
                    type="text"
                    name="staff_id"
                    value="{{ old('staff_id') }}"
                    placeholder="Enter your university Staff ID..."
                    required
                    autofocus
                    autocomplete="username"
                >

                @error('staff_id')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Password -->
            <div class="form-group">

                <label
                    for="password"
                    class="form-label"
                >
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        id="password"
                        class="form-control"
                        type="password"
                        name="password"
                        placeholder="Enter your secure password..."
                        required
                        autocomplete="current-password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        aria-label="Show or hide password"
                    >
                        <svg
                            id="eyeIcon"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M2 12C4.5 7.5 7.8 5.5 12 5.5C16.2 5.5 19.5 7.5 22 12C19.5 16.5 16.2 18.5 12 18.5C7.8 18.5 4.5 16.5 2 12Z"
                                stroke="currentColor"
                                stroke-width="1.6"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="2.5"
                                stroke="currentColor"
                                stroke-width="1.6"
                            />
                        </svg>
                    </button>

                </div>

                @error('password')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Forgot Password -->
            @if (Route::has('password.request'))

                <div class="forgot-password">

                    <a href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>

                </div>

            @endif


            <!-- Login Button -->
            <button
                type="submit"
                class="login-button"
            >
                ✓ Sign In to Your Account
            </button>

        </form>


        <!-- Help Section -->
        <div class="help-section">

            Need system access or having trouble logging in?

            <br>

            <span class="helpdesk-text">
                Contact UMPSA ICT Helpdesk
            </span>

        </div>

    </div>

</div>


<script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');

    passwordToggle.addEventListener('click', function () {

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }

    });
</script>

</body>
</html>