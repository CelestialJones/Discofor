@extends('layouts.app')

@section('title', 'Discofor - Plataforma de Artigos e Debates Acadêmicos')
@section('description', 'Compartilhe seus artigos acadêmicos, debata ideias e colabore com a comunidade científica')

@section('content')
@php
    $likedArticleIds = [];
    if (auth()->check() && isset($featured_articles)) {
        $likedArticleIds = auth()->user()
            ->likes()
            ->whereIn('article_id', $featured_articles->pluck('id'))
            ->pluck('article_id')
            ->toArray();
    }
@endphp
<!-- Hero Section com gradiente melhorado e animação suave -->
<div class="container-fluid position-relative overflow-hidden" style="background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 50%, #c026d3 100%); padding: 100px 20px;">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white mb-4 mb-lg-0 animate__animated animate__fadeInUp">
                <div class="d-inline-block px-3 py-1 bg-white bg-opacity-20 rounded-pill mb-4" style="backdrop-filter: blur(5px);">
                    <span class="text-white"><i class="bi bi-stars me-2"></i>Plataforma Acadêmica #1</span>
                </div>
                <h1 class="display-3 fw-bold mb-3">Discofor</h1>
                <h2 class="h3 mb-4 opacity-90">Onde o conhecimento ganha voz</h2>
                <p class="lead mb-4 opacity-85 fs-4">
                    Compartilhe suas pesquisas, debata ideias inovadoras e colabore com mentes brilhantes ao redor do mundo.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    @auth
                        <a href="{{ route('articles.index') }}" class="btn btn-light btn-lg px-4 py-3 shadow-lg hover-lift">
                            <i class="bi bi-book me-2"></i> Explorar Artigos
                        </a>
                        <a href="{{ route('articles.create') }}" class="btn btn-outline-light btn-lg px-4 py-3 hover-lift">
                            <i class="bi bi-pencil-square me-2"></i> Publicar Artigo
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 py-3 shadow-lg hover-lift">
                            <i class="bi bi-person-plus me-2"></i> Começar Agora
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 py-3 hover-lift">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Entrar
                        </a>
                    @endauth
                </div>
                <div class="d-flex gap-4 mt-5">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <span>+5.000 artigos</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <span>+2.000 pesquisadores</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <span>100% gratuito</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative animate__animated animate__fadeInRight">
                    <div class="bg-white bg-opacity-10 rounded-4 p-5 text-center" style="backdrop-filter: blur(10px);">
                        <i class="bi bi-file-earmark-text-fill display-1 text-white mb-3"></i>
                        <p class="text-white fs-5">"Compartilhe conhecimento e transforme o mundo através da ciência"</p>
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <div class="bg-white bg-opacity-20 rounded-circle p-2"></div>
                            <div class="bg-white bg-opacity-20 rounded-circle p-2"></div>
                            <div class="bg-white rounded-circle p-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas com cards animados -->
