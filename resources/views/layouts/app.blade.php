<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OCP-005 - Registro de la Propiedad Cayambe')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('cobros.index') }}">
                <i class="bi bi-building"></i> OCP-005 - Registro de la Propiedad
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cobros.index') }}"><i class="bi bi-list"></i> Listar Cobros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cobros.create') }}"><i class="bi bi-plus-circle"></i> Nuevo Cobro</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @yield('content')
    </main>
    <footer class="bg-light py-3 mt-auto text-center">
        <small class="text-muted">
            <i class="bi bi-check-circle-fill text-success"></i> = Campo obligatorio para el reporte TXT |
            <i class="bi bi-arrow-repeat text-primary"></i> = Generado automaticamente |
            OCP-005 - Sistema de Cobros Banco del Pacifico
        </small>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
