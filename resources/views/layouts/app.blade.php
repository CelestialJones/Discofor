<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Discofor') - Plataforma Acadêmica</title>
    <meta name="description" content="@yield('description', 'Discofor - Plataforma de Publicação de Artigos e Debates Acadêmicos')">
    <meta property="og:title" content="@yield('title', 'Discofor')">
    <meta property="og:description" content="@yield('description', 'Discofor - Plataforma de Publicação de Artigos e Debates Acadêmicos')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function () {
            const savedTheme = localStorage.getItem('discofor-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0f62fe;
            --primary-dark: #0945b5;
            --secondary-color: #00a3a3;
            --dark-bg: #0b1220;
            --light-bg: #f3f8ff;
            --border-color: #dce8f7;
            --muted-text: #5b6c84;
            --page-bg: radial-gradient(circle at top right, #e7f3ff 0%, #f3f8ff 45%, #f8fbff 100%);
            --text-color: #1e293b;
            --surface-bg: rgba(255, 255, 255, 0.95);
            --navbar-bg: rgba(255, 255, 255, 0.9);
            --table-head-bg: #f8fbff;
        }

        body {
            font-family: "Manrope", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--page-bg);
            color: var(--text-color);
        }

        .navbar {
            background: var(--navbar-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 8px 18px rgba(11, 18, 32, 0.06);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 14px 28px rgba(11, 18, 32, 0.06);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .article-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 28px rgba(11, 18, 32, 0.12);
        }

        .badge-primary {
            background-color: var(--primary-color);
        }

        .nav-link {
            color: #64748b !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            border-bottom: 2px solid var(--primary-color);
        }

        .form-control,
        .form-select {
            border-color: var(--border-color);
            border-radius: 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 163, 163, 0.2);
        }

        .text-muted {
            color: var(--muted-text) !important;
        }

        .surface-card {
            background: var(--surface-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 16px 30px rgba(11, 18, 32, 0.08);
        }

        .empty-state {
            background: rgba(255, 255, 255, 0.8);
            border: 1px dashed var(--border-color);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            color: var(--muted-text);
        }

        .empty-state a {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .rounded-lg {
            border-radius: 1rem !important;
        }

        .table > :not(caption) > * > * {
            vertical-align: middle;
        }

        .table-light,
        .table-light > th,
        .table-light > td {
            background-color: var(--table-head-bg) !important;
            color: var(--text-color) !important;
        }

        .page-header {
            background: linear-gradient(135deg, #0f62fe, #0085c7);
            color: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 20px 34px rgba(15, 98, 254, 0.25);
        }

        .hover-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 28px rgba(11, 18, 32, 0.14) !important;
        }

        .like-animate {
            animation: likePop 0.28s ease-out;
        }

        @keyframes likePop {
            0% {
                transform: scale(1);
            }
            45% {
                transform: scale(1.16);
            }
            100% {
                transform: scale(1);
            }
        }

        .hover-white:hover {
            color: #fff !important;
            transition: color 0.3s ease;
        }

        .hover-primary:hover {
            color: var(--primary-color) !important;
            transition: color 0.3s ease;
        }

        .bg-gradient-primary {
            background: linear-gradient(145deg, #0f62fe, #00a3a3);
        }

        .animate__animated {
            animation-duration: 1s;
            animation-fill-mode: both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translate3d(30px, 0, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .animate__fadeInUp {
            animation-name: fadeInUp;
        }

        .animate__fadeInRight {
            animation-name: fadeInRight;
        }

        footer {
            background-color: var(--dark-bg);
            color: #cbd5e1;
            margin-top: 4rem;
            padding: 2rem 0;
        }

        .pagination .page-link {
            color: var(--primary-color);
        }

        .pagination .page-link:hover {
            background-color: var(--light-bg);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .theme-toggle-btn {
            border: 1px solid var(--border-color);
            border-radius: 999px;
            background: var(--surface-bg);
            color: var(--text-color);
            width: 2.4rem;
            height: 2.4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        html[data-theme="dark"] {
            --light-bg: #0f1729;
            --border-color: #2a3c57;
            --muted-text: #a6bad3;
            --page-bg: radial-gradient(circle at top right, #0f172a 0%, #0b1323 45%, #0a1020 100%);
            --text-color: #e5edf9;
            --surface-bg: rgba(20, 31, 48, 0.92);
            --navbar-bg: rgba(14, 22, 36, 0.92);
            --table-head-bg: #132034;
        }

        html[data-theme="dark"] .card,
        html[data-theme="dark"] .dropdown-menu,
        html[data-theme="dark"] .modal-content,
        html[data-theme="dark"] .list-group-item,
        html[data-theme="dark"] .breadcrumb,
        html[data-theme="dark"] .bg-light,
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select {
            background-color: #132034 !important;
            color: var(--text-color) !important;
            border-color: var(--border-color) !important;
        }

        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] .dropdown-item,
        html[data-theme="dark"] .btn-light {
            color: var(--text-color) !important;
        }

        html[data-theme="dark"] .btn-light {
            background-color: #1b2a41 !important;
            border-color: var(--border-color) !important;
        }

        html[data-theme="dark"] .table {
            color: var(--text-color);
        }

        html[data-theme="dark"] a {
            color: #8bb8ff;
        }

        html[data-theme="dark"] a:hover {
            color: #b8d4ff;
        }

        html[data-theme="dark"] .badge.bg-light,
        html[data-theme="dark"] .bg-light.text-dark,
        html[data-theme="dark"] .badge.text-dark {
            background-color: #1a2a42 !important;
            color: #cfe0f7 !important;
            border: 1px solid var(--border-color);
        }

        html[data-theme="dark"] .alert-info {
            background-color: #13263f !important;
            border-color: #1f4a7a !important;
            color: #c9ddff !important;
        }

        html[data-theme="dark"] .alert-success {
            background-color: #102f26 !important;
            border-color: #1f6d53 !important;
            color: #bdeedb !important;
        }

        html[data-theme="dark"] .alert-warning {
            background-color: #3a2a10 !important;
            border-color: #7f5d1f !important;
            color: #ffe8b5 !important;
        }

        html[data-theme="dark"] .alert-danger {
            background-color: #3a1a1d !important;
            border-color: #7f2a33 !important;
            color: #ffc8ce !important;
        }

        html[data-theme="dark"] .btn-outline-secondary,
        html[data-theme="dark"] .btn-outline-danger,
        html[data-theme="dark"] .btn-outline-warning,
        html[data-theme="dark"] .btn-outline-success,
        html[data-theme="dark"] .btn-outline-info {
            border-color: var(--border-color);
            color: #d4e4fb;
        }

        html[data-theme="dark"] .btn-outline-secondary:hover,
        html[data-theme="dark"] .btn-outline-danger:hover,
        html[data-theme="dark"] .btn-outline-warning:hover,
        html[data-theme="dark"] .btn-outline-success:hover,
        html[data-theme="dark"] .btn-outline-info:hover {
            color: #fff;
        }

        html[data-theme="dark"] .page-header {
            box-shadow: 0 16px 28px rgba(6, 16, 30, 0.45);
        }

        html[data-theme="dark"] .empty-state {
            background: rgba(18, 30, 48, 0.75);
        }

        html[data-theme="dark"] .navbar-toggler {
            border-color: var(--border-color);
        }

        html[data-theme="dark"] .navbar-toggler-icon {
            filter: invert(1) brightness(1.6);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-chat-left-text"></i> Discofor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="flex-grow-1 mx-3">
                    <form method="GET" action="{{ route('search.search') }}" class="position-relative">
                        <input type="text" name="q" class="form-control" placeholder="Buscar artigos, usuários..."
                               style="border-radius: 0.5rem;">
                    </form>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center me-2">
                        <button type="button" id="theme-toggle" class="theme-toggle-btn" aria-label="Alternar tema">
                            <i class="bi bi-moon-stars-fill" id="theme-toggle-icon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articles.index') }}">Artigos</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('articles.create') }}">
                                <i class="bi bi-pencil-square"></i> Novo Artigo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                                <i class="bi bi-bell"></i> Notificações
                                @php
                                    $unreadCount = auth()->user()->unreadNotificationsCount();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                          style="font-size: 0.6rem;">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('users.dashboard') }}">Meu Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('users.my-articles') }}">Meus Artigos</a></li>
                                <li><a class="dropdown-item" href="{{ route('users.edit-profile') }}">Perfil</a></li>
                                @if(auth()->user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Painel Admin</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <strong>Erro!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">Discofor</h5>
                    <p>Plataforma acadêmica para publicação de artigos e debates em tempo real.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('articles.index') }}" class="text-decoration-none text-reset">Artigos</a></li>
                        <li><a href="#" class="text-decoration-none text-reset">Sobre</a></li>
                        <li><a href="#" class="text-decoration-none text-reset">Contato</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="text-white">Redes Sociais</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-reset">Twitter</a></li>
                        <li><a href="#" class="text-decoration-none text-reset">LinkedIn</a></li>
                        <li><a href="#" class="text-decoration-none text-reset">GitHub</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Discofor. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <div class="toast-container position-fixed top-0 end-0 p-3" id="appToastContainer" style="z-index: 1080;"></div>

    <div class="modal fade" id="appConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="appConfirmTitle">Confirmar ação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="appConfirmBody">Deseja continuar com esta ação?</div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="appConfirmCancel">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="appConfirmOk">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.DiscoforUI = (function () {
            let confirmModalInstance = null;
            let pendingResolve = null;
            let audioContext = null;

            function showToast(message, variant = 'success') {
                const container = document.getElementById('appToastContainer');
                if (!container) return;

                const colorClass = variant === 'error' ? 'text-bg-danger' : (variant === 'warning' ? 'text-bg-warning' : 'text-bg-success');
                const toast = document.createElement('div');
                toast.className = `toast align-items-center border-0 ${colorClass}`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                    </div>
                `;

                container.appendChild(toast);
                const instance = bootstrap.Toast.getOrCreateInstance(toast, { delay: 3000 });
                toast.addEventListener('hidden.bs.toast', () => toast.remove());
                instance.show();
            }

            function confirmAction(options = {}) {
                const modalEl = document.getElementById('appConfirmModal');
                const titleEl = document.getElementById('appConfirmTitle');
                const bodyEl = document.getElementById('appConfirmBody');
                const okEl = document.getElementById('appConfirmOk');
                const cancelEl = document.getElementById('appConfirmCancel');

                if (!modalEl || !titleEl || !bodyEl || !okEl || !cancelEl) {
                    return Promise.resolve(window.confirm(options.message || 'Deseja continuar?'));
                }

                titleEl.textContent = options.title || 'Confirmar ação';
                bodyEl.textContent = options.message || 'Deseja continuar com esta ação?';
                okEl.textContent = options.confirmText || 'Confirmar';
                cancelEl.textContent = options.cancelText || 'Cancelar';
                okEl.className = `btn ${options.confirmClass || 'btn-danger'}`;

                if (!confirmModalInstance) {
                    confirmModalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                }

                return new Promise((resolve) => {
                    pendingResolve = resolve;

                    const handleConfirm = () => {
                        cleanup();
                        resolve(true);
                        confirmModalInstance.hide();
                    };

                    const handleCancel = () => {
                        cleanup();
                        resolve(false);
                    };

                    const handleHidden = () => {
                        if (pendingResolve) {
                            cleanup();
                            resolve(false);
                        }
                    };

                    function cleanup() {
                        pendingResolve = null;
                        okEl.removeEventListener('click', handleConfirm);
                        cancelEl.removeEventListener('click', handleCancel);
                        modalEl.removeEventListener('hidden.bs.modal', handleHidden);
                    }

                    okEl.addEventListener('click', handleConfirm);
                    cancelEl.addEventListener('click', handleCancel);
                    modalEl.addEventListener('hidden.bs.modal', handleHidden);
                    confirmModalInstance.show();
                });
            }

            function playLikeSound() {
                try {
                    const Ctx = window.AudioContext || window.webkitAudioContext;
                    if (!Ctx) return;
                    if (!audioContext) {
                        audioContext = new Ctx();
                    }

                    const now = audioContext.currentTime;
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.type = 'triangle';
                    oscillator.frequency.setValueAtTime(720, now);
                    oscillator.frequency.exponentialRampToValueAtTime(980, now + 0.08);

                    gainNode.gain.setValueAtTime(0.0001, now);
                    gainNode.gain.exponentialRampToValueAtTime(0.04, now + 0.015);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, now + 0.12);

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.start(now);
                    oscillator.stop(now + 0.12);
                } catch (_) {
                    // ignore audio errors silently
                }
            }

            function playCommentSound() {
                try {
                    const Ctx = window.AudioContext || window.webkitAudioContext;
                    if (!Ctx) return;
                    if (!audioContext) {
                        audioContext = new Ctx();
                    }

                    const now = audioContext.currentTime;
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(520, now);
                    oscillator.frequency.exponentialRampToValueAtTime(780, now + 0.11);

                    gainNode.gain.setValueAtTime(0.0001, now);
                    gainNode.gain.exponentialRampToValueAtTime(0.035, now + 0.018);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, now + 0.16);

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.start(now);
                    oscillator.stop(now + 0.16);
                } catch (_) {
                    // ignore audio errors silently
                }
            }

            return { showToast, confirmAction, playLikeSound, playCommentSound };
        })();

        (function () {
            const toggleButton = document.getElementById('theme-toggle');
            const icon = document.getElementById('theme-toggle-icon');

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('discofor-theme', theme);
                if (icon) {
                    icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
                }
            }

            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            applyTheme(currentTheme);

            toggleButton?.addEventListener('click', function () {
                const now = document.documentElement.getAttribute('data-theme') || 'light';
                applyTheme(now === 'dark' ? 'light' : 'dark');
            });
        })();
    </script>

    @auth
    <script>
        // Update notifications count every 30 seconds
        function updateNotificationsCount() {
            fetch('/notifications/unread', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.nav-link[href*="notifications"] .badge');
                const currentCount = data.unread_count;

                if (currentCount > 0) {
                    if (badge) {
                        badge.textContent = currentCount > 99 ? '99+' : currentCount;
                        badge.style.display = 'inline-block';
                    } else {
                        // Create badge if it doesn't exist
                        const navLink = document.querySelector('.nav-link[href*="notifications"]');
                        if (navLink) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            newBadge.style.fontSize = '0.6rem';
                            newBadge.textContent = currentCount > 99 ? '99+' : currentCount;
                            navLink.style.position = 'relative';
                            navLink.appendChild(newBadge);
                        }
                    }
                } else {
                    if (badge) {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error updating notifications:', error));
        }

        // Update notifications on page load and every 30 seconds
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationsCount();
            setInterval(updateNotificationsCount, 30000); // 30 seconds
        });
    </script>
    @endauth

    @stack('scripts')

    {{-- Load compiled JS via Vite --}}
    @vite(['resources/js/app.js'])
</body>
</html>
