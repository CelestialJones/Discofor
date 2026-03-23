@extends('layouts.app')

@section('title', 'Painel Administrativo - Discofor')
@section('description', 'Painel de controle administrativo da plataforma Discofor')

@section('content')
<div class="container-fluid py-4">
    <!-- Header com saudação -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 fw-bold mb-1">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Painel de Administração
                    </h1>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-calendar3 me-1"></i> 
                        {{ now()->format('l, d F Y') }} • 
                        <i class="bi bi-clock ms-2 me-1"></i>
                        {{ now()->format('H:i') }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="window.location.reload()">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="bi bi-house-door me-2"></i> Ver Site
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards - Primeira Linha -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total de Usuários</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['total_users'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success small">
                                    <i class="bi bi-arrow-up"></i> +{{ $stats['new_users_today'] ?? 0 }} hoje
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Artigos Publicados</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['published_articles'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success small">
                                    <i class="bi bi-arrow-up"></i> +{{ $stats['published_today'] ?? 0 }} hoje
                                </span>
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-text text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Artigos Pendentes</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['pending_articles'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-warning bg-opacity-10 text-warning small">
                                    <i class="bi bi-hourglass"></i> Aguardando revisão
                                </span>
                            </div>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Comentários Pendentes</p>
                            <h2 class="fw-bold mb-0">{{ number_format($comments_pending_moderation ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-info bg-opacity-10 text-info small">
                                    <i class="bi bi-chat"></i> Aguardando moderação
                                </span>
                            </div>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-chat-dots text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Artigos com PDF</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['articles_with_pdf'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger small">
                                    <i class="bi bi-file-earmark-pdf"></i> Anexos moderaveis
                                </span>
                            </div>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards - Segunda Linha -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total de Comentários</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['total_comments'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary small">
                                    <i class="bi bi-chat"></i> Total geral
                                </span>
                            </div>
                        </div>
                        <div class="bg-secondary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-chat text-secondary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total de Debates</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['total_debates'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger small">
                                    <i class="bi bi-chat"></i> {{ $stats['active_debates'] ?? 0 }} ativos
                                </span>
                            </div>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-chat-left-dots text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Usuários Hoje</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['active_users_today'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-10 text-success small">
                                    <i class="bi bi-people"></i> Ativos agora
                                </span>
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="surface-card hover-lift rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-semibold">Total de Artigos</p>
                            <h2 class="fw-bold mb-0">{{ number_format($stats['total_articles'] ?? 0, 0, ',', '.') }}</h2>
                            <div class="mt-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary small">
                                    <i class="bi bi-file-text"></i> Incluindo rascunhos
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-earmark text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="surface-card rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-gear-fill text-primary me-2"></i>
                        Ações de Administração
                    </h5>
                    <p class="text-muted small mb-0">Gerencie todos os aspectos da plataforma</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                <div class="card bg-primary bg-opacity-10 border-0 hover-lift">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-people display-5 text-primary mb-3"></i>
                                        <h6 class="fw-bold mb-2">Gerenciar Usuários</h6>
                                        <p class="small text-muted mb-0">Gerencie permissões e perfis</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('admin.articles') }}" class="text-decoration-none">
                                <div class="card bg-success bg-opacity-10 border-0 hover-lift">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-file-text display-5 text-success mb-3"></i>
                                        <h6 class="fw-bold mb-2">Gerenciar Artigos</h6>
                                        <p class="small text-muted mb-0">Aprove e edite publicações</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('admin.comments') }}" class="text-decoration-none">
                                <div class="card bg-info bg-opacity-10 border-0 hover-lift">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-chat display-5 text-info mb-3"></i>
                                        <h6 class="fw-bold mb-2">Moderar Comentários</h6>
                                        <p class="small text-muted mb-0">{{ $comments_pending_moderation ?? 0 }} pendentes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('admin.logs') }}" class="text-decoration-none">
                                <div class="card bg-secondary bg-opacity-10 border-0 hover-lift">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-clock-history display-5 text-secondary mb-3"></i>
                                        <h6 class="fw-bold mb-2">Ver Logs</h6>
                                        <p class="small text-muted mb-0">Atividades do sistema</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities e Usuários Recentes -->
    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="surface-card rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                Atividades Recentes
                            </h5>
                            <p class="text-muted small mb-0">Últimas ações na plataforma</p>
                        </div>
                        <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-primary">
                            Ver todos <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recent_activities ?? [] as $activity)
                            <div class="list-group-item border-0 border-bottom px-4 py-3">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                            <i class="bi bi-person-circle text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1">
                                                    <strong>{{ $activity->user?->name ?? 'Sistema' }}</strong>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary ms-2">
                                                        {{ $activity->action }}
                                                    </span>
                                                </p>
                                                <p class="small text-muted mb-0">{{ $activity->description }}</p>
                                            </div>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-5 text-center">
                                <i class="bi bi-clock-history display-1 text-muted mb-3"></i>
                                <h6 class="fw-bold mb-2">Nenhuma atividade</h6>
                                <p class="text-muted small mb-0">As atividades recentes aparecerão aqui</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="surface-card rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-person-plus text-success me-2"></i>
                                Usuários Recentes
                            </h5>
                            <p class="text-muted small mb-0">{{ $stats['new_users_today'] ?? 0 }} novos hoje</p>
                        </div>
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">
                            Ver todos <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recent_users ?? [] as $user)
                            <div class="list-group-item border-0 border-bottom px-4 py-3">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                 class="rounded-circle" 
                                                 width="40" 
                                                 height="40" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <span class="text-primary fw-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1">
                                                    <strong>{{ $user->name }}</strong>
                                                    <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'primary' }} ms-2">
                                                        {{ $user->isAdmin() ? 'Admin' : 'Usuário' }}
                                                    </span>
                                                </p>
                                                <p class="small text-muted mb-0">{{ $user->email }}</p>
                                            </div>
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-5 text-center">
                                <i class="bi bi-people display-1 text-muted mb-3"></i>
                                <h6 class="fw-bold mb-2">Nenhum usuário</h6>
                                <p class="text-muted small mb-0">Os usuários recentes aparecerão aqui</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Artigos Recentes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="surface-card rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-file-earmark-text text-warning me-2"></i>
                                Artigos Recentes
                            </h5>
                            <p class="text-muted small mb-0">{{ $stats['published_today'] ?? 0 }} publicados hoje</p>
                        </div>
                        <a href="{{ route('admin.articles') }}" class="btn btn-sm btn-outline-primary">
                            Gerenciar artigos <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">Título</th>
                                <th class="py-3">Autor</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Visualizações</th>
                                <th class="py-3">Data</th>
                                <th class="py-3 text-end px-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_articles ?? [] as $article)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @if($article->image)
                                                <img src="{{ asset('storage/' . $article->image) }}" 
                                                     width="32" 
                                                     height="32" 
                                                     class="rounded object-fit-cover" 
                                                     alt="">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="bi bi-file-text text-muted small"></i>
                                                </div>
                                            @endif
                                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark fw-semibold">
                                                {{ Str::limit($article->title, 40) }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @if($article->user)
                                                @if($article->user->avatar)
                                                    <img src="{{ asset('storage/' . $article->user->avatar) }}" 
                                                         width="24" 
                                                         height="24" 
                                                         class="rounded-circle object-fit-cover" 
                                                         alt="">
                                                @else
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 24px; height: 24px;">
                                                        <span class="text-primary small fw-bold">
                                                            {{ strtoupper(substr($article->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <span>{{ $article->user->name }}</span>
                                            @else
                                                <span class="text-muted">Usuário deletado</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'pending' ? 'warning' : 'danger') }} rounded-pill px-3 py-2">
                                            <i class="bi bi-{{ $article->status === 'published' ? 'check-circle' : ($article->status === 'pending' ? 'hourglass' : 'x-circle') }} me-1"></i>
                                            {{ $article->status === 'published' ? 'Publicado' : ($article->status === 'pending' ? 'Pendente' : 'Rascunho') }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-eye me-1"></i> {{ number_format($article->views, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $article->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="py-3 text-end px-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('articles.show', $article) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver artigo">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            {{-- Verifica se a rota admin.articles.edit existe --}}
                                            @if(Route::has('admin.articles.edit'))
                                                <a href="{{ route('admin.articles.edit', $article) }}" 
                                                   class="btn btn-sm btn-outline-secondary"
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            
                                            {{-- Botão de excluir apenas se tiver permissão --}}
                                            @if(auth()->user()->isAdmin() && Route::has('admin.articles.destroy'))
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteArticle({{ $article->id }})"
                                                        title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-file-earmark-text display-1 text-muted mb-3"></i>
                                        <h6 class="fw-bold mb-2">Nenhum artigo</h6>
                                        <p class="text-muted small mb-0">Os artigos recentes aparecerão aqui</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Mostrando os últimos {{ isset($recent_articles) ? $recent_articles->count() : 0 }} artigos
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este artigo? Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i> Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteArticle(articleId) {
    @if(Route::has('admin.articles.destroy'))
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/articles/${articleId}`;
        modal.show();
    @else
        window.DiscoforUI.showToast('A funcionalidade de exclusão não está disponível no momento.', 'warning');
    @endif
}
</script>
@endpush
@endsection
