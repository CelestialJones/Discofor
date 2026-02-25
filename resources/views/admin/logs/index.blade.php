@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-clock-history"></i> Logs de Atividade
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
                    <select name="action" class="form-select">
                        <option value="">Todas as ações</option>
                        <option value="user_created" @if(request('action') === 'user_created') selected @endif>Usuário Criado</option>
                        <option value="article_created" @if(request('action') === 'article_created') selected @endif>Artigo Criado</option>
                        <option value="article_approved" @if(request('action') === 'article_approved') selected @endif>Artigo Aprovado</option>
                        <option value="article_rejected" @if(request('action') === 'article_rejected') selected @endif>Artigo Rejeitado</option>
                        <option value="user_banned" @if(request('action') === 'user_banned') selected @endif>Usuário Banido</option>
                        <option value="user_deleted" @if(request('action') === 'user_deleted') selected @endif>Usuário Deletado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="user" class="form-select">
                        <option value="">Todos os usuários</option>
                        @foreach(\App\Models\User::where('role', 'admin')->get() as $admin)
                            <option value="{{ $admin->id }}" @if(request('user') == $admin->id) selected @endif>
                                {{ $admin->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Data/Hora</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                            </td>
                            <td>
                                <strong>{{ $log->user->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $log->action }}</span>
                            </td>
                            <td>
                                <small>{{ $log->description }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Nenhum log registrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
