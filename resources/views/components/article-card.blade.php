<!-- Article Card Component -->
<div class="card article-card h-100 border-0 shadow-sm">
    @if($article->image_url)
        <div style="height: 200px; overflow: hidden; border-radius: 0.75rem 0.75rem 0 0;">
            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-100 h-100 object-fit-cover">
        </div>
    @endif
    
    <div class="card-body d-flex flex-column">
        <div class="d-flex gap-2 mb-3 flex-wrap">
            @if($article->status === 'published')
                <span class="badge bg-success">
                    <i class="bi bi-check-circle"></i> Publicado
                </span>
            @elseif($article->status === 'pending')
                <span class="badge bg-warning">
                    <i class="bi bi-hourglass"></i> Pendente
                </span>
            @endif
            @if($article->attachment)
                <span class="badge bg-danger-subtle text-danger">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </span>
            @endif
            @foreach($article->tags->take(3) as $tag)
                <a href="{{ route('articles.index', ['tag' => $tag->id]) }}" class="badge bg-light text-primary text-decoration-none">
                    {{ $tag->name }}
                </a>
            @endforeach
        </div>

        <h5 class="card-title mb-2">
            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark">
                {{ Str::limit($article->title, 60) }}
            </a>
        </h5>

        <p class="card-text text-muted small flex-grow-1">
            {{ Str::limit($article->excerpt ?? $article->content, 100) }}
        </p>

        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
            <div class="small">
                <div class="text-muted mb-1">
                    <i class="bi bi-person"></i> 
                    <a href="{{ route('users.show', $article->user) }}" class="text-decoration-none">
                        {{ $article->user->name }}
                    </a>
                </div>
                <div class="text-muted">
                    <i class="bi bi-calendar"></i> {{ $article->created_at->format('d/m/Y') }}
                </div>
            </div>
            <div class="text-center">
                <div class="small">
                    <strong>{{ $article->likes_count }}</strong>
                    <div class="text-muted">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
