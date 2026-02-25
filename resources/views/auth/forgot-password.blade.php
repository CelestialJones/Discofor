@extends('layouts.app')

@section('title', 'Recuperar Senha - Discofor')
@section('description', 'Recupere sua senha na plataforma Discofor')

@section('content')
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">
                        <i class="bi bi-key" style="color: #6366f1;"></i> Recuperar Senha
                    </h2>

                    <p class="text-muted text-center mb-4">
                        Esqueceu sua senha? Sem problema! Digite seu email e enviaremos um link para redefinir sua senha.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                            <i class="bi bi-envelope"></i> Enviar Link de Recuperação
                        </button>
                    </form>

                    <hr>

                    <p class="text-center text-muted mb-0">
                        Lembrou sua senha?
                        <a href="{{ route('login') }}" class="text-decoration-none">Faça login aqui</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
