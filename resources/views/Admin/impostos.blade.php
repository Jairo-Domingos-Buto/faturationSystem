@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#novoImpostoModal"
                style="margin-left: 20px; background-color: #6C6FFF; color:white;">
                Novo
            </button>
            <button type="button" class="btn btn" style="margin-right: 20px; background-color: #6C6FFF; color:white;">
                Pesquisar
            </button>
        </div>
    </div>

    <!-- Tabela de Impostos -->
    <div class="card">
        <table class="table table-hover">
            <thead class='p-1'>
                <tr>
                    <th>DESCRIÇÃO</th>
                    <th>TAXA (%)</th>
                    <th>CÓDIGO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="tabela-impostos">
                <tr>
                    <td colspan="4" class="text-center text-muted">A carregar impostos...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Novo/Editar Imposto -->
    <div class="modal fade" id="novoImpostoModal" tabindex="-1" aria-labelledby="novoImpostoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="imposto-form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white fw-bold" id="novoImpostoModalLabel">
                            <i class="bx bx-calculator me-2"></i> Cadastro de Imposto
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="descricao" class="form-label fw-semibold">Descrição</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-detail"></i></span>
                                    <input type="text" class="form-control" id="descricao" name="descricao"
                                        placeholder="Ex: IVA - Imposto sobre Valor Acrescentado" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="taxa" class="form-label fw-semibold">Taxa (%)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-percent"></i></span>
                                    <input type="number" class="form-control" id="taxa" name="taxa" placeholder="Ex: 14"
                                        step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="codigo" class="form-label fw-semibold">Código</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bx bx-code-alt"></i></span>
                                    <input type="text" class="form-control" id="codigo" name="codigo"
                                        placeholder="Ex: IVA001" required>
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
</main>
<script src="{{ asset('../js/Admin/impostos.js') }}"></script>
@endsection