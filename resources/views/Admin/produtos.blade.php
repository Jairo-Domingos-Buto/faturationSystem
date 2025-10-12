@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #E2E3E5; height: 70px; width: 100%; border-radius: 10px;">

            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoProdutoModal"
                style="margin-left: 20px; background-color: #6C6FFF; color:white;">
                Novo
            </button>
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoClienteModal"
                style="margin-right: 20px; background-color: #6C6FFF; color:white;">
                Pesquisar
            </button>
        </div>


    </div>
    <!-- Hoverable Table rows -->
    <div class=" card">


        <table class="table table-hover">
            <thead class='p-1'>
                <tr>
                    <th>Daescrição </th>
                    <th>CATEGORIA</th>
                    <th>FORNECEDOR</th>
                    <th>PREÇO DE COMPRA</th>
                    <th>PREÇO DE VENDA</th>
                    <th>ESTOQUE</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">

                <tr>
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Inocencio</strong></td>
                    <td>04934034034LA049</td>
                    <td>Luanda</td>
                    <td>Luanda</td>
                    <td>934543562</td>
                    <td>934543562</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a onclick="openModal()" class="dropdown-item" href="javascript:void(0);"><i
                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                    Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
    <!--/ Hoverable Table rows -->

    <!-- Modal Novo Cliente -->
    <div class="modal fade" id="novoProdutoModal" tabindex="-1" aria-labelledby="novoProdutoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <form id="produto-form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white fw-bold" id="novoProdutoModalLabel">
                            <i class="bx bx-package me-2"></i> Novo Produto
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>

                    </div>

                    <div class="modal-body bg-light">
                        <!-- Nome -->
                        <div class="form-group mb-2">
                            <label for="nome" class="form-label fw-semibold">Nome do Produto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-box"></i>
                                </span>
                                <input type="text" class="form-control" id="nome" name="nome"
                                    placeholder="Ex: Arroz Tio João 1Kg">
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div class="row">
                            <!-- Categoria -->
                            <div class="form-group col-sm-6">
                                <label for="categoria" class="form-label fw-semibold">Categoria</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bx bx-category"></i>
                                    </span>
                                    <select class="form-select" id="categoria" name="categoria" required>
                                        <option value="" selected disabled>Selecione a categoria</option>
                                        <option value="alimentacao">Alimentação</option>
                                        <option value="bebidas">Bebidas</option>
                                        <option value="higiene">Higiene</option>
                                        <option value="vestuario">Vestuário</option>
                                        <option value="outros">Outros</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fornecedor -->
                            <div class="form-group col-sm-6">
                                <label for="fornecedor" class="form-label fw-semibold">Fornecedor</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bx bx-store"></i>
                                    </span>
                                    <select class="form-select" id="fornecedor" name="fornecedor" required>
                                        <option value="" selected disabled>Selecione o fornecedor</option>
                                        <option value="angoalimentos">AngoAlimentos Lda</option>
                                        <option value="grupokwanza">Grupo Kwanza</option>
                                        <option value="novaera">Nova Era Distribuição</option>
                                        <option value="topmercado">Top Mercado</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Código de Barras -->
                        <div class="form-group mb-2">
                            <label for="codigo_barra" class="form-label fw-semibold">Código de Barras</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-barcode"></i>
                                </span>
                                <input type="text" class="form-control" id="codigo_barra" name="codigo_barra"
                                    placeholder="Ex: 5601234567890">
                            </div>
                        </div>

                        <!-- Preço de Compra -->
                        <div class="form-group mb-2">
                            <label for="preco_compra" class="form-label fw-semibold">Preço de Compra</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-purchase-tag"></i>
                                </span>
                                <input type="number" class="form-control" id="preco_compra" name="preco_compra"
                                    placeholder="Ex: 1500 Kz">
                            </div>
                        </div>

                        <!-- Preço de Venda -->
                        <div class="form-group mb-2">
                            <label for="preco_venda" class="form-label fw-semibold">Preço de Venda</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-money"></i>
                                </span>
                                <input type="number" class="form-control" id="preco_venda" name="preco_venda"
                                    placeholder="Ex: 2000 Kz">
                            </div>
                        </div>

                        <!-- Data de Validade -->
                        <div class="form-group mb-2">
                            <label for="validade" class="form-label fw-semibold">Data de Validade</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-calendar"></i>
                                </span>
                                <input type="date" class="form-control" id="validade" name="validade">
                            </div>
                        </div>

                        <!-- Estoque -->
                        <div class="form-group mb-2">
                            <label for="estoque" class="form-label fw-semibold">Estoque</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-layer"></i>
                                </span>
                                <input type="number" class="form-control" id="estoque" name="estoque"
                                    placeholder="Ex: 100 unidades">
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

    </script>
    @endsection