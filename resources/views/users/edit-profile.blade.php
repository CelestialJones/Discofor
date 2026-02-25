@extends('layouts.app')

@section('title', 'Editar Perfil - Discofor')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-lg-8">
            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Senha atualizada com sucesso!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <h1 class="display-6 mb-4">
                <i class="bi bi-gear"></i> Editar Perfil
            </h1>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('users.update-profile') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Avatar -->
                        <div class="mb-4">
                            <label for="avatar" class="form-label">Foto de Perfil</label>
                            <div class="d-flex gap-3 align-items-start">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                         class="rounded-circle" width="100" height="100"
                                         alt="Avatar">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width: 100px; height: 100px;">
                                        <i class="bi bi-person" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                           id="avatar" name="avatar" accept="image/*">
                                    <small class="text-muted">Máximo 2MB. Formatos: JPG, PNG</small>
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email (read-only) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email"
                                   value="{{ auth()->user()->email }}" disabled>
                            <small class="text-muted">Email não pode ser alterado</small>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label">Biografia</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio" name="bio" rows="4" placeholder="Fale um pouco sobre você..."
                                      maxlength="500">{{ old('bio', auth()->user()->bio) }}</textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                            @error('bio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
                            </button>
                            <a href="{{ route('users.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="card border-0 shadow-lg mt-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-key"></i> Alterar Senha
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Atualizar Senha
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
