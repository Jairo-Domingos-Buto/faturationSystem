@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoFornecedorModal"
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
                    <th>NOME</th>
                    <th>NIF</th>
                    <th>EMAIL</th>
                    <th>TELEFONE</th>
                    <th>PROVÍNCIA</th>
                    <th>CIDADE</th>
                    <th>LOCALIZAÇÃO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="tabela-fornecedores">
                <tr>
                    <td colspan="8" class="text-center text-muted">A carregar fornecedores...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Novo Fornecedor -->
    <div class="modal fade" id="novoFornecedorModal" tabindex="-1" aria-labelledby="novoFornecedorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="fornecedor-form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white fw-bold" id="novoFornecedorModalLabel">
                            <i class="bx bx-building-house me-2"></i>Cadastro de Fornecedor
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label fw-semibold">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                    placeholder="Ex: João Manuel" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nif" class="form-label fw-semibold">NIF</label>
                                <input type="text" class="form-control" id="nif" name="nif"
                                    placeholder="Ex: 005678910LA043" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Ex: fornecedor@email.com">
                            </div>
                            <div class="col-md-6">
                                <label for="telefone" class="form-label fw-semibold">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone"
                                    placeholder="Ex: +244 944 273 243">
                            </div>
                            <div class="col-md-6">
                                <label for="provincia" class="form-label fw-semibold">Província</label>
                                <input type="text" class="form-control" id="provincia" name="provincia"
                                    placeholder="Ex: Benguela">
                            </div>
                            <div class="col-md-6">
                                <label for="cidade" class="form-label fw-semibold">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade"
                                    placeholder="Ex: Lobito">
                            </div>
                            <div class="col-12">
                                <label for="localizacao" class="form-label fw-semibold">Localização</label>
                                <input type="text" class="form-control" id="localizacao" name="localizacao"
                                    placeholder="Ex: Rua Nova Vida, nº 25">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('../js/Admin/fornecedor.js') }}"></script>
@endsection