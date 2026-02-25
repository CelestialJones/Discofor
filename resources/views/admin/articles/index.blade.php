@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-file-text"></i> Gerenciar Artigos
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
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
                    <button type="submit" class="btn btn-primary w-100">
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
    <div class="card border-0 shadow-sm">
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

document.querySelectorAll('.approve-article').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza que quer aprovar este artigo?')) return;

        try {
            const response = await fetch(`/admin/articles/${this.dataset.articleId}/approve`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if(data.success) {
                location.reload();
            }
        } catch(e) {
            alert('Erro ao aprovar artigo');
        }
    });
});

document.querySelectorAll('.reject-article').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza que quer rejeitar este artigo?')) return;

        try {
            const response = await fetch(`/admin/articles/${this.dataset.articleId}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if(data.success) {
                location.reload();
            }
        } catch(e) {
            alert('Erro ao rejeitar artigo');
        }
    });
});

document.querySelectorAll('.delete-article').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza que quer deletar este artigo?')) return;

        try {
            const response = await fetch(`/admin/articles/${this.dataset.articleId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await response.json();
            if(data.success) {
                location.reload();
            }
        } catch(e) {
            alert('Erro ao deletar artigo');
        }
    });
});
</script>
@endsection
