@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#categoriaModal"
                style="margin-left: 20px; background-color: #6C6FFF; color:white;">
                Nova
            </button>
            <button type="button" class="btn" style="margin-right: 20px; background-color: #6C6FFF; color:white;">
                Pesquisar
            </button>
        </div>
    </div>

    <!-- Tabela -->
    <div class="card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>NOME</th>
                    <th>DESCRIÇÃO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody id="tabela-categorias">
                <tr>
                    <td colspan="3" class="text-center text-muted">A carregar categorias...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Categoria -->
    <div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="categoria-form" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white" id="categoriaModalLabel">
                            <i class="bx bx-category me-2"></i>Cadastro de Categoria
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label fw-semibold">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="col-md-6">
                                <label for="descricao" class="form-label fw-semibold">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" required>
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
</main>
<script src="{{ asset('../js/Admin/categoria.js') }}"></script>

@endsection