@extends('layouts.app')

@section('title', 'Discofor - Plataforma de Artigos e Debates Acadêmicos')
@section('description', 'Compartilhe seus artigos acadêmicos, debata ideias e colabore com a comunidade científica')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 80px 20px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Discofor</h1>
                <h2 class="h4 mb-4 opacity-90">Plataforma de Artigos e Debates Acadêmicos</h2>
                <p class="lead mb-4 opacity-85">
                    Compartilhe seus conhecimentos, debata ideias e colabore com pesquisadores ao redor do mundo.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    @auth
                        <a href="{{ route('articles.index') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-book"></i> Explorar Artigos
                        </a>
                        <a href="{{ route('articles.create') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-pencil-square"></i> Publicar Artigo
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-person-plus"></i> Começar Agora
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-white opacity-75">
                    <div class="display-6">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <p class="mt-3">Acesso a milhares de artigos de qualidade</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="p-4">
                <div class="h3 fw-bold text-primary">{{ $stats['articles'] ?? 0 }}</div>
                <p class="text-muted">Artigos Publicados</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4">
                <div class="h3 fw-bold text-primary">{{ $stats['users'] ?? 0 }}</div>
                <p class="text-muted">Usuários Ativos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4">
                <div class="h3 fw-bold text-primary">{{ $stats['comments'] ?? 0 }}</div>
                <p class="text-muted">Comentários e Discussões</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4">
                <div class="h3 fw-bold text-primary">{{ $stats['debates'] ?? 0 }}</div>
                <p class="text-muted">Debates em Tempo Real</p>
            </div>
        </div>
    </div>
</div>

<!-- Artigos Recentes -->
<div class="container py-5">
    <h2 class="mb-5 fw-bold">Artigos em Destaque</h2>
    <div class="row g-4">
        @forelse($featured_articles as $article)
            <div class="col-lg-4 col-md-6">
                <div class="card article-card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            @foreach($article->tags as $tag)
                                <span class="badge bg-light text-primary">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <h5 class="card-title">
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($article->title, 60) }}
                            </a>
                        </h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($article->excerpt, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $article->user->name }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhum artigo publicado ainda
                </div>
            </div>
        @endforelse
    </div>
    <div class="text-center mt-5">
        <a href="{{ route('articles.index') }}" class="btn btn-primary btn-lg">
            Ver Mais Artigos <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>

<!-- Features -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="mb-5 fw-bold">Por que escolher Discofor?</h2>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px;">
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">Seguro e Confiável</h5>
                        <p class="text-muted mb-0">Sua segurança e privacidade são nossa prioridade. Todos os dados são protegidos.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px;">
                            <i class="bi bi-chat-dots fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">Debates em Tempo Real</h5>
                        <p class="text-muted mb-0">Participe de discussões acadêmicas em tempo real com pesquisadores de todo o mundo.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-check fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">Comunidade Ativa</h5>
                        <p class="text-muted mb-0">Conecte-se com profissionais da área, compartilhe conhecimento e expanda sua rede.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px;">
                            <i class="bi bi-search fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">Busca Avançada</h5>
                        <p class="text-muted mb-0">Encontre facilmente o conteúdo que procura com nosso sistema de busca inteligente.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px;">
                            <i class="bi bi-graph-up fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">Impacto Medido</h5>
                        <p class="text-muted mb-0">Acompanhe o desempenho do seu trabalho com métricas detalhadas e relatórios.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 50px; height: 50px;">
                            <i class="bi bi-phone fs-5"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">Responsivo</h5>
                        <p class="text-muted mb-0">Acesse de qualquer dispositivo, computador, tablet ou smartphone.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Final -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="mb-4 fw-bold">Pronto para começar?</h2>
            <p class="lead mb-4 text-muted">
                Junte-se a milhares de pesquisadores e acadêmicos que já usam Discofor para compartilhar conhecimento.
            </p>
            @auth
                <a href="{{ route('articles.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-book"></i> Explorar Plataforma
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Criar Conta Grátis
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-3">
                <h5 class="fw-bold mb-3">Discofor</h5>
                <p class="text-muted mb-0">Plataforma acadêmica de artigos e debates científicos.</p>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold mb-3">Navegação</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('articles.index') }}" class="text-muted text-decoration-none">Artigos</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Sobre</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Contato</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold mb-3">Legal</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-muted text-decoration-none">Privacidade</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Termos</a></li>
                    <li><a href="#" class="text-muted text-decoration-none">Cookies</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-bold mb-3">Redes Sociais</h6>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-muted">
            <p class="mb-0">&copy; {{ date('Y') }} Discofor. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
