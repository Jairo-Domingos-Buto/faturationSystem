@extends('layout.main')

@section('content')
<main class="flex-1 overflow-auto">
    <div class="p-6 space-y-6">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="background-color: #dad6d6e8; height: 70px; width: 100%; border-radius: 10px;">
            <button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#servicoModal"
                style="margin-left: 20px; background-color: #6C6FFF; color:white;">
                Novo
            </button>
            <button type="button" class="btn btn" style="margin-right: 20px; background-color: #6C6FFF; color:white;">
                Pesquisar
            </button>
        </div>
    </div>

    <!-- Tabela -->
    <div class="card">
        <table class="table table-hover">
            <thead class='p-1'>
                <tr>
                    <th>DESCRIÇÃO</th>
                    <th>VALOR (KZ)</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody id="tabela-servicos">
                <tr>
                    <td colspan="3" class="text-center text-muted">A carregar serviços...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal de Serviço -->
    <div class="modal fade" id="servicoModal" tabindex="-1" aria-labelledby="servicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <form id="servico-form">
                    @csrf
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title fw-bold" id="servicoModalLabel">
                            <i class="bx bx-cog me-2"></i> Cadastro de Serviço
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-semibold">Descrição do Serviço</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-detail"></i></span>
                                <input type="text" class="form-control" id="descricao" name="descricao"
                                    placeholder="Ex: Consultoria técnica" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="valor" class="form-label fw-semibold">Valor (Kz)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-money"></i></span>
                                <input type="number" class="form-control" id="valor" name="valor" step="0.01"
                                    placeholder="Ex: 15000" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset('../js/Admin/servicos.js') }}"></script>

@endsection