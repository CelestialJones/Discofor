@extends('layouts.app')

@section('title', 'Notificações - Discofor')

@push('styles')
<style>
    .notification-card {
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .notification-card.unread {
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 16px 30px rgba(11, 18, 32, 0.1);
    }

    .notification-card.read {
        opacity: 0.92;
    }

    .notification-card .notification-title {
        color: inherit;
    }

    html[data-theme="dark"] .notification-card.unread {
        background: rgba(24, 38, 58, 0.95) !important;
        border-left-color: #60a5fa;
    }

    html[data-theme="dark"] .notification-card.read {
        background: rgba(18, 30, 48, 0.85) !important;
        border-left: 4px solid #2f425f;
    }

    html[data-theme="dark"] .notification-card .text-muted {
        color: #9fb4d1 !important;
    }

    html[data-theme="dark"] .notification-card .btn-outline-secondary {
        border-color: #3f587a;
        color: #c9dbf3;
    }

    html[data-theme="dark"] .notification-card .btn-outline-secondary:hover {
        background: #2a3f5e;
    }
</style>
@endpush

@section('content')
<div class="container py-3">
    <div class="row">
        <div class="col-lg-8">
            <div class="page-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h1 class="display-6 mb-0 fw-bold">
                    <i class="bi bi-bell me-1"></i> Notificações
                </h1>
                @if(auth()->user()->unreadNotificationsCount() > 0)
                    <button class="btn btn-light" onclick="markAllAsRead()">
                        <i class="bi bi-check-all me-1"></i> Marcar todas como lidas
                    </button>
                @endif
            </div>

            @forelse($notifications as $notification)
                <div class="surface-card notification-card mb-3 {{ $notification->is_read ? 'read' : 'unread' }}"
                     id="notification-{{ $notification->id }}">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <!-- Notification Icon -->
                                <div class="mb-2">
                                    @if($notification->type === 'new_comment')
                                        <span class="badge bg-info">
                                            <i class="bi bi-chat-left-text"></i> Comentário
                                        </span>
                                    @elseif($notification->type === 'new_like')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-hand-thumbs-up"></i> Curtida
                                        </span>
                                    @elseif($notification->type === 'new_debate')
                                        <span class="badge bg-success">
                                            <i class="bi bi-chat-dots"></i> Debate
                                        </span>
                                    @elseif($notification->type === 'new_article')
                                        <span class="badge bg-primary">
                                            <i class="bi bi-file-text"></i> Artigo
                                        </span>
                                    @elseif($notification->type === 'article_approved')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Aprovado
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-info-circle"></i> Sistema
                                        </span>
                                    @endif
                                </div>

                                <h6 class="mb-1 notification-title">{{ $notification->title }}</h6>
                                <p class="mb-2 text-muted">{{ $notification->content }}</p>

                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>

                                <!-- Action Link -->
                                @if($notification->article_id && $notification->article)
                                    <div class="mt-2">
                                        <a href="{{ route('articles.show', $notification->article->slug) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           onclick="markAsRead(event, {{ $notification->id }})">
                                            <i class="bi bi-arrow-right"></i> Ver Artigo
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="btn-group btn-group-sm">
                                @if(!$notification->is_read)
                                    <button class="btn btn-outline-secondary" onclick="markAsRead(event, {{ $notification->id }})">
                                        <i class="bi bi-check"></i> Ler
                                    </button>
                                @endif
                                <button class="btn btn-outline-danger" onclick="deleteNotification({{ $notification->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                    <p class="mt-3 mb-0">Você está em dia com todas as notificações!</p>
                </div>
            @endforelse

            <!-- Pagination -->
            {{ $notifications->links('vendor.pagination.bootstrap-5') }}
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Stats Card -->
            <div class="surface-card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Resumo</h6>
                    <div class="mb-2">
                        <p class="mb-1 small text-muted">Notificações não lidas</p>
                        <h4 id="unread-count">{{ auth()->user()->unreadNotificationsCount() }}</h4>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1 small text-muted">Total de notificações</p>
                        <h4>{{ auth()->user()->notifications()->count() }}</h4>
                    </div>
                    @if(auth()->user()->notifications()->count() > 0)
                        <form action="{{ route('notifications.clear-all') }}" method="POST" id="clear-all-form">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash"></i> Limpar Todas
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Notification Types Card -->
            <div class="surface-card">
                <div class="card-body">
                    <h6 class="mb-3">Tipos de Notificação</h6>
                    <div class="small">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-info"><i class="bi bi-chat-left-text"></i></span>
                            <span>Novo comentário em seus artigos</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-danger"><i class="bi bi-hand-thumbs-up"></i></span>
                            <span>Quando seus artigos recebem curtidas</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-success"><i class="bi bi-chat-dots"></i></span>
                            <span>Novos debates em artigos</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i></span>
                            <span>Quando seus artigos são aprovados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('clear-all-form')?.addEventListener('submit', async function (event) {
        event.preventDefault();
        const confirmed = await window.DiscoforUI.confirmAction({
            title: 'Limpar notificações',
            message: 'Tem certeza que deseja limpar todas as notificações?',
            confirmText: 'Limpar tudo',
            confirmClass: 'btn-danger',
        });
        if (confirmed) this.submit();
    });

    function markAsRead(event, notificationId) {
        event.preventDefault();

        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(() => {
            const notif = document.getElementById(`notification-${notificationId}`);
            notif.classList.remove('unread');
            notif.classList.add('read');

            // Update unread count
            const count = parseInt(document.getElementById('unread-count').textContent);
            if (count > 0) {
                document.getElementById('unread-count').textContent = count - 1;
            }

            window.DiscoforUI.showToast('Notificação marcada como lida.', 'success');

            // If there's an action link, follow it
            if (event.target.closest('.btn-outline-primary')) {
                window.location.href = event.target.closest('.btn-outline-primary').href;
            }
        })
        .catch(() => window.DiscoforUI.showToast('Erro ao marcar notificação.', 'error'));
    }

    async function deleteNotification(notificationId) {
        const confirmed = await window.DiscoforUI.confirmAction({
            title: 'Remover notificação',
            message: 'Tem certeza que deseja remover esta notificação?',
            confirmText: 'Remover',
            confirmClass: 'btn-danger',
        });
        if (!confirmed) return;

        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(() => {
            document.getElementById(`notification-${notificationId}`)?.remove();
            window.DiscoforUI.showToast('Notificação removida.', 'success');
            setTimeout(() => location.reload(), 400);
        })
        .catch(() => window.DiscoforUI.showToast('Erro ao remover notificação.', 'error'));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(() => {
            window.DiscoforUI.showToast('Todas as notificações foram marcadas como lidas.', 'success');
            setTimeout(() => location.reload(), 400);
        })
        .catch(() => window.DiscoforUI.showToast('Erro ao atualizar notificações.', 'error'));
    }
</script>
@endpush
@endsection
