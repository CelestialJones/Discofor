@extends('layouts.app')

@section('title', 'Admin - Artigos')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="h3 mb-0">
                    <i class="bi bi-file-text me-1"></i> Gerenciar Artigos
                </h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="surface-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por título"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="published" @if(request('status') === 'published') selected @endif>Publicado</option>
                        <option value="pending" @if(request('status') === 'pending') selected @endif>Pendente</option>
                        <option value="removed" @if(request('status') === 'removed') selected @endif>Removido</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 h-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Articles Table -->
    <div class="surface-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($article->title, 50) }}</strong>
                            </td>
                            <td>{{ $article->user?->name ?? 'Usuário deletado' }}</td>
                            <td>
                                <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td>{{ $article->views_count ?? 0 }}</td>
                            <td>{{ $article->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    @if($article->status === 'pending')
                                        <button type="button" class="btn btn-outline-success approve-article"
                                                data-article-id="{{ $article->id }}">
                                            <i class="bi bi-check"></i> Aprovar
                                        </button>
                                        <button type="button" class="btn btn-outline-danger reject-article"
                                                data-article-id="{{ $article->id }}">
                                            <i class="bi bi-x"></i> Rejeitar
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-danger delete-article"
                                            data-article-id="{{ $article->id }}">
                                        <i class="bi bi-trash"></i> Deletar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Nenhum artigo encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $articles->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

async function runArticleAction(url, button, method, successFallback, errorFallback) {
    setButtonLoading(button, true);
    try {
        const response = await fetch(url, {
            method,
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || errorFallback);
        window.DiscoforUI.showToast(data.message || successFallback, 'success');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        window.DiscoforUI.showToast(error.message || errorFallback, 'error');
        setButtonLoading(button, false);
    }
}

document.querySelectorAll('.approve-article').forEach(btn => {
    btn.addEventListener('click', async function() {
        const confirmed = await window.DiscoforUI.confirmAction({
            title: 'Aprovar artigo',
            message: 'Confirma a aprovação deste artigo?',
            confirmText: 'Aprovar',
            confirmClass: 'btn-success',
        });
        if (!confirmed) return;
        runArticleAction(`/admin/articles/${this.dataset.articleId}/approve`, this, 'POST', 'Artigo aprovado!', 'Erro ao aprovar artigo');
    });
});

document.querySelectorAll('.reject-article').forEach(btn => {
    btn.addEventListener('click', async function() {
        const confirmed = await window.DiscoforUI.confirmAction({
            title: 'Rejeitar artigo',
            message: 'Confirma a rejeição deste artigo?',
            confirmText: 'Rejeitar',
            confirmClass: 'btn-danger',
        });
        if (!confirmed) return;
        runArticleAction(`/admin/articles/${this.dataset.articleId}/reject`, this, 'POST', 'Artigo rejeitado!', 'Erro ao rejeitar artigo');
    });
});

document.querySelectorAll('.delete-article').forEach(btn => {
    btn.addEventListener('click', async function() {
        const confirmed = await window.DiscoforUI.confirmAction({
            title: 'Excluir artigo',
            message: 'Esta ação remove o artigo permanentemente. Deseja continuar?',
            confirmText: 'Excluir',
            confirmClass: 'btn-danger',
        });
        if (!confirmed) return;
        runArticleAction(`/admin/articles/${this.dataset.articleId}`, this, 'DELETE', 'Artigo deletado!', 'Erro ao deletar artigo');
    });
});
</script>
@endsection
