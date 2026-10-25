<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Unique Hardware POS') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #546e7a;
            --primary-dark: #37474f;
            --accent: #ffb74d;
            --text-primary: #263238;
            --text-secondary: #546e7a;
            --bg-light: #eceff1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }

        .brand-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, var(--text-primary), var(--text-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        .btn-action {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .btn-outline-custom {
            background: white;
            border: 2px solid #e2e8f0;
            color: var(--text-primary);
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1000px;
            margin: 4rem auto 0;
            text-align: left;
        }

        .feature-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: #eff6ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--primary);
            font-size: 1.25rem;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div class="container">
            <div class="brand-logo">
                <i class="bi bi-tools"></i>
            </div>

            <h1 class="hero-title">Unique Hardware POS</h1>
            <p class="hero-subtitle">
                Streamline your hardware store operations with our professional Point of Sale system.
                Manage inventory, track sales, and monitor stock levels with ease.
            </p>

            <div class="d-flex gap-3 justify-content-center mb-5">
                @auth
                    <a href="{{ url('/products') }}" class="btn btn-action btn-primary-custom">
                        <i class="bi bi-speedometer2 me-2"></i>Enter Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-action btn-primary-custom">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-action btn-outline-custom">
                            Register
                        </a>
                    @endif
                @endauth
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5>Smart Inventory</h5>
                    <p class="text-secondary mb-0">Track hardware items, boxes, and units precisely.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h5>Sales Analytics</h5>
                    <p class="text-secondary mb-0">Monitor daily and monthly sales performance.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <h5>Unit Conversion</h5>
                    <p class="text-secondary mb-0">Handle loose items, boxes, meters, and dozens.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>