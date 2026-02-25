@extends('layouts.app')

@section('title', 'Meus Artigos - Discofor')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6 mb-2">
                <i class="bi bi-file-earmark-text"></i> Meus Artigos
            </h1>
            <p class="text-muted">Gerencie todos os seus artigos publicados</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Novo Artigo
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            @forelse($articles as $article)
                <div class="card mb-3 article-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">
                                    <a href="{{ route('articles.show', $article->slug) }}"
                                       class="text-decoration-none">
                                        {{ $article->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($article->content, 200) }}
                                </p>
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    @foreach($article->tags as $tag)
                                        <span class="badge bg-light text-dark">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y H:i') }}
                                    •
                                    <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="mb-2">
                                    <div class="d-flex gap-2 flex-column flex-md-row justify-content-md-end">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-eye"></i> {{ $article->views }} visualizações
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-hand-thumbs-up"></i> {{ $article->likes()->count() }} curtidas
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-chat"></i> {{ $article->comments()->count() }} comentários
                                        </span>
                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja deletar este artigo?')">
                                            <i class="bi bi-trash"></i> Deletar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Você ainda não publicou nenhum artigo.
                    <a href="{{ route('articles.create') }}" class="alert-link">Criar artigo agora</a>
                </div>
            @endforelse

            <!-- Pagination -->
            {{ $articles->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
