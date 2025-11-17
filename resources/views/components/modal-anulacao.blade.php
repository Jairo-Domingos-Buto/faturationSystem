{{-- resources/views/components/modal-anulacao.blade.php --}}

<div x-data="modalAnulacao()" x-show="isOpen" x-cloak @abrir-modal-anulacao.window="abrirModal($event.detail)"
    class="fixed  inset-0 z-50 overflow-y-auto" style="display: none;">

    {{-- Overlay --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="fecharModal()">
        </div>

        {{-- Center Modal --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        {{-- Modal Content --}}
        <div x-show="isOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-center bg-white rounded-lg px-4 pt-5 pb-5 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

            <form :action="formAction" method="POST" @submit="submitForm($event)">
                @csrf

                {{-- Ícone de Aviso --}}
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        {{-- Título --}}
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Anular <span x-text="tipoDocumento"></span>
                        </h3>

                        {{-- Descrição --}}
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Você está prestes a anular o documento <strong x-text="numeroDocumento"></strong>.
                            </p>
                            <p class="text-sm text-red-600 font-semibold mt-2">
                                ⚠️ Esta ação é IRREVERSÍVEL e irá:
                            </p>
                            <ul class="text-xs text-gray-600 mt-2 space-y-1 list-disc list-inside">
                                <li>Devolver TODOS os produtos ao estoque</li>
                                <li>Marcar o documento como ANULADO permanentemente</li>
                                <li>Gerar uma Nota de Crédito de Anulação</li>
                            </ul>
                        </div>

                        {{-- Campo Motivo --}}
                        <div class="mt-4">
                            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Motivo da Anulação <span class="text-red-500">*</span>
                            </label>
                            <textarea name="motivo" id="motivo" rows="4" required maxlength="500"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Ex: Cliente cancelou a compra, erro no faturamento, etc."
                                x-model="motivo"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <span x-text="motivo.length"></span>/500 caracteres
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" :disabled="motivo.length < 10"
                        :class="motivo.length < 10 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-700'"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Confirmar Anulação
                    </button>

                    <button type="button" @click="fecharModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function modalAnulacao() {
    return {
        isOpen: false,
        tipoDocumento: '',
        numeroDocumento: '',
        documentoId: null,
        formAction: '',
        motivo: '',

        abrirModal(detail) {
            this.tipoDocumento = detail.tipo === 'fatura' ? 'Fatura' : 'Recibo';
            this.numeroDocumento = detail.numero;
            this.documentoId = detail.id;
            this.motivo = '';
            
            // Define a action do formulário
            if (detail.tipo === 'fatura') {
                this.formAction = `/admin/faturas/${detail.id}/anular`;
            } else {
                this.formAction = `/admin/recibos/${detail.id}/anular`;
            }
            
            this.isOpen = true;
        },

        fecharModal() {
            this.isOpen = false;
            this.motivo = '';
        },

        submitForm(event) {
            if (this.motivo.length < 10) {
                event.preventDefault();
                alert('O motivo deve ter no mínimo 10 caracteres.');
                return false;
            }
        }
    }
}
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>