@extends('layouts.app')

@section('title', 'Meus Artigos - Discofor')

@section('content')
<div class="container py-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2">
                        <i class="bi bi-file-earmark-text me-1"></i> Meus Artigos
                    </h1>
                    <p class="mb-0 opacity-75">Gerencie seus artigos, status e desempenho de leitura.</p>
                </div>
                <a href="{{ route('articles.create') }}" class="btn btn-light">
                    <i class="bi bi-pencil-square me-1"></i> Novo Artigo
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            @forelse($articles as $article)
                <div class="surface-card mb-3 article-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">
                                    <a href="{{ route('articles.show', $article->slug) }}"
                                       class="text-decoration-none">
                                        {{ $article->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($article->content, 200) }}
                                </p>
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    @foreach($article->tags as $tag)
                                        <span class="badge bg-light text-dark">{{ $tag->name }}</span>
                                    @endforeach
                                    @if($article->attachment)
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF anexado
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y H:i') }}
                                    •
                                    <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </small>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="mb-2">
                                    <div class="d-flex gap-2 flex-column flex-md-row justify-content-md-end">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-eye"></i> {{ $article->views }} visualizações
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-hand-thumbs-up"></i> {{ $article->likes()->count() }} curtidas
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-chat"></i> {{ $article->comments()->count() }} comentários
                                        </span>
                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    @if($article->status === 'published')
                                        <a href="{{ route('articles.pdf', $article->slug) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-filetype-pdf"></i> PDF
                                        </a>
                                    @endif
                                    <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" style="display:inline;" class="delete-article-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Deletar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-info-circle"></i> Você ainda não publicou nenhum artigo.
                    <a href="{{ route('articles.create') }}" class="alert-link">Criar artigo agora</a>
                </div>
            @endforelse

            <!-- Pagination -->
            {{ $articles->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.delete-article-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const confirmed = await window.DiscoforUI.confirmAction({
                title: 'Excluir artigo',
                message: 'Tem certeza que deseja deletar este artigo?',
                confirmText: 'Excluir',
                confirmClass: 'btn-danger',
            });
            if (confirmed) form.submit();
        });
    });
</script>
@endpush
@endsection
