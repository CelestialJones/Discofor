@extends('layouts.app')

@section('title', 'Entrar - Discofor')
@section('description', 'Acesse sua conta na plataforma Discofor')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="page-header h-100 d-flex flex-column justify-content-center">
                        <span class="badge bg-light text-primary mb-3 align-self-start">Acesso seguro</span>
                        <h1 class="h2 fw-bold mb-3">Entre na sua conta</h1>
                        <p class="mb-0 opacity-75">Acompanhe artigos, debates e notificações da comunidade acadêmica em um único lugar.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="surface-card p-4 p-md-5">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erro!</strong>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
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
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">Manter-me conectado</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                            </button>
                        </form>

                        <div class="d-flex justify-content-between flex-wrap gap-2 small">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                <i class="bi bi-key me-1"></i> Esqueceu a senha?
                            </a>
                            <a href="{{ route('register') }}" class="text-decoration-none">
                                <i class="bi bi-pencil-square me-1"></i> Criar conta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
