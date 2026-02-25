@extends('layouts.app')

@section('title', $debate->title . ' - Debate')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Debate Info -->
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('articles.index') }}">Artigos</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('articles.show', $debate->article->slug) }}">{{ $debate->article->title }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $debate->title }}</li>
                </ol>
            </nav>

            <!-- Debate Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex gap-3 align-items-start justify-content-between">
                        <div>
                            <h2 class="mb-2">{{ $debate->title }}</h2>
                            @if($debate->description)
                                <p class="text-muted mb-0">{{ $debate->description }}</p>
                            @endif
                        </div>
                        <span class="badge bg-{{ $debate->isActive() ? 'success' : 'secondary' }}">
                            {{ $debate->isActive() ? 'Ativo' : 'Encerrado' }}
                        </span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex gap-2 align-items-center">
                        <img src="{{ $debate->creator->avatar ? asset('storage/' . $debate->creator->avatar) : '' }}"
                             class="rounded-circle" width="40" height="40"
                             alt="{{ $debate->creator->name }}">
                        <div>
                            <p class="mb-0">
                                <strong>{{ $debate->creator->name }}</strong>
                            </p>
                            <small class="text-muted">
                                {{ $debate->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        @if(auth()->id() === $debate->created_by || auth()->user()?->isAdmin())
                            <div class="ms-auto btn-group btn-group-sm">
                                @if($debate->isActive())
                                    <form action="{{ route('debates.close', $debate) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning">
                                            <i class="bi bi-lock"></i> Encerrar
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('debates.destroy', $debate) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza?')">
                                        <i class="bi bi-trash"></i> Deletar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="card border-0 shadow-sm" style="height: 500px; display: flex; flex-direction: column;">
                <!-- Messages List -->
                <div class="card-body" id="messages-container" style="flex: 1; overflow-y: auto; background-color: #f8f9fa;">
                    @forelse($messages as $message)
                        <div class="d-flex gap-2 mb-3 align-items-end">
                            <img src="{{ $message->user->avatar ? asset('storage/' . $message->user->avatar) : '' }}"
                                 class="rounded-circle" width="32" height="32"
                                 alt="{{ $message->user->name }}"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22><circle cx=%2216%22 cy=%2216%22 r=%2216%22 fill=%22%23e2e8f0%22/></svg>'">
                            <div class="flex-grow-1">
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <p class="mb-1 fw-semibold small">{{ $message->user->name }}</p>
                                    <p class="mb-0">{{ $message->message }}</p>
                                    <small class="text-muted d-block mt-1">
                                        {{ $message->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                            @auth
                                @if(auth()->id() === $message->user_id || auth()->user()->isAdmin())
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteMessage({{ $message->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-chat-left" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mt-2">Nenhuma mensagem ainda. Comece a discussão!</p>
                        </div>
                    @endforelse
                </div>

                <script>let lastMessageId = {{ $messages->last()?->id ?? 0 }};</script>

                <!-- Message Form -->
                @if($debate->isActive() && auth()->check())
                    <div class="card-footer bg-white border-top">
                        <form id="message-form" class="d-flex gap-2">
                            @csrf
                            <input type="text" id="message-input" name="message"
                                   class="form-control" placeholder="Digite sua mensagem..."
                                   required minlength="1" maxlength="500">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Enviar
                            </button>
                        </form>
                    </div>
                @elseif($debate->isActive())
                    <div class="card-footer bg-light text-center">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Faça login para participar
                        </a>
                    </div>
                @else
                    <div class="card-footer bg-warning bg-opacity-10">
                        <p class="mb-0 text-center text-muted">
                            <i class="bi bi-lock"></i> Este debate foi encerrado
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Article Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Artigo Original</h6>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">
                        <a href="{{ route('articles.show', $debate->article->slug) }}"
                           class="text-decoration-none">
                            {{ $debate->article->title }}
                        </a>
                    </h6>
                    <p class="small text-muted mb-3">
                        {{ Str::limit($debate->article->content, 100) }}
                    </p>
                    <a href="{{ route('articles.show', $debate->article->slug) }}"
                       class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-arrow-right"></i> Ver Artigo
                    </a>
                </div>
            </div>

            <!-- Participants Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0">
                        <i class="bi bi-people"></i> Participantes
                        <span class="badge bg-primary">{{ $debate->messages()->distinct('user_id')->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @foreach($debate->messages()->distinct('user_id')->get() as $message)
                        <a href="{{ route('users.show', $message->user) }}"
                           class="d-flex gap-2 p-3 border-bottom text-decoration-none text-dark align-items-center hover-effect">
                            <img src="{{ $message->user->avatar ? asset('storage/' . $message->user->avatar) : '' }}"
                                 class="rounded-circle" width="32" height="32"
                                 alt="{{ $message->user->name }}"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22><circle cx=%2216%22 cy=%2216%22 r=%2216%22 fill=%22%23e2e8f0%22/></svg>'">
                            <div>
                                <p class="mb-0 small fw-semibold">{{ $message->user->name }}</p>
                                <small class="text-muted">{{ $debate->messages()->where('user_id', $message->user_id)->count() }} mensagens</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    }

    // Initialize scroll
    scrollToBottom();

    // Submit message
    @auth
        @if($debate->isActive())
            document.getElementById('message-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const content = document.getElementById('message-input').value;

                fetch('{{ route("messages.store", $debate) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: content }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('message-input').value = '';
                        // Append new message
                        const messagesContainer = document.getElementById('messages-container');
                        const messageHtml = `
                            <div class="d-flex gap-2 mb-3 align-items-end">
                                <img src="${data.message.user.avatar ? '/storage/' + data.message.user.avatar : 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22><circle cx=%2216%22 cy=%2216%22 r=%2216%22 fill=%22%23e2e8f0%22/></svg>'}"
                                     class="rounded-circle" width="32" height="32"
                                     alt="${data.message.user.name}">
                                <div class="flex-grow-1">
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="mb-1 fw-semibold small">${data.message.user.name}</p>
                                        <p class="mb-0">${data.message.message}</p>
                                        <small class="text-muted d-block mt-1">
                                            Agora
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `;
                        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                        lastMessageId = data.message.id;
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Polling for new messages
            setInterval(() => {
                fetch(`/debates/{{ $debate->id }}/messages?after=${lastMessageId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            const messagesContainer = document.getElementById('messages-container');
                            data.messages.forEach(msg => {
                                const messageHtml = `
                                    <div class="d-flex gap-2 mb-3 align-items-end">
                                        <img src="${msg.user.avatar ? '/storage/' + msg.user.avatar : 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22><circle cx=%2216%22 cy=%2216%22 r=%2216%22 fill=%22%23e2e8f0%22/></svg>'}"
                                             class="rounded-circle" width="32" height="32"
                                             alt="${msg.user.name}">
                                        <div class="flex-grow-1">
                                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                                <p class="mb-1 fw-semibold small">${msg.user.name}</p>
                                                <p class="mb-0">${msg.message}</p>
                                                <small class="text-muted d-block mt-1">
                                                    ${new Date(msg.created_at).toLocaleTimeString()}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                                lastMessageId = msg.id;
                            });
                            scrollToBottom();
                        }
                    })
                    .catch(error => console.error('Polling error:', error));
            }, 5000); // Poll every 5 seconds
        @endif
    @endauth

    // Delete message
    function deleteMessage(messageId) {
        if (confirm('Tem certeza que deseja deletar esta mensagem?')) {
            fetch(`/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endpush
@endsection
