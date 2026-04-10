<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OCP-005 - Registro de la Propiedad Cayambe')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --sidebar-bg: #2c3e50;
            --sidebar-width: 280px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #ecf0f1;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            height: var(--topbar-height);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-brand i {
            font-size: 1.75rem;
        }

        .topbar-spacer {
            flex: 1;
        }

        .topbar-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .topbar-nav a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
        }

        .topbar-nav a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .topbar-nav a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        /* Main Container */
        .main-container {
            display: flex;
            flex: 1;
            margin-top: var(--topbar-height);
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: white;
            padding: 2rem 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            position: fixed;
            left: 0;
            top: var(--topbar-height);
            height: calc(100vh - var(--topbar-height));
        }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-nav .nav-item {
            margin: 0;
            padding: 0;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 2rem;
        }

        .sidebar-nav .nav-link.active {
            background-color: var(--primary);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
            min-width: 1.5rem;
        }

        /* Content Area */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - var(--topbar-height));
        }

        .content {
            flex: 1;
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 1.25rem;
            font-weight: 600;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table thead th {
            font-weight: 600;
            color: #495057;
            padding: 1rem;
            border: none;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Forms */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }

        /* Buttons */
        .btn {
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #0a58ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        /* Footer */
        .footer {
            background-color: white;
            border-top: 1px solid #dee2e6;
            padding: 1.5rem 2rem;
            margin-left: var(--sidebar-width);
            text-align: center;
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
            }

            .sidebar {
                display: none;
            }

            .content-wrapper,
            .footer {
                margin-left: 0;
            }

            .topbar {
                padding: 0 1rem;
            }

            .topbar-brand {
                font-size: 1.25rem;
            }

            .content {
                padding: 1rem;
            }
        }

        /* Utilities */
        .text-muted {
            color: #6c757d;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Topbar -->
    <div class="topbar">
        <a href="{{ route('cobros.index') }}" class="topbar-brand">
            <i class="bi bi-building-fill"></i>
            <span>OCP-005</span>
        </a>
        <div class="topbar-spacer"></div>
        <nav class="topbar-nav">
            <a href="{{ route('consultar.index') }}" class="topbar-nav-link {{ request()->routeIs('consultar.*') ? 'active' : '' }}">
                <i class="bi bi-database-fill-down"></i>
                <span>Obtener</span>
            </a>
            <a href="{{ route('cobros.index') }}" class="topbar-nav-link {{ request()->routeIs('cobros.*') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i>
                <span>Listado de Cobros</span>
            </a>
        </nav>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('cobros.index') }}" class="nav-link {{ request()->routeIs('cobros.index') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i>
                        <span>Listado de Cobros</span>
                    </a>
                </li>                
                <li class="nav-item">
                    <a href="{{ route('consultar.index') }}" class="nav-link {{ request()->routeIs('consultar.*') ? 'active' : '' }}">
                        <i class="bi bi-database-fill-down"></i>
                        <span>Obtener</span>
                    </a>
                </li>
            </nav>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="margin: 1.5rem 2rem 0 2rem;" role="alert">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="margin: 1.5rem 2rem 0 2rem;" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Content -->
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <small>
            <strong>OCP-005</strong> - Sistema de envío de Cobros Banco del Pacifico - RPMC {{ date('Y') }} | 
            <strong>Registro de la Propiedad y Mercantil del Cantón Cayambe</strong>
        </small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
