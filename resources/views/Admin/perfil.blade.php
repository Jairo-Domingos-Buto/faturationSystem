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
    height: 80px;
    border-radius: 50%;
    border: 4px solid white;
    object-fit: cover;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.20);
}

img {
    width: 100%;
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
        <img src=" {{ asset($profile->foto ?? 'assets/img/avatars/default.png') }}" alt="Foto do perfil"
            class="profile-photo-big rounded-circle mb-3 shadow d-flex flex-column align-items-center justify-content-center">
        <h2 class=" fw-bold mb-0">{{ $user->name }}</h2>
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
                    <strong>Telefone: </strong> {{ $profile->telefone}}
                </p>

                <p><i class="bx bx-id-card me-2 text-primary"></i>
                    <strong>Bilhete de Identidade: </strong> {{ $profile->bi}}
                </p>

                <p><i class="bx bx-calendar me-2 text-primary"></i>
                    <strong>Data de Nascimento:</strong>
                    {{ $profile?->data_nascimento ? \Carbon\Carbon::parse($profile->data_nascimento)->format('d/m/Y') : '-' }}
                </p>

                <p><i class="bx bx-user-pin me-2 text-primary"></i>
                    <strong>Gênero:</strong> {{ $profile->genero}}
                </p>

                <p><i class="bx bx-home me-2 text-primary"></i>
                    <strong>Endereço:</strong> {{ $profile->endereco}}
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
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <form action="{{ route('Admin.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- HEADER --}}
                    <div class="modal-header bg-primary text-white py-3">
                        <h4 class="modal-title" style="color: white;">
                            <i class="bx bx-user-circle me-2"></i> Atualizar Perfil
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body py-4">

                        <div class="row">
                            {{-- FOTO --}}
                            <div class="col-md-4 text-center border-end">
                                @if($profile && $profile->foto)
                                <img src="{{ asset($profile->foto ?? 'assets/img/avatars/default.png') }}"
                                    alt="Foto do perfil" class="profile-photo-big
                                    rounded-circle mb-3 shadow" width="100" height="100">

                                @else
                                <div class="bg-light text-primary rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3 shadow"
                                    style="width:100px; height:100px; font-size:65px;">
                                    <i class="bx bx-user"></i>
                                </div>
                                @endif

                                <label class="form-label fw-bold">Foto de Perfil</label>
                                <input type="file" name="foto" class="form-control">
                                <small class="text-muted d-block mt-1">Formatos: JPG, PNG, WEBP</small>

                                <hr>

                                <p class="text-muted small">
                                    Mantenha seus dados atualizados para melhor identificação no sistema.
                                </p>
                            </div>

                            {{-- CAMPOS --}}
                            <div class="col-md-8">

                                <div class="row">
                                    {{-- TELEFONE --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Telefone</label>
                                        <input type="text" name="telefone" class="form-control"
                                            value="{{ $profile->telefone ?? '' }}">
                                    </div>

                                    {{-- BI --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Bilhete de Identidade</label>
                                        <input type="text" name="bi" class="form-control"
                                            value="{{ $profile->bi ?? '' }}">
                                    </div>

                                    {{-- DATA DE NASCIMENTO --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Data de Nascimento</label>
                                        <input type="date" name="data_nascimento" class="form-control"
                                            value="{{ $profile->data_nascimento ?? '' }}">
                                    </div>

                                    {{-- GÊNERO --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Gênero</label>
                                        <select name="genero" class="form-select">
                                            <option value="">Selecione</option>
                                            <option value="Masculino" @selected(($profile->genero ??
                                                '')=='Masculino')>Masculino</option>
                                            <option value="Feminino" @selected(($profile->genero ??
                                                '')=='Feminino')>Feminino</option>
                                            <option value="Outro" @selected(($profile->genero ?? '')=='Outro')>Outro
                                            </option>
                                        </select>
                                    </div>

                                    {{-- ENDEREÇO --}}
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Endereço</label>
                                        <input type="text" name="endereco" class="form-control"
                                            value="{{ $profile->endereco ?? '' }}">
                                    </div>

                                    {{-- DESCRIÇÃO --}}
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Descrição Profissional</label>
                                        <textarea class="form-control" name="descricao" rows="4"
                                            placeholder="Ex: Técnico de Informática, Programador, Professor...">
                                        {{ $profile->descricao ?? '' }}
                                    </textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x-circle me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bx bx-save me-1"></i> Salvar Alterações
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @endsection