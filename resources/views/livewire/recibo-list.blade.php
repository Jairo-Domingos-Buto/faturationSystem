<div>
    <main class="bg-white p-3 h-full">
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Recibos</h2>
        </div>

        <header class="bg-gray-50 rounded border p-3 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">De</label>
                    <!-- Atualização em tempo real: debounce curto para evitar muitos requests -->
                    <input wire:model.debounce.400ms="start_date" type="date"
                        class="text-black border p-2 rounded border-gray-300">
                    <label class="text-sm text-gray-600 ml-3">Até</label>
                    <input wire:model.debounce.400ms="end_date" type="date"
                        class="text-black border p-2 rounded border-gray-300">
                </div>

                <div>
                    <button class="flex justify-center w-[180px] bg-[#3c83f6] text-white rounded p-2 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                        </svg>
                        <span>Exportar/Imprimir</span>
                    </button>
                </div>
            </div>
        </header>

        <div class="p-2 bg-white rounded border">
            @if ($recibos->isEmpty())
            <div class="p-4 text-center text-gray-600">Nenhum recibo encontrado para o período selecionado.</div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Número</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Fatura</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Cliente</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Usuário</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Valor</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Data</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($recibos as $recibo)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $recibo->id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $recibo->numero }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($recibo->fatura)->numero ?? '—' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($recibo->cliente)->nome ??
                                'Consumidor Final' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ optional($recibo->user)->nome ??
                                optional($recibo->user)->email ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($recibo->valor, 2, ',', '.') }}
                                KZ</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{
                                optional($recibo->data_emissao)->format('d/m/Y') ?? $recibo->created_at->format('d/m/Y')
                                }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $recibos->links() }}
            </div>
            @endif
        </div>
    </main>
</div>