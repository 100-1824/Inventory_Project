<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unique Hardware - Inventory Management</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --accent: #f472b6;
            --accent-hover: #ec4899;
            --accent-glow: rgba(244, 114, 182, 0.4);
            --bg-dark: #0f172a;
            --bg-darker: #020617;
            --bg-card: rgba(30, 41, 59, 0.7);
            --bg-card-hover: rgba(51, 65, 85, 0.7);
            --bg-card-solid: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: rgba(148, 163, 184, 0.15);
            --border-hover: rgba(148, 163, 184, 0.3);
            --success: #22c55e;
            --success-glow: rgba(34, 197, 94, 0.3);
            --warning: #eab308;
            --warning-glow: rgba(234, 179, 8, 0.3);
            --danger: #ef4444;
            --danger-glow: rgba(239, 68, 68, 0.3);
            --info: #06b6d4;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg-darker) 0%, var(--bg-dark) 50%, #1e1b4b 100%);
            min-height: 100vh;
            color: var(--text-primary);
            position: relative;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 50%);
            pointer-events: none;
            animation: bgFloat 20s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -50%;
            left: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(244, 114, 182, 0.06) 0%, transparent 50%);
            pointer-events: none;
            animation: bgFloat 25s ease-in-out infinite reverse;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(5%, 5%); }
        }

        /* Navbar */
        .navbar {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.875rem 0;
            position: relative;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        .navbar-brand .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .navbar-brand .brand-icon i {
            font-size: 1.25rem;
            color: white;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            padding: 0.625rem 1rem !important;
            border-radius: 10px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--text-primary) !important;
            background: rgba(99, 102, 241, 0.1);
        }

        .nav-link.active {
            color: var(--primary-light) !important;
            background: rgba(99, 102, 241, 0.15);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-name {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.875rem;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: var(--danger);
            color: var(--danger);
            transform: translateY(-1px);
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.03) inset;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: var(--border-hover);
            box-shadow: 
                0 10px 40px -10px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-accent {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-accent::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-accent:hover::before {
            left: 100%;
        }

        .btn-outline-light {
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            background: transparent;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
            color: var(--primary-light);
            transform: translateY(-1px);
        }

        /* Form Controls */
        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Table */
        .table {
            color: var(--text-primary);
            --bs-table-bg: transparent;
        }

        .table thead th {
            background: rgba(79, 70, 229, 0.15);
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            border-top: none;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        /* Category Badges */
        .badge-category {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .badge-hardware { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .badge-tools { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
        .badge-sanitary { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .badge-paint { background: linear-gradient(135deg, #f59e0b, #d97706); }

        /* Modal */
        .modal {
            z-index: 1060; /* Above navbar and background animations */
        }

        .modal-backdrop {
            z-index: 1050;
        }

        .modal-content {
            background: var(--bg-card-solid);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1061;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* Alert */
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            padding: 1.5rem;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.3);
        }

        .stat-card.accent {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        }

        .stat-card.accent:hover {
            box-shadow: 0 15px 40px var(--accent-glow);
        }

        .stat-card.success {
            background: linear-gradient(135deg, var(--success), #16a34a);
        }

        .stat-card.success:hover {
            box-shadow: 0 15px 40px var(--success-glow);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, var(--warning), #ca8a04);
        }

        .stat-card.warning:hover {
            box-shadow: 0 15px 40px var(--warning-glow);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            position: relative;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* Search Box */
        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .search-box input {
            padding-left: 2.75rem;
        }

        .search-box input:focus + i,
        .search-box:focus-within i {
            color: var(--primary);
        }

        /* Action Buttons */
        .btn-action {
            width: 38px;
            height: 38px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s ease;
            border-width: 1px;
        }

        .btn-action:hover {
            transform: scale(1.1) translateY(-2px);
        }

        .btn-action.btn-outline-info:hover {
            box-shadow: 0 5px 20px rgba(6, 182, 212, 0.3);
        }

        .btn-action.btn-outline-warning:hover {
            box-shadow: 0 5px 20px var(--warning-glow);
        }

        .btn-action.btn-outline-danger:hover {
            box-shadow: 0 5px 20px var(--danger-glow);
        }

        /* Pagination */
        .pagination .page-link {
            background: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
            border-radius: 10px;
            margin: 0 3px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
            color: var(--primary-light);
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        /* Stock Status */
        .stock-low { 
            color: var(--danger);
            text-shadow: 0 0 10px var(--danger-glow);
        }
        .stock-medium { 
            color: var(--warning);
            text-shadow: 0 0 10px var(--warning-glow);
        }
        .stock-good { 
            color: var(--success);
            text-shadow: 0 0 10px var(--success-glow);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.4s ease forwards;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Main content wrapper */
        main {
            position: relative;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand span {
                display: none;
            }

            .user-name {
                display: none;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <div class="brand-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <span>Unique Hardware</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav me-auto mb-2 mb-lg-0">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Inventory
                    </a>
                    <a class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}" href="{{ route('sales.create') }}">
                        <i class="bi bi-cart-plus me-1"></i> New Sale (POS)
                    </a>
                    <a class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="bi bi-receipt me-1"></i> Sales History
                    </a>
                </div>

                <!-- User Menu -->
                <div class="user-menu">
                    <div class="user-info d-none d-md-flex">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                <div class="d-flex flex-column">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i>Please correct the following errors:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
