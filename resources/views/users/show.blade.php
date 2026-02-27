@extends('layouts.app')

@section('title', $user->name . ' - Perfil')

@section('content')
<div class="container py-3">
    <!-- User Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header d-flex gap-4 align-items-start flex-wrap">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border border-white border-2" style="width: 120px; height: 120px;">
                    <i class="bi bi-person" style="font-size: 3rem; color: #8ca2c2;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h1 class="mb-0">{{ $user->name }}</h1>
                        @if($user->isAdmin())
                            <span class="badge bg-danger">Administrador</span>
                        @endif
                    </div>
                    <p class="mb-3 opacity-75">
                        <i class="bi bi-envelope"></i> {{ $user->email }}
                    </p>
                    <div class="row text-center gap-3 mb-3">
                        <div class="col-auto">
                            <strong>{{ $user->articles()->count() }}</strong>
                            <p class="small mb-0 opacity-75">Artigos</p>
                        </div>
                        <div class="col-auto">
                            <strong>{{ $user->comments()->count() }}</strong>
                            <p class="small mb-0 opacity-75">Comentários</p>
                        </div>
                        <div class="col-auto">
                            <strong>{{ $user->created_at->diffInDays() }}</strong>
                            <p class="small mb-0 opacity-75">Dias na plataforma</p>
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->id !== $user->id)
                            <a href="mailto:{{ $user->email }}" class="btn btn-light">
                                <i class="bi bi-envelope me-1"></i> Contatar
                            </a>
                        @else
                            <a href="{{ route('users.edit-profile') }}" class="btn btn-light">
                                <i class="bi bi-pencil me-1"></i> Editar Perfil
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- User Articles -->
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">
                <i class="bi bi-file-text"></i> Artigos de {{ $user->name }}
            </h3>

            @if($user->articles()->published()->count() > 0)
                <div class="row g-3">
                    @foreach($user->articles()->published()->latest()->take(6)->get() as $article)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                            {{ Str::limit($article->title, 50) }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($article->excerpt, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ $article->created_at->format('d/m/Y') }}
                                        </small>
                                        <span class="badge bg-success">{{ $article->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($user->articles()->published()->count() > 6)
                    <div class="text-center mt-4">
                        <a href="{{ route('articles.index', ['author' => $user->id]) }}" class="btn btn-outline-primary">
                            Ver todos os artigos de {{ $user->name }}
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i> Este usuário ainda não publicou artigos.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
