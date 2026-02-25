@extends('layouts.app')

@section('title', 'Artigos - Discofor')
@section('description', 'Explore os melhores artigos acadêmicos da plataforma Discofor')

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-md-9">
            <div class="mb-4">
                <h1 class="display-5 mb-2">Artigos</h1>
                <p class="text-muted">Descubra artigos acadêmicos de qualidade da nossa comunidade</p>
            </div>

            <!-- Search and Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Buscar artigos..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="sort" class="form-select">
                                <option value="">Ordenar por</option>
                                <option value="likes" {{ request('sort') === 'likes' ? 'selected' : '' }}>
                                    Mais Curtidos
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Articles Grid -->
            <div class="row g-4">
                @forelse($articles as $article)
                    <div class="col-md-6">
                        <div class="card article-card h-100">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top"
                                     alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light p-5 text-center"
                                     style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-text" style="font-size: 3rem; color: #cbd5e1;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('articles.show', $article->slug) }}"
                                       class="text-decoration-none text-dark">
                                        {{ $article->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($article->content, 100) }}
                                </p>
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    @foreach($article->tags as $tag)
                                        <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}"
                                           class="badge bg-light text-dark text-decoration-none">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i>
                                    <a href="#" class="text-decoration-none">{{ $article->user->name }}</a>
                                    •
                                    <i class="bi bi-calendar"></i>
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                                <div class="mt-2 d-flex gap-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-eye"></i> {{ $article->views }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-hand-thumbs-up"></i> {{ $article->likes_count ?? $article->likes()->count() }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-chat"></i> {{ $article->comments_count ?? $article->comments()->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nenhum artigo encontrado.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $articles->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-tags"></i> Tags Populares
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($tags as $tag)
                        <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}"
                           class="badge bg-primary text-decoration-none mb-2">
                            {{ $tag->name }}
                            <span class="badge bg-light text-dark">{{ $tag->articles_count }}</span>
                        </a>
                    @empty
                        <p class="text-muted small">Nenhuma tag disponível</p>
                    @endforelse
                </div>
            </div>

            @auth
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('articles.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Criar Artigo
                        </a>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-3">
                            <i class="bi bi-lock"></i> Faça login para criar artigos
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Entrar</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">Registrar</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
