@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-person"></i> Detalhes do Usuário
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Profile -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                             class="rounded-circle mb-3" width="120" height="120"
                             alt="Avatar de {{ $user->name }}">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person" style="font-size: 3rem; color: #cbd5e1;"></i>
                        </div>
                    @endif

                    <h5 class="card-title mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="mb-3">
                        <span class="badge fs-6 bg-{{ $user->isAdmin() ? 'danger' : 'primary' }}">
                            {{ $user->isAdmin() ? 'Administrador' : 'Usuário' }}
                        </span>
                    </div>

                    @if($user->is_suspended)
                        <div class="alert alert-danger py-2">
                            <i class="bi bi-exclamation-triangle"></i> Usuário Suspenso
                        </div>
                    @else
                        <div class="alert alert-success py-2">
                            <i class="bi bi-check-circle"></i> Conta Ativa
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#roleModal" data-user-id="{{ $user->id }}"
                                data-user-role="{{ $user->role }}">
                            <i class="bi bi-pencil"></i> Alterar Função
                        </button>

                        @if(!$user->is_suspended)
                            <button type="button" class="btn btn-outline-danger btn-sm ban-user" data-user-id="{{ $user->id }}">
                                <i class="bi bi-ban"></i> Suspender Usuário
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-success btn-sm unban-user" data-user-id="{{ $user->id }}">
                                <i class="bi bi-check"></i> Reativar Usuário
                            </button>
                        @endif

                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="bi bi-eye"></i> Ver Perfil Público
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-lg-8">
            <!-- Account Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Informações da Conta</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Data de Registro:</strong><br>
                            {{ $user->created_at->format('d/m/Y \à\s H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Última Atividade:</strong><br>
                            {{ $user->last_activity_at ? $user->last_activity_at->format('d/m/Y \à\s H:i') : 'Nunca' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email Verificado:</strong><br>
                            @if($user->email_verified_at)
                                <span class="text-success"><i class="bi bi-check-circle"></i> Sim</span>
                            @else
                                <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Não</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>ID do Usuário:</strong><br>
                            #{{ $user->id }}
                        </div>
                    </div>

                    @if($user->bio)
                        <div class="mt-3">
                            <strong>Biografia:</strong><br>
                            <p class="mb-0">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Estatísticas</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $user->articles()->count() }}</h4>
                                <small class="text-muted">Artigos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-success mb-1">{{ $user->articles()->where('status', 'published')->count() }}</h4>
                                <small class="text-muted">Publicados</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-warning mb-1">{{ $user->articles()->where('status', 'pending')->count() }}</h4>
                                <small class="text-muted">Pendentes</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info mb-1">{{ $user->likes()->count() }}</h4>
                            <small class="text-muted">Curtidas</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Articles -->
            @if($user->articles()->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Artigos Recentes</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($user->articles()->latest()->limit(5)->get() as $article)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="text-decoration-none">
                                                    {{ $article->title }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                Criado em {{ $article->created_at->format('d/m/Y') }} •
                                                <span class="badge bg-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                                    {{ $article->status === 'published' ? 'Publicado' : 'Pendente' }}
                                                </span>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ $article->views ?? 0 }} visualizações</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity -->
            @if($user->activityLogs()->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Atividade Recente</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($user->activityLogs()->latest()->limit(10)->get() as $activity)
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-circle-fill text-primary me-3" style="font-size: 0.5rem;"></i>
                                        <div>
                                            <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }}</small>
                                            <p class="mb-0">{{ $activity->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Role Change Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar Função do Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="roleForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Função</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role change modal
    const roleModal = document.getElementById('roleModal');
    roleModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userRole = button.getAttribute('data-user-role');

        const form = document.getElementById('roleForm');
        form.action = `/admin/users/${userId}/role`;
        form.querySelector('#role').value = userRole;
    });

    // Ban/Unban users
    document.querySelectorAll('.ban-user, .unban-user').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const action = this.classList.contains('ban-user') ? 'ban' : 'unban';

            if (confirm(`Tem certeza que deseja ${action === 'ban' ? 'suspender' : 'reativar'} este usuário?`)) {
                fetch(`/admin/users/${userId}/${action}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro ao processar a solicitação');
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection