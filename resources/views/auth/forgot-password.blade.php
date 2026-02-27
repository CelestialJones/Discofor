@extends('layouts.app')

@section('title', 'Recuperar Senha - Discofor')
@section('description', 'Recupere sua senha na plataforma Discofor')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="page-header h-100 d-flex flex-column justify-content-center">
                        <span class="badge bg-light text-primary mb-3 align-self-start">Recuperação</span>
                        <h1 class="h2 fw-bold mb-3">Recuperar senha</h1>
                        <p class="mb-0 opacity-75">Informe seu e-mail para receber o link de redefinição de acesso.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="surface-card p-4 p-md-5">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="bi bi-envelope me-1"></i> Enviar link de recuperação
                            </button>
                        </form>

                        <p class="text-center text-muted mb-0">
                            Lembrou sua senha?
                            <a href="{{ route('login') }}" class="text-decoration-none">Fazer login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
