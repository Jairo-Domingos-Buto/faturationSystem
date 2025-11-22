@extends('layout.main')

@section('content')

<style>
.profile-header {
    background: linear-gradient(135deg, #0d6efd, #3b82f6);
    padding: 50px 20px;
    border-radius: 12px;
    color: white;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.profile-photo-big {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.20);
}

.info-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
}

.info-title {
    font-weight: 600;
    color: #555;
}

h2 {
    color: white;
    text-transform: uppercase;
}
</style>

<div class="container mt-4">

    <!-- ================= HEADER CORPORATIVO ================= -->
    <div class="profile-header">

        {{-- FOTO DO PERFIL (se existe; senão ícone padrão) --}}
        @if($user->foto)
        <img src="{{ asset('storage/'.$user->foto) }}" class="profile-photo-big">
        @else
        <div class="bg-light text-primary rounded-circle d-flex justify-content-center align-items-center mx-auto profile-photo-big"
            style="font-size: 65px;">
            <i class="bx bx-user"></i>
        </div>
        @endif

        <h2 class="fw-bold mb-0">{{ $user->name }}</h2>
        <p class="text-light mb-2" style="opacity: 0.9;">
            {{ $user->funcao ?? 'Usuário da Plataforma' }}
        </p>

        <button class="btn btn-light btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#editarPerfilModal">
            <i class="bx bx-edit me-1"></i> Editar Perfil
        </button>
    </div>

    <!-- ================= INFORMAÇÕES ================= -->
    <div class="row g-4 mt-3">

        <!-- CARD INFO PESSOAL -->
        <div class="col-md-6">
            <div class="card info-card p-3">
                <h5 class="info-title mb-3">Informações Pessoais</h5>

                <p><i class="bx bx-envelope me-2 text-primary"></i>
                    <strong>Email: </strong> {{ $user->email }}
                </p>

                <p><i class="bx bx-phone me-2 text-primary"></i>
                    <strong>Telefone: </strong> {{ $profile->telefone ?? '-' }}
                </p>

                <p><i class="bx bx-id-card me-2 text-primary"></i>
                    <strong>Bilhete de Identidade: </strong> {{ $profile->bi ?? '-' }}
                </p>

                <p><i class="bx bx-calendar me-2 text-primary"></i>
                    <strong>Data de Nascimento:</strong>
                    {{ $profile?->data_nascimento ? \Carbon\Carbon::parse($profile->data_nascimento)->format('d/m/Y') : '-' }}
                </p>

                <p><i class="bx bx-user-pin me-2 text-primary"></i>
                    <strong>Gênero:</strong> {{ $profile->genero ?? '-' }}
                </p>

                <p><i class="bx bx-home me-2 text-primary"></i>
                    <strong>Endereço:</strong> {{ $profile->endereco ?? '-' }}
                </p>
            </div>
        </div>

        <!-- CARD DESCRIÇÃO PROFISSIONAL -->
        <div class="col-md-6">
            <div class="card info-card p-3">
                <h5 class="info-title mb-3">Descrição</h5>

                <p class="text-muted">
                    {{ $profile->descricao ?? 'Nenhuma descrição cadastrada.' }}
                </p>
            </div>
        </div>

    </div>

    <!-- ========== MODAL ATUALIZAR PERFIL ========= -->
    <div class="modal fade" id="editarPerfilModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form action="{{ route('Admin.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Atualizar Perfil</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        {{-- FOTO --}}
                        <div class="text-center mb-3">
                            @if($profile && $profile->foto)
                            <img src="{{ asset('storage/'.$profile->foto) }}" class="rounded-circle mb-2" width="100"
                                height="100">
                            @else
                            <div class="bg-light text-primary rounded-circle d-flex justify-content-center align-items-center mx-auto mb-2"
                                style="width:100px; height:100px; font-size:45px;">
                                <i class="bx bx-user"></i>
                            </div>
                            @endif
                            <label class="form-label fw-bold">Foto de Perfil</label>
                            <input type="file" name="foto" class="form-control">
                            <small class="text-muted">Formatos: jpg, png, webp</small>
                        </div>

                        {{-- TELEFONE --}}
                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control"
                                value="{{ $profile->telefone ?? '' }}">
                        </div>

                        {{-- BI --}}
                        <div class="mb-3">
                            <label class="form-label">Bilhete de Identidade</label>
                            <input type="text" name="bi" class="form-control" value="{{ $profile->bi ?? '' }}">
                        </div>

                        {{-- DATA DE NASCIMENTO --}}
                        <div class="mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" class="form-control"
                                value="{{ $profile->data_nascimento ?? '' }}">
                        </div>

                        {{-- GÊNERO --}}
                        <div class="mb-3">
                            <label class="form-label">Gênero</label>
                            <select name="genero" class="form-control">
                                <option value="">Selecione</option>
                                <option value="Masculino" @if(($profile->genero ?? '')=='Masculino') selected
                                    @endif>Masculino</option>
                                <option value="Feminino" @if(($profile->genero ?? '')=='Feminino') selected
                                    @endif>Feminino
                                </option>
                                <option value="Outro" @if(($profile->genero ?? '')=='Outro') selected @endif>Outro
                                </option>
                            </select>
                        </div>

                        {{-- ENDEREÇO --}}
                        <div class="mb-3">
                            <label class="form-label">Endereço</label>
                            <input type="text" name="endereco" class="form-control"
                                value="{{ $profile->endereco ?? '' }}">
                        </div>

                        {{-- DESCRIÇÃO --}}
                        <div class="mb-3">
                            <label class="form-label">Descrição Profissional</label>
                            <textarea class="form-control" name="descricao"
                                rows="3">{{ $profile->descricao ?? '' }}</textarea>
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

    @endsection