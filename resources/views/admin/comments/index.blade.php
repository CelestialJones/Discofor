@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-chat"></i> Moderar Comentários
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Comments List -->
    <div class="row">
        @forelse($comments as $comment)
            <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
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
                            <div class="col-md-3 text-end">
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
                <div class="alert alert-info text-center py-4">
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
const csrfToken = document.querySelector('[name="_token"]').value;

document.querySelectorAll('.approve-comment').forEach(btn => {
    btn.addEventListener('click', async function() {
        try {
            const response = await fetch(`/admin/comments/${this.dataset.commentId}/approve`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if(data.success) {
                this.closest('.col-12').remove();
                if(document.querySelectorAll('.col-12.mb-3').length === 0) {
                    location.reload();
                }
            }
        } catch(e) {
            alert('Erro ao aprovar comentário');
        }
    });
});

document.querySelectorAll('.reject-comment').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza que quer rejeitar este comentário?')) return;

        try {
            const response = await fetch(`/admin/comments/${this.dataset.commentId}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if(data.success) {
                this.closest('.col-12').remove();
                if(document.querySelectorAll('.col-12.mb-3').length === 0) {
                    location.reload();
                }
            }
        } catch(e) {
            alert('Erro ao rejeitar comentário');
        }
    });
});
</script>
@endsection
