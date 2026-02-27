@extends('layouts.app')

@section('title', 'Criar Artigo - Discofor')
@section('description', 'Publique seu artigo na plataforma Discofor')

@section('content')
<div class="container py-2">
    <div class="row justify-content-center py-4">
        <div class="col-lg-9">
            <div class="page-header mb-4">
                <h1 class="display-6 fw-bold mb-2">
                    <i class="bi bi-pencil-square me-1"></i> Criar Novo Artigo
                </h1>
                <p class="mb-0 opacity-75">Preencha os campos abaixo para enviar seu artigo para revisão.</p>
            </div>

            <div class="surface-card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required autofocus
                                   placeholder="Digite um título atraente para seu artigo">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label">Imagem em Destaque</label>
                            <div id="image-preview" class="mb-3"></div>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            <small class="text-muted">
                                Máximo 2MB. Formatos recomendados: JPG, PNG (mínimo 800x600px)
                            </small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label">Conteúdo</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="12" required
                                      placeholder="Digite o conteúdo do seu artigo aqui..."
                                      minlength="100" maxlength="50000">{{ old('content') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                Mínimo 100 caracteres | Máximo 50.000 caracteres
                                <span id="char-count">0</span> / 50.000
                            </small>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <label for="tags" class="form-label">Tags (Categorias)</label>
                            <div id="tags-container" class="mb-2">
                                @foreach($tags as $tag)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="tag-{{ $tag->id }}"
                                               name="tags[]" value="{{ $tag->id }}"
                                               @if(in_array($tag->id, old('tags', []))) checked @endif>
                                        <label class="form-check-label" for="tag-{{ $tag->id }}">
                                            {{ $tag->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">
                                Selecione uma ou mais categorias para seu artigo
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Publicar Artigo
                            </button>
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancelar
                            </a>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info mt-4" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <strong>Aviso:</strong> Seu artigo será enviado para aprovação antes de ser publicado.
                            Um administrador revisará o conteúdo e tomará uma decisão em breve.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Character counter
    const contentArea = document.getElementById('content');
    const charCount = document.getElementById('char-count');

    contentArea.addEventListener('input', () => {
        charCount.textContent = contentArea.value.length;
    });

    // Initialize counter
    charCount.textContent = contentArea.value.length;

    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');

    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview.innerHTML = `
                    <div class="card">
                        <img src="${event.target.result}" class="card-img-top" style="max-height: 300px; object-fit: cover;">
                        <div class="card-body">
                            <small class="text-muted">${file.name}</small>
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
