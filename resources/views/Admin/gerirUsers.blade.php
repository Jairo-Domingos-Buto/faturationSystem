@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">

            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoClienteModal"
                style="margin-left: 20px; background-color: #010625b2; color:white;">
                Novo
            </button>
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoClienteModal"
                style="margin-right: 20px; background-color: #010625b2; color:white;">
                Pesquisar
            </button>
        </div>


    </div>
    <!-- Hoverable Table rows -->
    <div class=" card">


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
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Inocencio</strong></td>
                    <td>04934034034LA049</td>
                    <td>Luanda</td>
                    <td>Luanda</td>
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
    <div class="modal fade" id="novoClienteModal" tabindex="-1" aria-labelledby="novoClienteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="cliente-form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-header" style="background-color: #010625b2;">
                        <h5 class="modal-title text-white" id="novoClienteModalLabel">Novo Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="nome" class="form-label fw-semibold">Nome do Cliente</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-user"></i>
                                </span>
                                <input type="text" class="form-control" id="nome" placeholder="Ex: João Manuel">
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="bilhete" class="form-label fw-semibold">NIF</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-id-card"></i>
                                </span>
                                <input type="text" class="form-control" id="bilhete" placeholder="Ex: 005678910LA043">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="endereco" class="form-label fw-semibold">Província</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-map"></i>
                                </span>
                                <input type="text" class="form-control" id="endereco" placeholder="Ex: Benguela">
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="endereco" class="form-label fw-semibold">Cidade</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-map"></i>
                                </span>
                                <input type="text" class="form-control" id="endereco" placeholder="Ex: Luanda">
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="endereco" class="form-label fw-semibold">Localização</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-map"></i>
                                </span>
                                <input type="text" class="form-control" id="endereco"
                                    placeholder="Ex: Rua do nova vida, nº 25">
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="endereco" class="form-label fw-semibold">Telefone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bx bx-map"></i>
                                </span>
                                <input type="text" class="form-control" id="endereco"
                                    placeholder="Ex: +244 944 273 243">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
  
    @endsection
