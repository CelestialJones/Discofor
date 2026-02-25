@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- User Header -->
    <div class="row mb-5">
        <div class="col-md-3 text-center mb-4 mb-md-0">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                <i class="bi bi-person" style="font-size: 4rem; color: #ccc;"></i>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center gap-2 mb-2">
                <h1 class="mb-0">{{ $user->name }}</h1>
                @if($user->isAdmin())
                    <span class="badge bg-danger">Administrador</span>
                @endif
            </div>
            <p class="text-muted mb-3">
                <i class="bi bi-envelope"></i> {{ $user->email }}
            </p>
            <div class="row text-center gap-3 mb-3">
                <div class="col-auto">
                    <strong>{{ $user->articles()->count() }}</strong>
                    <p class="text-muted small mb-0">Artigos</p>
                </div>
                <div class="col-auto">
                    <strong>{{ $user->comments()->count() }}</strong>
                    <p class="text-muted small mb-0">Comentários</p>
                </div>
                <div class="col-auto">
                    <strong>{{ $user->createdAt->diffInDays() }}</strong>
                    <p class="text-muted small mb-0">Dias na plataforma</p>
                </div>
            </div>
            @auth
                @if(auth()->user()->id !== $user->id)
                    <a href="mailto:{{ $user->email }}" class="btn btn-primary">
                        <i class="bi bi-envelope"></i> Contatar
                    </a>
                @else
                    <a href="{{ route('users.edit-profile') }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar Perfil
                    </a>
                @endif
            @endauth
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
                            <div class="card h-100 border-0 shadow-sm">
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
                <div class="alert alert-info">
                    <i class="bi bi-inbox"></i> Este usuário ainda não publicou artigos.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
