@extends('layouts.app')

@section('title', $user->name . ' - Discofor')
@section('description', 'Perfil de ' . $user->name . ' na plataforma Discofor')

@section('content')
<div class="container py-2">
    <!-- User Header -->
    <div class="row mb-5 mt-4">
        <div class="col">
            <div class="page-header d-flex gap-4 align-items-start flex-wrap">
                <div>
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                             class="rounded-circle" width="150" height="150"
                             alt="{{ $user->name }}">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border border-white border-2"
                             style="width: 150px; height: 150px;">
                            <i class="bi bi-person" style="font-size: 4rem; color: #91a6c6;"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h1 class="display-5 mb-1 fw-bold">{{ $user->name }}</h1>
                    <p class="mb-2 opacity-75">
                        <i class="bi bi-envelope"></i> {{ $user->email }}
                    </p>
                    @if($user->bio)
                        <p class="lead">{{ $user->bio }}</p>
                    @endif
                    <div class="mb-3">
                        <small class="opacity-75">
                            Membro desde {{ $user->created_at->format('d/m/Y') }}
                        </small>
                    </div>

                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('users.edit-profile') }}" class="btn btn-light">
                                <i class="bi bi-pencil-square me-1"></i> Editar Perfil
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="surface-card text-center hover-lift h-100">
                <div class="card-body">
                    <h4 class="mb-0">{{ $stats['articles'] }}</h4>
                    <p class="text-muted small mb-0">Artigos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="surface-card text-center hover-lift h-100">
                <div class="card-body">
                    <h4 class="mb-0">{{ $stats['likes'] }}</h4>
                    <p class="text-muted small mb-0">Curtidas Recebidas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="surface-card text-center hover-lift h-100">
                <div class="card-body">
                    <h4 class="mb-0">{{ $stats['comments'] }}</h4>
                    <p class="text-muted small mb-0">Comentários</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles -->
    <div class="row">
        <div class="col">
            <h2 class="mb-4">
                <i class="bi bi-file-earmark-text"></i> Artigos de {{ $user->name }}
            </h2>

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
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top px-4 py-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i>
                                    {{ $article->created_at->format('d/m/Y') }}
                                </small>
                                <div class="mt-2 d-flex gap-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-eye"></i> {{ $article->views }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-hand-thumbs-up"></i> {{ $article->likes()->count() }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-chat"></i> {{ $article->comments()->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info border-0 shadow-sm">
                            <i class="bi bi-info-circle"></i> {{ $user->name }} não publicou nenhum artigo ainda.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $articles->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
