@extends('layout.main')

@section('content')

<div class="container mt-4">

    <div class="row justify-content-center">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0" style="color: white;">Cadastro de Usuário</h5>
                </div>

                <div class="card-body">



                    <form action="{{ route('admin.cadastrar.store') }}" method="POST">
                        @csrf

                        <!-- Nome -->
                        <div class="mb-3 mt-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Digite o nome do usuário" required>
                            @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="usuario@exemplo.com" required>
                            @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Crie uma senha segura" required>
                            @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tipo de Usuário -->
                        <div class="mt-3">
                            <label class="form-label">Tipo de Usuário</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeUser" value="admin" required>
                                <label class="form-check-label">Administrador</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeUser" value="atendente">
                                <label class="form-check-label">Atendente</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeUser" value="balconista">
                                <label class="form-check-label">Balconista</label>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Cadastrar Usuário
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection