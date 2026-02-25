@extends('layouts.app')

@section('title', 'Meu Dashboard - Discofor')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6 mb-2">
                <i class="bi bi-speedometer2"></i> Meu Dashboard
            </h1>
            <p class="text-muted">Bem-vindo de volta, {{ auth()->user()->name }}!</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Novo Artigo
            </a>
            <a href="{{ route('users.edit-profile') }}" class="btn btn-outline-primary">
                <i class="bi bi-gear"></i> Configurações
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text" style="font-size: 2rem; color: #6366f1;"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_articles'] }}</h3>
                    <p class="text-muted small mb-0">Artigos Publicados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-hand-thumbs-up" style="font-size: 2rem; color: #8b5cf6;"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_likes'] }}</h3>
                    <p class="text-muted small mb-0">Curtidas Recebidas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-chat-dots" style="font-size: 2rem; color: #06b6d4;"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_comments'] }}</h3>
                    <p class="text-muted small mb-0">Comentários</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-hourglass-split" style="font-size: 2rem; color: #f59e0b;"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['pending_articles'] }}</h3>
                    <p class="text-muted small mb-0">Artigos Pendentes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Articles -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Artigos Recentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    @forelse($articles as $article)
                        <div class="d-flex gap-3 p-3 border-bottom align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('articles.show', $article->slug) }}"
                                       class="text-decoration-none">
                                        {{ $article->title }}
                                    </a>
                                </h6>
                                <p class="mb-1 text-muted small">
                                    {{ Str::limit($article->content, 80) }}
                                </p>
                                <small class="text-muted">
                                    <span class="badge bg-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox"></i> Você ainda não publicou nenhum artigo.
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="{{ route('users.my-articles') }}" class="text-decoration-none">
                        Ver todos os artigos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-activity"></i> Atividade Recente
                    </h5>
                </div>
                <div class="card-body p-0">
                    @forelse($recentActivity as $activity)
                        <div class="p-3 border-bottom">
                            <p class="mb-1 small">{{ $activity->description }}</p>
                            <small class="text-muted">
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted">
                            <i class="bi bi-inbox"></i> Sem atividade recente
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
