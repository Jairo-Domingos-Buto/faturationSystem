@extends('layout.main')

@section('content')

<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoClienteModal"
                style="margin-left: 20px; background-color: #6C6FFF; color:white;">
                Novo
            </button>
            <button type="button" class="btn btn" style="margin-right: 20px; background-color: #6C6FFF; color:white;">
                Pesquisar
            </button>
        </div>
    </div>

    <!-- Tabela de Fornecedores -->
    <div class="card">
        <table class="table table-hover">
            <thead class='p-1'>
                <tr>
                    <th>NOME </th>
                    <th>NIF</th>
                    <th>PROVÍNCIA</th>
                    <th>CIDADE</th>
                    <th>TELEFONE</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td colspan="8" class="text-center text-muted">A carregar clientes...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Novo Cliente -->
    <div class="modal fade" id="novoClienteModal" tabindex="-1" aria-labelledby="novoClienteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <form id="cliente-form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #4e73df;">
                        <h5 class="modal-title text-white fw-bold" id="novoClienteModalLabel">
                            <i class="bx bx-user-plus me-2"></i> Novo Cliente
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="form-group mb-3">
                            <label for="nome" class="form-label fw-semibold">Nome do Cliente</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-user"></i></span>
                                <input type="text" class="form-control" id="nome" name="nome"
                                    placeholder="Ex: João Manuel">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nif" class="form-label fw-semibold">NIF</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-id-card"></i></span>
                                <input type="text" class="form-control" id="nif" name="nif"
                                    placeholder="Ex: 005678910LA043">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="provincia" class="form-label fw-semibold">Província</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-map"></i></span>
                                <input type="text" class="form-control" id="provincia" name="provincia"
                                    placeholder="Ex: Benguela">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="cidade" class="form-label fw-semibold">Cidade</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-buildings"></i></span>
                                <input type="text" class="form-control" id="cidade" name="cidade"
                                    placeholder="Ex: Luanda">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="localizacao" class="form-label fw-semibold">Localização</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-map-pin"></i></span>
                                <input type="text" class="form-control" id="localizacao" name="localizacao"
                                    placeholder="Ex: Rua do Nova Vida, nº 25">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="telefone" class="form-label fw-semibold">Telefone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-phone"></i></span>
                                <input type="text" class="form-control" id="telefone" name="telefone"
                                    placeholder="Ex: +244 944 273 243">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src={{'/js/Admin/clientes.js'}}></script>

@endsection
