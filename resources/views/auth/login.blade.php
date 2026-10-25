<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Unique Hardware</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #546e7a;
            --primary-dark: #37474f;
            --primary-light: #b0bec5;
            --accent: #ffb74d;
            --accent-hover: #f57c00;
            --bg-dark: #eceff1;
            --bg-darker: #cfd8dc;
            --text-primary: #263238;
            --text-secondary: #546e7a;
            --border-color: #cfd8dc;
            --success: #81c784;
            --danger: #e57373;
        }

        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-darker);
            overflow: hidden;
        }

        /* Animated Background */
        .bg-animated {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: linear-gradient(135deg, var(--bg-dark) 0%, #b0bec5 100%);
        }

        .bg-animated::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(96, 125, 139, 0.1) 0%, transparent 50%);
            animation: pulse 15s ease-in-out infinite;
        }

        .bg-animated::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -25%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255, 183, 77, 0.08) 0%, transparent 50%);
            animation: pulse 20s ease-in-out infinite reverse;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(5%, 5%) scale(1.1);
            }
        }

        /* Floating shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            animation: float 20s ease-in-out infinite;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            bottom: -50px;
            left: -50px;
            animation-delay: -5s;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            top: 40%;
            left: 20%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(20px, -20px) rotate(5deg);
            }

            50% {
                transform: translate(-10px, 20px) rotate(-5deg);
            }

            75% {
                transform: translate(-20px, -10px) rotate(3deg);
            }
        }

        /* Login Card */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        }

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(96, 125, 139, 0.3);
            animation: logoGlow 3s ease-in-out infinite;
        }

        @keyframes logoGlow {

            0%,
            100% {
                box-shadow: 0 10px 40px rgba(96, 125, 139, 0.3);
            }

            50% {
                box-shadow: 0 10px 60px rgba(96, 125, 139, 0.5);
            }
        }

        .logo-icon i {
            font-size: 32px;
            color: white;
        }

        .logo h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }

        .logo p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Form */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 16px 14px 48px;
            font-size: 1rem;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(96, 125, 139, 0.15);
        }

        .form-control:focus+.input-icon,
        .form-control:focus~.input-icon {
            color: var(--primary);
        }

        /* Remember Me */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            cursor: pointer;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(96, 125, 139, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        /* Error Alert */
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            color: #fca5a5;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger i {
            color: var(--danger);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .login-footer p {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 36px 24px;
            }

            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="bg-animated">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo">
                <div class="logo-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <h1>Unique Hardware</h1>
                <p>Inventory Management System</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Enter your email" required autofocus>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" placeholder="Enter your password"
                            required>
                        <i class="bi bi-lock input-icon"></i>
                    </div>
                </div>

                <div class="form-options">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; {{ date('Y') }} Unique Hardware. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>