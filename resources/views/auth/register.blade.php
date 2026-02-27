@extends('layouts.app')

@section('title', 'Registrar - Discofor')
@section('description', 'Crie sua conta na plataforma Discofor')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="page-header h-100 d-flex flex-column justify-content-center">
                        <span class="badge bg-light text-primary mb-3 align-self-start">Novo membro</span>
                        <h1 class="h2 fw-bold mb-3">Crie sua conta</h1>
                        <p class="mb-0 opacity-75">Publique artigos, participe dos debates e construa sua presença acadêmica dentro da plataforma.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="surface-card p-4 p-md-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nome completo</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
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

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="bi bi-person-plus me-1"></i> Criar conta
                            </button>
                        </form>

                        <p class="text-center text-muted mb-0">
                            Já tem conta?
                            <a href="{{ route('login') }}" class="text-decoration-none">Fazer login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
