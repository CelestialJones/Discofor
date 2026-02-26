@extends('layouts.app')

@section('title', $article->title . ' - Discofor')
@section('description', Str::limit($article->content, 160))

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Article -->
        <div class="col-lg-8">
            <!-- Article Header -->
            <article class="mb-5">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" class="img-fluid rounded-lg mb-4"
                         alt="{{ $article->title }}" style="max-height: 500px; object-fit: cover; width: 100%;">
                @else
                    <div class="bg-light rounded-lg mb-4 d-flex align-items-center justify-content-center"
                         style="height: 400px;">
                        <i class="bi bi-file-text" style="font-size: 5rem; color: #cbd5e1;"></i>
                    </div>
                @endif

                <h1 class="display-5 mb-2 text-balance">{{ $article->title }}</h1>

                <!-- Article Meta -->
                <div class="d-flex gap-3 align-items-center mb-4 pb-3 border-bottom">
                    <a href="{{ route('users.show', $article->user) }}" class="d-flex align-items-center gap-2 text-decoration-none">
                        @if($article->user->avatar)
                            <img src="{{ asset('storage/' . $article->user->avatar) }}"
                                 class="rounded-circle" width="40" height="40"
                                 alt="{{ $article->user->name }}">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <p class="mb-0 fw-semibold">{{ $article->user->name }}</p>
                            <small class="text-muted">{{ $article->created_at->format('d \\d\\e M \\d\\e Y') }}</small>
                        </div>
                    </a>

                    <span class="badge bg-light text-dark ms-auto">
                        <i class="bi bi-eye"></i> {{ $article->views }} visualizações
                    </span>
                </div>

                <!-- Tags -->
                @if($article->tags->isNotEmpty())
                    <div class="mb-4">
                        @foreach($article->tags as $tag)
                            <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}"
                               class="badge bg-primary text-decoration-none">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Article Content -->
                <div class="article-content mb-5" style="line-height: 1.8; font-size: 1.1rem;">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <!-- Article Footer -->
                <div class="d-flex gap-2 align-items-center mb-5 pb-3 border-bottom">
                    <button class="btn btn-outline-primary" id="like-btn" onclick="toggleLike()">
                        <i class="bi bi-hand-thumbs-up"></i> <span id="likes-count">{{ $article->likes()->count() }}</span>
                    </button>

                    <span class="text-muted small">
                        {{ $article->comments()->count() }} comentários
                    </span>

                    @auth
                        @if(auth()->id() === $article->user_id || auth()->user()->isAdmin())
                            <div class="ms-auto">
                                <a href="{{ route('articles.edit', $article->slug) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('articles.destroy', $article->slug) }}" method="POST"
                                      style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Tem certeza?')">
                                        <i class="bi bi-trash"></i> Deletar
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Debates Section -->
                <section id="debates" class="mb-5">
                    <h3 class="mb-4">
                        <i class="bi bi-chat-dots"></i> Debates em Tempo Real
                        <span class="badge bg-light text-dark" id="debates-count">{{ $debates->total() }}</span>
                    </h3>

                    @auth
                        <!-- Create Debate Form -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <form method="POST" action="{{ route('debates.store', $article) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="debate-title" class="form-label">Iniciar um novo debate</label>
                                        <input type="text" class="form-control" id="debate-title" name="title"
                                               placeholder="Título do debate" required minlength="5" maxlength="255">
                                    </div>
                                    <div class="mb-3">
                                        <label for="debate-description" class="form-label">Descrição (opcional)</label>
                                        <textarea class="form-control" id="debate-description" name="description"
                                                  placeholder="Descreva o tema do debate..." rows="2" maxlength="500"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-chat-dots"></i> Criar Debate
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i>
                            <a href="{{ route('login') }}" class="alert-link">Faça login</a> para iniciar um debate.
                        </div>
                    @endauth

                    <!-- Active Debates List -->
                    <div id="debates-list">
                    @forelse($debates as $debate)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex gap-3 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('debates.show', $debate) }}"
                                               class="text-decoration-none">
                                                {{ $debate->title }}
                                            </a>
                                        </h6>
                                        @if($debate->description)
                                            <p class="text-muted small mb-2">{{ $debate->description }}</p>
                                        @endif
                                        <div class="d-flex gap-3">
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> {{ $debate->creator->name }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> {{ $debate->created_at->diffForHumans() }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-chat"></i> {{ $debate->messagesCount() }} mensagens
                                            </small>
                                            <span class="badge bg-{{ $debate->isActive() ? 'success' : 'secondary' }} ms-auto">
                                                {{ $debate->isActive() ? 'Ativo' : 'Encerrado' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-top">
                                <a href="{{ route('debates.show', $debate) }}" class="text-decoration-none">
                                    Participar do debate <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="bi bi-inbox"></i> Nenhum debate iniciado ainda. @auth Crie o primeiro! @endauth
                        </div>
                    @endforelse

                    </div>

                    <!-- Pagination -->
                    {{ $debates->links('vendor.pagination.bootstrap-5') }}
                </section>

                <!-- Comments Section -->
                <section id="comments">
                    <h3 class="mb-4">
                        <i class="bi bi-chat-left-text"></i> Comentários
                        <span class="badge bg-light text-dark">{{ $article->comments()->count() }}</span>
                    </h3>

                    @auth
                        <!-- Add Comment Form -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <form id="comment-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="comment-content" class="form-label">Adicionar Comentário</label>
                                        <textarea class="form-control" id="comment-content" name="content"
                                                  rows="3" placeholder="Seu comentário aqui..."
                                                  required minlength="5" maxlength="1000"></textarea>
                                        <small class="text-muted">Mínimo 5 caracteres</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i> Comentar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i>
                            <a href="{{ route('login') }}" class="alert-link">Faça login</a> para comentar neste artigo.
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div id="comments-list">
                        @forelse($comments as $comment)
                            <div class="card mb-3 border-0 shadow-sm" id="comment-{{ $comment->id }}">
                                <div class="card-body">
                                    <div class="d-flex gap-2 mb-2 align-items-start">
                                        <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none">
                                            @if($comment->user->avatar)
                                                <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                                                     class="rounded-circle" width="40" height="40"
                                                     alt="{{ $comment->user->name }}">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person text-muted"></i>
                                                </div>
                                            @endif
                                        </a>
                                        <div class="flex-grow-1">
                                            <p class="mb-1">
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </p>
                                            <p class="mb-0">{{ $comment->content }}</p>
                                        </div>

                                        @auth
                                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                            onclick="editComment({{ $comment->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                            onclick="deleteComment({{ $comment->id }})">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-inbox"></i> Nenhum comentário ainda. Seja o primeiro a comentar!
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    {{ $comments->links('vendor.pagination.bootstrap-5') }}
                </section>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Article Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informações</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Status</small>
                        <p class="mb-0">
                            <span class="badge bg-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Publicado em</small>
                        <p class="mb-0">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Última atualização</small>
                        <p class="mb-0">{{ $article->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Author Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Autor</h6>
                </div>
                <div class="card-body text-center">
                    @if($article->user->avatar)
                        <img src="{{ asset('storage/' . $article->user->avatar) }}"
                             class="rounded-circle mb-2" width="80" height="80"
                             alt="{{ $article->user->name }}">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2"
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-person" style="font-size: 2rem; color: #cbd5e1;"></i>
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $article->user->name }}</h5>
                    @if($article->user->bio)
                        <p class="text-muted small mb-3">{{ $article->user->bio }}</p>
                    @endif
                    <a href="{{ route('users.show', $article->user) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right"></i> Ver Perfil
                    </a>
                </div>
            </div>

            <!-- Similar Articles Card -->
            @php
                $userTagIds = auth()->check() ? \App\Models\Article::where('user_id', auth()->id())
                    ->join('article_tag', 'articles.id', '=', 'article_tag.article_id')
                    ->pluck('article_tag.tag_id')
                    ->unique()
                    ->toArray() : [];
                $similarArticles = \App\Models\Article::where('status', 'published')
                    ->where('id', '!=', $article->id)
                    ->whereHas('tags', function ($q) use ($userTagIds) {
                        $q->whereIn('tags.id', $userTagIds);
                    })
                    ->limit(3)
                    ->get();
            @endphp

            @if($similarArticles->isNotEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h6 class="mb-0"><i class="bi bi-bookmark"></i> Artigos Relacionados</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($similarArticles as $similar)
                            <a href="{{ route('articles.show', $similar->slug) }}"
                               class="d-block p-3 border-bottom text-decoration-none text-dark hover-shadow">
                                <small class="text-muted d-block">{{ $similar->user->name }}</small>
                                <p class="mb-1 fw-semibold">{{ Str::limit($similar->title, 50) }}</p>
                                <small class="text-muted">{{ $similar->created_at->format('d/m/Y') }}</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Like article
    function toggleLike() {
        @auth
            fetch('{{ route("likes.toggle", $article) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById('like-btn');
                const count = document.getElementById('likes-count');
                count.textContent = data.likes_count;
                if (data.liked) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary');
                } else {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                }
            })
            .catch(error => console.error('Error:', error));
        @else
            window.location.href = '{{ route("login") }}';
        @endauth
    }

    // Submit comment form
    @auth
        document.getElementById('comment-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const content = document.getElementById('comment-content').value;

            fetch('{{ route("comments.store", $article) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('comment-content').value = '';
                    // append new comment locally
                    const list = document.getElementById('comments-list');
                    const html = `
                        <div class="card mb-3 border-0 shadow-sm" id="comment-${data.comment.id}">
                            <div class="card-body">
                                <div class="d-flex gap-2 mb-2 align-items-start">
                                    <a href="{{ route('users.show', auth()->user()) }}" class="text-decoration-none">
                                        ${data.comment.user_avatar ? `<img src="/storage/${data.comment.user_avatar}" class="rounded-circle" width="40" height="40" alt="${data.comment.user_name}">` : `<div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-person text-muted"></i></div>`}
                                    </a>
                                    <div>
                                        <p class="mb-1 fw-bold small"><a href="{{ route('users.show', auth()->user()) }}" class="text-decoration-none text-dark">${data.comment.user_name}</a></p>
                                        <p class="mb-0">${data.comment.content}</p>
                                        <small class="text-muted">agora</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('afterbegin', html);
                    // update count badge
                    const countSpan = document.querySelector('#comments h3 .badge');
                    if (countSpan) {
                        countSpan.textContent = parseInt(countSpan.textContent) + 1;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    @endauth

    // Real-time updates via Echo on article channel
    if (window.Echo) {
        window.Echo.private(`article.{{ $article->id }}`)
            .listen('.comment.created', (evt) => {
                // another user added comment
                const list = document.getElementById('comments-list');
                const html = `
                    <div class="card mb-3 border-0 shadow-sm" id="comment-${evt.comment.id}">
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-2 align-items-start">
                                <a href="/users/${evt.comment.user.id}" class="text-decoration-none">
                                    ${evt.comment.user.avatar ? `<img src="/storage/${evt.comment.user.avatar}" class="rounded-circle" width="40" height="40" alt="${evt.comment.user.name}">` : `<div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-person text-muted"></i></div>`}
                                </a>
                                <div>
                                    <p class="mb-1 fw-bold small"><a href="/users/${evt.comment.user.id}" class="text-decoration-none text-dark">${evt.comment.user.name}</a></p>
                                    <p class="mb-0">${evt.comment.content}</p>
                                    <small class="text-muted">agora</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                list.insertAdjacentHTML('afterbegin', html);
                const countSpan = document.querySelector('#comments h3 .badge');
                if (countSpan) {
                    countSpan.textContent = parseInt(countSpan.textContent) + 1;
                }
            })
            .listen('.article.liked', (evt) => {
                const count = document.getElementById('likes-count');
                if (count) {
                    count.textContent = parseInt(count.textContent) + 1;
                }
            })
            .listen('.debate.created', (evt) => {
                // update debates badge
                const badge = document.getElementById('debates-count');
                if (badge) {
                    badge.textContent = parseInt(badge.textContent) + 1;
                }

                // refresh notifications count badge as well
                if (window.updateNotificationsCount) {
                    window.updateNotificationsCount();
                }

                // prepend new debate card to list
                const list = document.getElementById('debates-list');
                if (list) {
                    const d = evt.debate;
                    const forumVote = ''; // placeholder if needed
                    const html = `
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex gap-3 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="/debates/${d.id}" class="text-decoration-none">
                                                ${d.title}
                                            </a>
                                        </h6>
                                        <div class="d-flex gap-3">
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> ${d.creator_name || 'Usuário'}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> agora
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-chat"></i> 0 mensagens
                                            </small>
                                            <span class="badge bg-success ms-auto">
                                                Ativo
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-top">
                                <a href="/debates/${d.id}" class="text-decoration-none">
                                    Participar do debate <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('afterbegin', html);
                }
            });
    }

    // Delete comment
    function deleteComment(commentId) {
        if (confirm('Tem certeza que deseja deletar este comentário?')) {
            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`comment-${commentId}`).remove();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Delete comment
    function deleteComment(commentId) {
        if (confirm('Tem certeza que deseja deletar este comentário?')) {
            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`comment-${commentId}`).remove();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endpush
@endsection
