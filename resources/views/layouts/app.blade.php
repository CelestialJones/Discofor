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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: #1e293b;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
</body>
</html>
