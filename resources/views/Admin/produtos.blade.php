@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex gap-2 align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoProdutoModal"
                style="margin-left: 20px; background-color: #6C6FFF; color:white;">
                Novo
            </button>
            <button id="btn-imprimir-produtos" type="button" class="btn btn"
                style="background-color: #6C6FFF; color:white;">
                Imprimir
            </button>
        </div>

        <!-- Tabela -->
        <div class="card">
            <table class="table table-hover" id="tabela-produtos">
                <thead class='p-1'>
                    <tr>
                        <th>DESCRIÇÃO</th>
                        <th>CATEGORIA</th>
                        <th>FORNECEDOR</th>
                        <th>PREÇO COMPRA</th>
                        <th>PREÇO VENDA</th>
                        <th>ESTOQUE</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="produtos-body">
                    <tr>
                        <td colspan="7" class="text-center text-muted">Carregando produtos...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo Produto -->
    <div class="modal fade" id="novoProdutoModal" tabindex="-1" aria-labelledby="novoProdutoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <form id="produto-form">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white fw-bold" id="novoProdutoModalLabel">
                            <i class="bx bx-package me-2"></i> Novo Produto
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="row">

                            <div class="form-group col-sm-6">

                                <label class="form-label fw-semibold">Nome do Produto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bx bx-box"></i>
                                    </span>
                                    <input type="text" class="form-control" id="descricao" name="descricao"
                                        placeholder="Ex: Arroz Tio João 1Kg" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Categoria</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bx bx-category"></i>
                                    </span>
                                    <select class="form-select" id="categoria" name="categoria_id" required>
                                        <option value="" disabled selected>Carregando categorias...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Fornecedor</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bx bx-store"></i>
                                    </span>
                                    <select class="form-select" id="fornecedor" name="fornecedor_id" required>
                                        <option value="" disabled selected>Carregando fornecedores...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Código de Barras</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-barcode"></i></span>
                                    <input type="text" class="form-control" id="codigo_barras" name="codigo_barras"
                                        placeholder="Ex: 5601234567890">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Preço de Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-purchase-tag"></i></span>
                                    <input type="number" class="form-control" id="preco_compra" name="preco_compra"
                                        placeholder="Ex: 1500" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Preço de Venda</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-money"></i></span>
                                    <input type="number" class="form-control" id="preco_venda" name="preco_venda"
                                        placeholder="Ex: 2000" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Data Validade</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-money"></i></span>
                                    <input type="date" class="form-control" id="data_validade" name="data_validade"
                                        required>

                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Estoque</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-layer"></i></span>
                                    <input type="number" class="form-control" id="estoque" name="estoque"
                                        placeholder="Ex: 100" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label fw-semibold">Imposto</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-percent"></i></span>
                                    <select class="form-select" id="imposto" name="imposto_id">
                                        <!-- <option value="">-- Selecione o imposto--</option> -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-6" id="motivo-container" style="display: none;">
                                <label class="form-label fw-semibold">Motivo de Isenção</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-message-square"></i></span>
                                    <select class="form-select" id="motivo_isencao" name="motivo_isencaos_id">
                                        <option value="">-- Selecione o motivo de isenção --</option>
                                    </select>
                                </div>
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
<script src="{{ asset('../js/Admin/produto.js') }}"></script>
@endsection