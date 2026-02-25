@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-speedometer2"></i> Painel de Administração
            </h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total de Usuários</p>
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                        </div>
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Artigos Publicados</p>
                            <h3 class="mb-0">{{ $stats['published_articles'] }}</h3>
                        </div>
                        <i class="bi bi-file-text text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Artigos Pendentes</p>
                            <h3 class="mb-0">{{ $stats['pending_articles'] }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Comentários Pendentes</p>
                            <h3 class="mb-0">{{ $comments_pending_moderation }}</h3>
                        </div>
                        <i class="bi bi-chat-dots text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total de Comentários</p>
                            <h3 class="mb-0">{{ $stats['total_comments'] }}</h3>
                        </div>
                        <i class="bi bi-chat text-secondary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total de Debates</p>
                            <h3 class="mb-0">{{ $stats['total_debates'] }}</h3>
                        </div>
                        <i class="bi bi-chat-left-dots text-danger" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Usuários Hoje</p>
                            <h3 class="mb-0">{{ $stats['active_users_today'] }}</h3>
                        </div>
                        <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total de Artigos</p>
                            <h3 class="mb-0">{{ $stats['total_articles'] }}</h3>
                        </div>
                        <i class="bi bi-file-earmark text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Ações de Administração
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-people"></i> Gerenciar Usuários
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.articles') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-file-text"></i> Gerenciar Artigos
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.comments') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-chat"></i> Moderar Comentários
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-clock-history"></i> Ver Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Atividades Recentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recent_activities as $activity)
                            <div class="list-group-item px-3 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">
                                            <strong>{{ $activity->user->name }}</strong>
                                            <span class="badge bg-secondary">{{ $activity->action }}</span>
                                        </p>
                                        <small class="text-muted">{{ $activity->description }}</small>
                                    </div>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="px-3 py-3 text-muted text-center">
                                Nenhuma atividade registrada
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus"></i> Usuários Recentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recent_users as $user)
                            <div class="list-group-item px-3 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">
                                            <strong>{{ $user->name }}</strong>
                                            <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'primary' }}">
                                                {{ $user->isAdmin() ? 'Admin' : 'Usuário' }}
                                            </span>
                                        </p>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="px-3 py-3 text-muted text-center">
                                Nenhum usuário
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark"></i> Artigos Recentes
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_articles as $article)
                                <tr>
                                    <td>
                                        <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                            {{ Str::limit($article->title, 40) }}
                                        </a>
                                    </td>
                                    <td>{{ $article->user?->name ?? 'Usuário deletado' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($article->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Nenhum artigo</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
