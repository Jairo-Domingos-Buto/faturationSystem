<main class="flex-1 overflow-auto">
    {{-- Mensagens de Sucesso --}}
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="p-6 space-y-6">
        {{-- Barra de Ações --}}
        <div class="d-flex justify-content-between align-items-center mb-3 p-3 shadow-sm"
            style="background-color: #f8f9fa; border-radius: 10px;">

            <div class="d-flex gap-2">
                {{-- Botão NOVO aciona o método create --}}
                <button wire:click="create" type="button" class="btn btn"
                    style="background-color: #6C6FFF; color:white;">
                    <i class="bx bx-plus"></i> Novo
                </button>

                <a href="{{ url('/admin/impressao/servicos') }}" target="_blank" class="btn btn"
                    style="background-color: #6C6FFF; color:white;">
                    <i class="bx bx-printer"></i> Imprimir
                </a>
            </div>

            {{-- Campo de Pesquisa em tempo real --}}
            <div class="input-group w-25">
                <span class="input-group-text bg-white"><i class='bx bx-search'></i></span>
                <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                    placeholder="Pesquisar...">
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="card m-4">
        <table class="table table-hover">
            <thead class='p-1 bg-light'>
                <tr>
                    <th>DESCRIÇÃO</th>
                    <th class="text-end">preco_venda (KZ)</th>
                    <th class="text-center" style="width: 100px;">AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicos as $servico)
                <tr wire:key="servico-{{ $servico->id }}">
                    <td><strong>{{ $servico->descricao }}</strong></td>
                    <td class="text-end">
                        {{ number_format($servico->preco_venda, 2, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                {{-- Botão Editar --}}
                                <a class="dropdown-item" href="javascript:void(0);"
                                    wire:click="edit({{ $servico->id }})">
                                    <i class="bx bx-edit-alt me-1"></i> Editar
                                </a>

                                {{-- Botão Excluir com Confirmação nativa do Livewire --}}
                                <a class="dropdown-item text-danger" href="javascript:void(0);"
                                    wire:click="delete({{ $servico->id }})"
                                    wire:confirm="Tem certeza que deseja excluir este serviço?">
                                    <i class="bx bx-trash me-1"></i> Excluir
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">Nenhum serviço encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginação --}}
        <div class="px-4 py-2">
            {{ $servicos->links() }}
        </div>
    </div>

    <!-- Modal de Serviço -->
    <!-- Adicionamos wire:ignore.self para que o Livewire não renderize o modal inteiro e feche sozinho -->
    <div class="modal fade" id="servicoModal" tabindex="-1" aria-labelledby="servicoModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0 rounded-3">

                {{-- Formulário com submit preventivo para o método save --}}
                <form wire:submit.prevent="save">
                    <div class="modal-header text-white" style="background-color: #6C6FFF;">
                        <h5 class="modal-title text-white fw-bold" id="servicoModalLabel">
                            <i class="bx bx-cog me-2"></i>
                            {{ $servicoId ? 'Editar Serviço' : 'Cadastro de Serviço' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body bg-light">
                        <!-- Campo Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-semibold">Descrição do Serviço</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-detail"></i></span>
                                <input type="text" class="form-control @error('descricao') is-invalid @enderror"
                                    wire:model="descricao" placeholder="Ex: Consultoria técnica">
                            </div>
                            @error('descricao') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- Campo preco_venda -->
                        <div class="mb-3">
                            <label for="preco_venda" class="form-label fw-semibold">preco_venda (Kz)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-money"></i></span>
                                <input type="number" class="form-control @error('preco_venda') is-invalid @enderror"
                                    wire:model="preco_venda" step="0.01" placeholder="Ex: 15000">
                            </div>
                            @error('preco_venda') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">
                                <i class="bx bx-save me-1"></i> Salvar
                            </span>
                            <span wire:loading wire:target="save">
                                <i class='bx bx-loader-alt animate-spin'></i> A gravar...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

{{-- Scripts para controlar o Modal via eventos do Livewire --}}
@script
<script>
    let modalElement = document.getElementById('servicoModal');
    let bsModal = new bootstrap.Modal(modalElement);

    // Quando o Livewire disser 'open-modal'
    $wire.on('open-modal', () => {
        bsModal.show();
    });

    // Quando o Livewire disser 'close-modal' (depois de salvar)
    $wire.on('close-modal', () => {
        bsModal.hide();
    });

    // Resetar campos visuais quando modal fecha (opcional, o livewire já reseta no backend)
    modalElement.addEventListener('hidden.bs.modal', function () {
        $wire.dispatch('resetModal'); // Chama o resetInput no PHP se quiser limpar erros
    });
</script>
@endscript