@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-people"></i> Gerenciar Usuários
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
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou email"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Todas as funções</option>
                        <option value="user" @if(request('role') === 'user') selected @endif>Usuário</option>
                        <option value="admin" @if(request('role') === 'admin') selected @endif>Administrador</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Função</th>
                        <th>Status</th>
                        <th>Data de Registro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'primary' }}">
                                    {{ $user->isAdmin() ? 'Admin' : 'Usuário' }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge bg-danger">Banido</span>
                                else
                                    <span class="badge bg-success">Ativo</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#roleModal" data-user-id="{{ $user->id }}"
                                            data-user-role="{{ $user->role }}">
                                        <i class="bi bi-pencil"></i> Função
                                    </button>
                                    @if(!$user->is_banned)
                                        <button type="button" class="btn btn-outline-danger ban-user" data-user-id="{{ $user->id }}">
                                            <i class="bi bi-ban"></i> Banir
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-success unban-user" data-user-id="{{ $user->id }}">
                                            <i class="bi bi-check"></i> Desbanir
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Nenhum usuário encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Alterar Função</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="roleForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role" class="form-label">Função</label>
                        <select name="role" id="role" class="form-select">
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.ban-user').forEach(btn => {
    btn.addEventListener('click', async function() {
        if(!confirm('Tem certeza que quer banir este usuário?')) return;

        try {
            const response = await fetch(`/admin/users/${this.dataset.userId}/ban`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value }
            });
            const data = await response.json();
            if(data.success) {
                location.reload();
            }
        } catch(e) {
            alert('Erro ao banir usuário');
        }
    });
});

document.querySelectorAll('.unban-user').forEach(btn => {
    btn.addEventListener('click', async function() {
        try {
            const response = await fetch(`/admin/users/${this.dataset.userId}/unban`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value }
            });
            const data = await response.json();
            if(data.success) {
                location.reload();
            }
        } catch(e) {
            alert('Erro ao desbanir usuário');
        }
    });
});

const roleModal = document.getElementById('roleModal');
roleModal.addEventListener('show.bs.modal', function(e) {
    const userId = e.relatedTarget.dataset.userId;
    const userRole = e.relatedTarget.dataset.userRole;
    document.getElementById('role').value = userRole;
    document.getElementById('roleForm').action = `/admin/users/${userId}/role`;
});
</script>
@endsection
