@extends('layouts.app')

@section('title', 'Admin - Comentários')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="h3 mb-0">
                    <i class="bi bi-chat me-1"></i> Moderar Comentários
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="surface-card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="form-check m-0">
                <input class="form-check-input" type="checkbox" id="selectAllComments">
                <label class="form-check-label" for="selectAllComments">
                    Selecionar todos da página
                </label>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-light text-dark" id="selectedCount">0 selecionado(s)</span>
                <button type="button" class="btn btn-sm btn-success" id="bulkApproveBtn" disabled>
                    <i class="bi bi-check me-1"></i>Aprovar selecionados
                </button>
                <button type="button" class="btn btn-sm btn-danger" id="bulkRejectBtn" disabled>
                    <i class="bi bi-x me-1"></i>Rejeitar selecionados
                </button>
            </div>
        </div>
    </div>

    <!-- Comments List -->
    <div class="row">
        @forelse($comments as $comment)
            <div class="col-12 mb-3 comment-row" data-comment-id="{{ $comment->id }}">
                <div class="surface-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1 d-flex align-items-start">
                                <input type="checkbox" class="form-check-input mt-2 comment-select" value="{{ $comment->id }}">
                            </div>
                            <div class="col-md-9">
                                <div class="mb-2">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <span class="text-muted">em</span>
                                    <a href="{{ route('articles.show', $comment->article) }}" class="text-decoration-none">
                                        {{ $comment->article->title }}
                                    </a>
                                </div>
                                <p class="mb-2">
                                    {{ $comment->content }}
                                </p>
                                <small class="text-muted">
                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group btn-group-sm d-flex gap-1">
                                    <button type="button" class="btn btn-success approve-comment flex-grow-1"
                                            data-comment-id="{{ $comment->id }}">
                                        <i class="bi bi-check"></i> Aprovar
                                    </button>
                                    <button type="button" class="btn btn-danger reject-comment flex-grow-1"
                                            data-comment-id="{{ $comment->id }}">
                                        <i class="bi bi-x"></i> Rejeitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i> Nenhum comentário aguardando moderação
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($comments->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $comments->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const bulkApproveBtn = document.getElementById('bulkApproveBtn');
const bulkRejectBtn = document.getElementById('bulkRejectBtn');
const selectAllCheckbox = document.getElementById('selectAllComments');
const selectedCountBadge = document.getElementById('selectedCount');
const commentSelectors = Array.from(document.querySelectorAll('.comment-select'));

function setButtonLoading(button, loading) {
    if (!button) return;
    if (loading) {
        button.dataset.originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processando...';
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalHtml || button.innerHTML;
    }
}

function getSelectedCommentIds() {
    return commentSelectors.filter(input => input.checked).map(input => Number(input.value));
}

function refreshBulkState() {
    const selected = getSelectedCommentIds();
    selectedCountBadge.textContent = `${selected.length} selecionado(s)`;
    bulkApproveBtn.disabled = selected.length === 0;
    bulkRejectBtn.disabled = selected.length === 0;
    selectAllCheckbox.checked = commentSelectors.length > 0 && selected.length === commentSelectors.length;
}

async function moderateSingle(commentId, action, button) {
    const endpoint = `/admin/comments/${commentId}/${action}`;
    const confirmConfig = action === 'approve'
        ? { title: 'Aprovar comentário', message: 'Confirma a aprovação deste comentário?', confirmText: 'Aprovar', confirmClass: 'btn-success' }
        : { title: 'Rejeitar comentário', message: 'Este comentário será removido. Deseja continuar?', confirmText: 'Rejeitar', confirmClass: 'btn-danger' };

    const confirmed = await window.DiscoforUI.confirmAction(confirmConfig);
    if (!confirmed) return;

    setButtonLoading(button, true);
    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'Falha na moderação');

        const row = document.querySelector(`.comment-row[data-comment-id="${commentId}"]`);
        if (row) row.remove();
        window.DiscoforUI.showToast(data.message || 'Comentário moderado.', 'success');
        refreshBulkState();

        if (document.querySelectorAll('.comment-row').length === 0) {
            setTimeout(() => location.reload(), 500);
        }
    } catch (error) {
        window.DiscoforUI.showToast(error.message || 'Erro ao moderar comentário', 'error');
        setButtonLoading(button, false);
    }
}

async function moderateBulk(action, button) {
    const ids = getSelectedCommentIds();
    if (ids.length === 0) return;

    const actionLabel = action === 'approve' ? 'aprovar' : 'rejeitar';
    const confirmed = await window.DiscoforUI.confirmAction({
        title: `${action === 'approve' ? 'Aprovar' : 'Rejeitar'} em lote`,
        message: `Deseja ${actionLabel} ${ids.length} comentário(s) selecionado(s)?`,
        confirmText: action === 'approve' ? 'Aprovar em lote' : 'Rejeitar em lote',
        confirmClass: action === 'approve' ? 'btn-success' : 'btn-danger',
    });
    if (!confirmed) return;

    setButtonLoading(button, true);
    try {
        const response = await fetch('/admin/comments/bulk', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action,
                comment_ids: ids,
            }),
        });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'Falha na operação em lote');

        ids.forEach((id) => {
            const row = document.querySelector(`.comment-row[data-comment-id="${id}"]`);
            if (row) row.remove();
        });

        window.DiscoforUI.showToast(data.message || 'Operação em lote concluída.', 'success');
        refreshBulkState();
        setButtonLoading(button, false);

        if (document.querySelectorAll('.comment-row').length === 0) {
            setTimeout(() => location.reload(), 500);
        }
    } catch (error) {
        window.DiscoforUI.showToast(error.message || 'Erro ao processar operação em lote', 'error');
        setButtonLoading(button, false);
    }
}

document.querySelectorAll('.approve-comment').forEach(btn => {
    btn.addEventListener('click', async function() {
        moderateSingle(this.dataset.commentId, 'approve', this);
    });
});

document.querySelectorAll('.reject-comment').forEach(btn => {
    btn.addEventListener('click', async function() {
        moderateSingle(this.dataset.commentId, 'reject', this);
    });
});

selectAllCheckbox.addEventListener('change', function () {
    commentSelectors.forEach((input) => {
        input.checked = this.checked;
    });
    refreshBulkState();
});

commentSelectors.forEach((input) => {
    input.addEventListener('change', refreshBulkState);
});

bulkApproveBtn.addEventListener('click', function () {
    moderateBulk('approve', this);
});

bulkRejectBtn.addEventListener('click', function () {
    moderateBulk('reject', this);
});

refreshBulkState();
</script>
@endsection
