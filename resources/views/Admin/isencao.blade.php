@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #E2E3E5; height: 70px; width: 100%; border-radius: 10px;">

            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoClienteModal"
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
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="motivo-isencao-form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white fw-bold" id="novoClienteModalLabel">
                            <i class="bx bx-error-circle me-2"></i> Motivos de Isenção
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>

                    </div>

                    <div class="modal-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="codigo" class="form-label fw-semibold">Código</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-code"></i></span>
                                    <input type="text" class="form-control" id="codigo" name="codigo"
                                        placeholder="Ex: M01" required>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label for="razao" class="form-label fw-semibold">Razão</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-bookmark"></i></span>
                                    <input type="text" class="form-control" id="razao" name="razao"
                                        placeholder="Ex: Isenção de IVA para exportação" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="descricao" class="form-label fw-semibold">Descrição</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-detail"></i></span>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="3"
                                        placeholder="Descreva brevemente o motivo da isenção..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </script>
    @endsection