<div class="bg-white p-6 min-h-screen">
    {{-- ✅ INCLUIR MODAL DE ANULAÇÃO --}}
    <x-modal-anulacao />

    {{-- Alerta se empresa não estiver configurada --}}
   
    {{-- Cabeçalho --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Exportar o SAFT</h2>
    </div>

    {{-- Filtros de Data e Ações --}}
    <div class="bg-gray-50 rounded-lg border p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            {{-- Filtro de Datas --}}
            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="start_date"
                    class="text-black border border-gray-400 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <span class="text-gray-600">→</span>
                <input type="date" wire:model.live="end_date"
                    class="text-black border border-gray-400 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Botão Criar Nova Fatura --}}
            <div>
                <a 
                    class="flex justify-center items-center w-auto px-4 bg-blue-500 text-white rounded p-2 hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mr-2 h-4 w-4">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                        <path d="M10 9H8"></path>
                        <path d="M16 13H8"></path>
                        <path d="M16 17H8"></path>
                    </svg>
                    <span>Exportar</span>
                </a>
            </div>
        </div>
    </div>

    

</div>