<div class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="p-4 bg-white rounded-4 shadow-sm hover-lift">
                <div class="display-4 fw-bold text-primary mb-2">{{ $stats['articles'] ?? 0 }}</div>
                <p class="text-muted mb-0">
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                    Artigos Publicados
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 bg-white rounded-4 shadow-sm hover-lift">
                <div class="display-4 fw-bold text-primary mb-2">{{ $stats['users'] ?? 0 }}</div>
                <p class="text-muted mb-0">
                    <i class="bi bi-people me-2 text-primary"></i>
                    Usuários Ativos
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 bg-white rounded-4 shadow-sm hover-lift">
                <div class="display-4 fw-bold text-primary mb-2">{{ $stats['comments'] ?? 0 }}</div>
                <p class="text-muted mb-0">
                    <i class="bi bi-chat-dots me-2 text-primary"></i>
                    Discussões
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 bg-white rounded-4 shadow-sm hover-lift">
                <div class="display-4 fw-bold text-primary mb-2">{{ $stats['debates'] ?? 0 }}</div>
                <p class="text-muted mb-0">
                    <i class="bi bi-mic me-2 text-primary"></i>
                    Debates em Tempo Real
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Artigos Recentes com design melhorado -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold position-relative">
            Artigos em Destaque
            <span class="position-absolute bottom-0 start-0 w-50 h-1 bg-primary" style="height: 3px; width: 50px;"></span>
        </h2>
        <a href="{{ route('articles.index') }}" class="btn btn-link text-primary text-decoration-none">
            Ver todos <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    
    <div class="row g-4">
        @forelse($featured_articles as $article)
            <div class="col-lg-4 col-md-6">
                <div class="card article-card h-100 border-0 shadow-sm hover-lift rounded-4 overflow-hidden">
                    @if($article->featured_image)
                        <img src="{{ $article->featured_image }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-gradient-primary" style="height: 200px; background: linear-gradient(145deg, #4f46e5, #7c3aed);"></div>
                    @endif
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            @foreach($article->tags->take(3) as $tag)
                                <span class="badge bg-light text-primary px-3 py-2 rounded-pill">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark hover-primary">
                                {{ Str::limit($article->title, 60) }}
                            </a>
                        </h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($article->content, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="bi bi-person text-primary small"></i>
                                </div>
                                <small class="text-muted">{{ $article->user->name }}</small>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i> {{ $article->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-3 mt-3">
                            <small class="text-muted"><i class="bi bi-eye me-1"></i> {{ $article->views ?? 0 }}</small>
                            <small class="text-muted"><i class="bi bi-chat me-1"></i> {{ $article->comments_count ?? 0 }}</small>
                            <small class="text-muted"><i class="bi bi-heart me-1"></i> <span class="home-likes-count">{{ $article->likes_count ?? 0 }}</span></small>
                            @auth
                                <button type="button"
                                        class="btn btn-sm {{ in_array($article->id, $likedArticleIds) ? 'btn-primary' : 'btn-outline-primary' }} py-0 px-2 ms-auto home-like-btn"
                                        data-url="{{ route('likes.toggle', $article) }}">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary py-0 px-2 ms-auto">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info border-0 bg-light rounded-4 p-5 text-center">
                    <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                    <h4>Nenhum artigo publicado ainda</h4>
                    <p class="mb-0">Seja o primeiro a compartilhar seu conhecimento!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Features com design em grid melhorado -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="mb-5 fw-bold text-center position-relative">
            Por que escolher Discofor?
            <span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary" style="height: 3px; width: 80px;"></span>
        </h2>
        
        <div class="row g-4">
            @php
                $features = [
                    ['icon' => 'shield-check', 'title' => 'Seguro e Confiável', 'desc' => 'Sua segurança e privacidade são nossa prioridade. Todos os dados são protegidos com criptografia de ponta a ponta.'],
                    ['icon' => 'chat-dots', 'title' => 'Debates em Tempo Real', 'desc' => 'Participe de discussões acadêmicas ao vivo com pesquisadores de todo o mundo através de salas temáticas.'],
                    ['icon' => 'person-check', 'title' => 'Comunidade Ativa', 'desc' => 'Conecte-se com profissionais da área, compartilhe conhecimento e expanda sua rede de contatos acadêmicos.'],
                    ['icon' => 'search', 'title' => 'Busca Avançada', 'desc' => 'Encontre exatamente o que procura com filtros por área, autor, data e relevância.'],
                    ['icon' => 'graph-up', 'title' => 'Impacto Medido', 'desc' => 'Acompanhe o alcance do seu trabalho com métricas detalhadas e relatórios de engajamento.'],
                    ['icon' => 'phone', 'title' => 'Totalmente Responsivo', 'desc' => 'Acesse de qualquer dispositivo com experiência otimizada para computador, tablet ou smartphone.'],
                ];
            @endphp
            
            @foreach($features as $feature)
                <div class="col-lg-4 col-md-6">
                    <div class="bg-white p-4 rounded-4 shadow-sm hover-lift h-100">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="d-inline-flex align-items-center justify-content-center bg-gradient-primary text-white rounded-3" style="width: 50px; height: 50px; background: linear-gradient(145deg, #4f46e5, #7c3aed);">
                                    <i class="bi bi-{{ $feature['icon'] }} fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">{{ $feature['title'] }}</h5>
                                <p class="text-muted mb-0">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Final com design moderno -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="bg-gradient-primary text-white rounded-5 p-5 text-center" style="background: linear-gradient(145deg, #4f46e5, #7c3aed);">
                <h2 class="mb-4 fw-bold display-6">Pronto para fazer parte da comunidade?</h2>
                <p class="lead mb-4 opacity-90 fs-4">
                    Junte-se a milhares de pesquisadores que já compartilham conhecimento no Discofor.
                </p>
                @auth
                    <a href="{{ route('articles.index') }}" class="btn btn-light btn-lg px-5 py-3 hover-lift">
                        <i class="bi bi-compass me-2"></i> Explorar Plataforma
                    </a>
                @else
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 hover-lift">
                            <i class="bi bi-person-plus me-2"></i> Criar Conta Grátis
                        </a>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-light btn-lg px-5 py-3 hover-lift">
                            <i class="bi bi-eye me-2"></i> Ver Artigos
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

@auth
@push('scripts')
<script>
    document.querySelectorAll('.home-like-btn').forEach((btn) => {
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
                const likesLabel = this.parentElement.querySelector('.home-likes-count');
                if (likesLabel) likesLabel.textContent = data.likes_count;
            } catch (_) {
                window.DiscoforUI?.showToast('Não foi possível atualizar a curtida.', 'error');
            }
        });
    });
</script>
@endpush
@endauth
@endsection
