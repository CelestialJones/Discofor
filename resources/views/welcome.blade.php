@extends('layouts.app')

@section('title', 'Bem-vindo ao Discofor')
@section('description', 'Plataforma acadêmica para publicação de artigos e debates em tempo real')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row align-items-center py-5 mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h1 class="display-4 fw-bold mb-3">
                <span style="color: #6366f1;">Discofor</span>
            </h1>
            <h2 class="display-6 mb-3">Plataforma Acadêmica de Publicação e Debates</h2>
            <p class="lead text-muted mb-4">
                Publique artigos de qualidade, participe de debates em tempo real e conecte-se com a comunidade acadêmica global.
            </p>
            <div class="d-flex gap-3">
                <a href="{{ route('articles.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-right"></i> Explorar Artigos
                </a>
                @if (!auth()->check())
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-pencil-square"></i> Começar
                    </a>
                @endif
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="p-5 rounded-lg bg-light" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-chat-left-text" style="font-size: 8rem; color: #6366f1; opacity: 0.2;"></i>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <h2 class="text-center mb-4">Recursos Principais</h2>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #6366f1;"></i>
                    <h5 class="card-title mt-3">Publicação de Artigos</h5>
                    <p class="card-text text-muted">
                        Publique seus artigos acadêmicos com formatação rica, tags e imagens.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-chat-dots" style="font-size: 3rem; color: #6366f1;"></i>
                    <h5 class="card-title mt-3">Debates em Tempo Real</h5>
                    <p class="card-text text-muted">
                        Participe de discussões ao vivo com chat em tempo real via WebSockets.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up" style="font-size: 3rem; color: #6366f1;"></i>
                    <h5 class="card-title mt-3">Análises Detalhadas</h5>
                    <p class="card-text text-muted">
                        Acompanhe estatísticas de visualizações, curtidas e engajamento dos seus artigos.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-hand-thumbs-up" style="font-size: 3rem; color: #6366f1;"></i>
                    <h5 class="card-title mt-3">Sistema de Curtidas</h5>
                    <p class="card-text text-muted">
                        Reconheça artigos de qualidade curtindo e comentando em conteúdos.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-tag" style="font-size: 3rem; color: #6366f1;"></i>
                    <h5 class="card-title mt-3">Categorias e Tags</h5>
                    <p class="card-text text-muted">
                        Organize conteúdos com tags temáticas para melhor descoberta.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check" style="font-size: 3rem; color: #6366f1;"></i>
                    <h5 class="card-title mt-3">Segurança</h5>
                    <p class="card-text text-muted">
                        Plataforma segura com autenticação robusta e validações rigorosas.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row my-5 py-5 bg-light rounded-lg">
        <div class="col-md-3 text-center mb-3">
            <h3 class="display-6" style="color: #6366f1;">{{ \App\Models\Article::count() }}</h3>
            <p class="text-muted">Artigos Publicados</p>
        </div>
        <div class="col-md-3 text-center mb-3">
            <h3 class="display-6" style="color: #6366f1;">{{ \App\Models\User::count() }}</h3>
            <p class="text-muted">Usuários Cadastrados</p>
        </div>
        <div class="col-md-3 text-center mb-3">
            <h3 class="display-6" style="color: #6366f1;">{{ \App\Models\Comment::count() }}</h3>
            <p class="text-muted">Comentários</p>
        </div>
        <div class="col-md-3 text-center mb-3">
            <h3 class="display-6" style="color: #6366f1;">{{ \App\Models\Like::count() }}</h3>
            <p class="text-muted">Curtidas Recebidas</p>
        </div>
    </div>

    <!-- CTA Section -->
    @if (!auth()->check())
        <div class="row my-5 text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-3">Pronto para começar?</h2>
                <p class="lead text-muted mb-4">
                    Cadastre-se agora e faça parte da comunidade acadêmica Discofor.
                </p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-pencil-square"></i> Criar Conta
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
