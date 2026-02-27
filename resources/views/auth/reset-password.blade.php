@extends('layouts.app')

@section('title', 'Redefinir Senha - Discofor')
@section('description', 'Redefina sua senha na plataforma Discofor')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="page-header h-100 d-flex flex-column justify-content-center">
                        <span class="badge bg-light text-primary mb-3 align-self-start">Segurança</span>
                        <h1 class="h2 fw-bold mb-3">Redefinir senha</h1>
                        <p class="mb-0 opacity-75">Defina uma nova senha forte para continuar usando sua conta.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="surface-card p-4 p-md-5">
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nova senha</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mínimo de 8 caracteres com letras e números.</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-check-circle me-1"></i> Redefinir senha
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
