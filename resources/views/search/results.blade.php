@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">
                <i class="bi bi-search"></i> Resultados da Busca
            </h1>
            <p class="text-muted">
                @if($query)
                    Resultados para: <strong>"{{ $query }}"</strong>
                @else
                    Resultados da busca
                @endif
            </p>
        </div>
    </div>

    <!-- Filters and Sort -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="q" class="form-control" placeholder="Buscar..."
                                   value="{{ $query }}">
                        </div>

                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="all" @if($type === 'all') selected @endif>Todos</option>
                                <option value="articles" @if($type === 'articles') selected @endif>Artigos</option>
                                <option value="users" @if($type === 'users') selected @endif>Usuários</option>
                                <option value="tags" @if($type === 'tags') selected @endif>Tags</option>
                            </select>
                        </div>

                        @if($type === 'articles' || $type === 'all')
                            <div class="col-md-2">
                                <select name="sort" class="form-select">
                                    <option value="latest" @if($sort === 'latest') selected @endif>Mais Recentes</option>
                                    <option value="oldest" @if($sort === 'oldest') selected @endif>Mais Antigos</option>
                                    <option value="most_liked" @if($sort === 'most_liked') selected @endif>Mais Curtidos</option>
                                    <option value="most_commented" @if($sort === 'most_commented') selected @endif>Mais Comentados</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="tag" class="form-select">
                                    <option value="">Todas as Tags</option>
                                    @foreach($availableTags as $t)
                                        <option value="{{ $t->slug }}" @if($tag === $t->slug) selected @endif>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="author" class="form-select">
                                    <option value="">Todos os Autores</option>
                                    @foreach($availableAuthors as $a)
                                        <option value="{{ $a->id }}" @if($author == $a->id) selected @endif>
                                            {{ $a->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row">
        @if($type === 'articles' || $type === 'all')
            <div class="col-12 mb-5">
                <h4 class="mb-3">
                    <i class="bi bi-file-text"></i> Artigos
                    @if($articles)
                        <small class="text-muted">({{ $articles->total() }} encontrado{{ $articles->total() !== 1 ? 's' : '' }})</small>
                    @endif
                </h4>

                @if($articles && $articles->count() > 0)
                    <div class="row g-3">
                        @foreach($articles as $article)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                                {{ $article->title }}
                                            </a>
                                        </h5>
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($article->excerpt, 100) }}
                                        </p>
                                        <div class="mb-3">
                                            @forelse($article->tags as $tag)
                                                <a href="{{ route('search.search', ['tag' => $tag->slug]) }}"
                                                   class="badge bg-light text-dark text-decoration-none">
                                                    {{ $tag->name }}
                                                </a>
                                            @empty
                                            @endforelse
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center text-muted small">
                                            <span>
                                                <i class="bi bi-person"></i> {{ $article->user?->name ?? 'Usuário deletado' }}
                                            </span>
                                            <span>
                                                <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($articles->hasPages())
                        <div class="mt-4">
                            {{ $articles->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                @elseif($type === 'articles')
                    <div class="alert alert-info">
                        <i class="bi bi-inbox"></i> Nenhum artigo encontrado.
                    </div>
                @endif
            </div>
        @endif

        @if($type === 'users' || $type === 'all')
            <div class="col-12 mb-5">
                <h4 class="mb-3">
                    <i class="bi bi-people"></i> Usuários
                    @if($users)
                        <small class="text-muted">({{ $users->total() }} encontrado{{ $users->total() !== 1 ? 's' : '' }})</small>
                    @endif
                </h4>

                @if($users && $users->count() > 0)
                    <div class="row g-3">
                        @foreach($users as $user)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-person" style="font-size: 1.5rem; color: #ccc;"></i>
                                            </div>
                                        </div>
                                        <h5 class="card-title">
                                            <a href="{{ route('users.show', $user) }}" class="text-decoration-none">
                                                {{ $user->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <strong>{{ $user->articles()->count() }}</strong>
                                                <p class="text-muted small mb-0">Artigos</p>
                                            </div>
                                            <div class="col-6">
                                                <strong>{{ $user->comments()->count() }}</strong>
                                                <p class="text-muted small mb-0">Comentários</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary w-100">
                                            Ver Perfil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                @elseif($type === 'users')
                    <div class="alert alert-info">
                        <i class="bi bi-inbox"></i> Nenhum usuário encontrado.
                    </div>
                @endif
            </div>
        @endif

        @if($type === 'tags' || $type === 'all')
            <div class="col-12">
                <h4 class="mb-3">
                    <i class="bi bi-tags"></i> Tags
                    @if($tags)
                        <small class="text-muted">({{ $tags->total() }} encontrada{{ $tags->total() !== 1 ? 's' : '' }})</small>
                    @endif
                </h4>

                @if($tags && $tags->count() > 0)
                    <div class="row g-3">
                        @foreach($tags as $t)
                            <div class="col-md-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('search.search', ['tag' => $t->slug]) }}" class="text-decoration-none">
                                                {{ $t->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small">
                                            {{ $t->articles()->count() }} artigo{{ $t->articles()->count() !== 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($tags->hasPages())
                        <div class="mt-4">
                            {{ $tags->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                @elseif($type === 'tags')
                    <div class="alert alert-info">
                        <i class="bi bi-inbox"></i> Nenhuma tag encontrada.
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- No Results -->
    @if(($articles && $articles->count() === 0) && ($users && $users->count() === 0) && ($tags && $tags->count() === 0))
        <div class="alert alert-warning text-center py-5">
            <i class="bi bi-search" style="font-size: 2rem;"></i>
            <p class="mt-2">Nenhum resultado encontrado para sua busca.</p>
        </div>
    @endif
</div>
@endsection
