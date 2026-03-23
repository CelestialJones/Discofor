@extends('layouts.app')

@section('title', 'Artigos - Discofor')
@section('description', 'Explore os melhores artigos acadêmicos da plataforma Discofor')

@section('content')
@php
    $likedArticleIds = [];
    if (auth()->check() && isset($articles)) {
        $likedArticleIds = auth()->user()
            ->likes()
            ->whereIn('article_id', $articles->pluck('id'))
            ->pluck('article_id')
            ->toArray();
    }
@endphp
<div class="container py-2">
    <div class="row mb-5">
        <div class="col-md-9">
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">Artigos</h1>
                        <p class="mb-0 opacity-75">Descubra conteúdos acadêmicos relevantes da comunidade.</p>
                    </div>
                    @auth
                        <a href="{{ route('articles.create') }}" class="btn btn-light">
                            <i class="bi bi-pencil-square me-1"></i> Publicar artigo
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="surface-card mb-4">
                <div class="card-body p-4">
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
                            <button type="submit" class="btn btn-primary w-100 h-100">
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
                        <div class="card article-card h-100 hover-lift border-0">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top"
                                     alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light p-5 text-center border-bottom"
                                     style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-text" style="font-size: 3rem; color: #91a6c6;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold">
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
                                    @if($article->status === 'published')
                                        <a href="{{ route('articles.pdf', $article->slug) }}"
                                           class="badge bg-danger text-decoration-none">
                                            <i class="bi bi-filetype-pdf"></i> PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top px-4 py-3">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i>
                                    <a href="#" class="text-decoration-none">{{ $article->user->name }}</a>
                                    •
                                    <i class="bi bi-calendar"></i>
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                                <div class="mt-2 d-flex gap-2">
                                    @if($article->status === 'published')
                                        <a href="{{ route('articles.pdf', $article->slug) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-filetype-pdf"></i>
                                        </a>
                                    @endif
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-eye"></i> {{ $article->views }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-hand-thumbs-up"></i> <span class="article-likes-count">{{ $article->likes_count ?? $article->likes()->count() }}</span>
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-chat"></i> {{ $article->comments_count ?? $article->comments()->count() }}
                                    </span>
                                    @auth
                                        <button type="button"
                                                class="btn btn-sm {{ in_array($article->id, $likedArticleIds) ? 'btn-primary' : 'btn-outline-primary' }} ms-auto article-like-btn"
                                                data-url="{{ route('likes.toggle', $article) }}">
                                            <i class="bi bi-hand-thumbs-up"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary ms-auto">
                                            <i class="bi bi-hand-thumbs-up"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info border-0 shadow-sm">
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
            <div class="surface-card mb-4">
                <div class="card-header bg-light border-0">
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
                <div class="surface-card">
                    <div class="card-body">
                        <a href="{{ route('articles.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Criar Artigo
                        </a>
                    </div>
                </div>
            @else
                <div class="surface-card">
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
@auth
@push('scripts')
<script>
    document.querySelectorAll('.article-like-btn').forEach((btn) => {
        btn.addEventListener('click', async function () {
            try {
                const response = await fetch(this.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error('Erro ao curtir artigo');

                this.classList.remove('like-animate');
                void this.offsetWidth;
                this.classList.add('like-animate');
                if (data.liked) {
                    window.DiscoforUI?.playLikeSound();
                }
                this.classList.toggle('btn-primary', data.liked);
                this.classList.toggle('btn-outline-primary', !data.liked);
                const count = this.parentElement.querySelector('.article-likes-count');
                if (count) count.textContent = data.likes_count;
            } catch (_) {
                window.DiscoforUI?.showToast('Não foi possível atualizar a curtida.', 'error');
            }
        });
    });
</script>
@endpush
@endauth
@endsection
