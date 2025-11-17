<div class="bg-white p-6 min-h-screen">
   {{-- ✅ INCLUIR MODAL DE ANULAÇÃO --}}
    <x-modal-anulacao />

    {{-- Alerta se empresa não estiver configurada --}}
    @if(!$empresa)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Atenção:</strong> Os dados da empresa não foram configurados.
                    <a href="{{ route('admin.configuracoes') }}" class="font-medium underline">
                        Configure aqui
                    </a>
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Cabeçalho --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestão de Faturas</h2>
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
                <a href="{{ route('admin.pov') }}"
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
                    <span>Criar Fatura</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Mensagem de Sucesso --}}
    @if(session()->has('message'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
        <p class="text-green-700">{{ session('message') }}</p>
    </div>
    @endif

    {{-- Estatísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Faturas Geradas</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $totalFaturas }}</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Subtotal (AOA)</p>
                    <p class="text-2xl font-bold text-green-700">{{ number_format($somaSubtotal, 2, ',', '.') }} KZ</p>
                </div>
                <div class="bg-green-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border border-yellow-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">IVA Total</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ number_format($somaImpostos, 2, ',', '.') }} KZ</p>
                </div>
                <div class="bg-yellow-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Geral</p>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($somaTotal, 2, ',', '.') }} KZ</p>
                </div>
                <div class="bg-purple-500 p-3 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Estatísticas por Estado --}}

    {{-- Tabela de Faturas --}}
    <div class="bg-white rounded-lg shadow overflow-hidden border">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Emissão
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Número
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subtotal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IVA
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($faturas as $fatura)
                    <tr class="hover:bg-gray-50 transition {{ $fatura->retificada ? 'opacity-60' : '' }}">
                        <!-- Data Emissão -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $fatura->data_emissao->format('d/m/Y') }}
                        </td>

                        <!-- Número -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="text-sm font-medium text-gray-900 {{ $fatura->retificada ? 'line-through' : '' }}">
                                {{ $fatura->numero }}
                            </div>
                            @if($fatura->retificada && $fatura->faturaRetificacao)
                            <div class="text-xs text-green-600 mt-1">
                                → {{ $fatura->faturaRetificacao->numero }}
                            </div>
                            @endif
                        </td>

                        <!-- Cliente -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $fatura->cliente->nome ?? 'N/A' }}
                        </td>

                        <!-- Usuário -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $fatura->user?->name ?? 'N/A' }}
                        </td>

                        <!-- Subtotal -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="text-sm text-gray-700 {{ $fatura->retificada ? 'line-through text-red-600' : '' }}">
                                {{ number_format($fatura->subtotal, 2, ',', '.') }} KZ
                            </div>
                            @if($fatura->retificada && $fatura->faturaRetificacao)
                            <div class="text-xs text-green-600 font-medium mt-1">
                                {{ number_format($fatura->faturaRetificacao->subtotal, 2, ',', '.') }} KZ
                            </div>
                            @endif
                        </td>

                        <!-- IVA -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="text-sm text-gray-700 {{ $fatura->retificada ? 'line-through text-red-600' : '' }}">
                                {{ number_format($fatura->total_impostos, 2, ',', '.') }} KZ
                            </div>
                            @if($fatura->retificada && $fatura->faturaRetificacao)
                            <div class="text-xs text-green-600 font-medium mt-1">
                                {{ number_format($fatura->faturaRetificacao->total_impostos, 2, ',', '.') }} KZ
                            </div>
                            @endif
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="text-sm font-semibold text-gray-900 {{ $fatura->retificada ? 'line-through text-red-600' : '' }}">
                                {{ number_format($fatura->total, 2, ',', '.') }} KZ
                            </div>
                            @if($fatura->retificada && $fatura->faturaRetificacao)
                            <div class="text-xs text-green-600 font-medium mt-1">
                                {{ number_format($fatura->faturaRetificacao->total, 2, ',', '.') }} KZ
                            </div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($fatura->retificada)
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                RETIFICADA
                            </span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $fatura->estado === 'emitida' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($fatura->estado) }}
                            </span>
                            @endif
                        </td>

                        <!-- Ações -->
                        <!-- resources/views/admin/faturas/index.blade.php -->

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Botão Ver -->
                                <a href="{{ route('admin.fatura.download', $fatura->id) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200"
                                    title="Ver Fatura">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <!-- ✅ Botão Retificar -->
                                @if($fatura->pode_ser_retificada)
                                <a href="{{ route('admin.pov') }}?retificar_id={{ $fatura->id }}&tipo=fatura"
                                    class="flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200"
                                    title="Retificar Fatura">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </a>
                                @endif

                                <!-- ✅ Botão Anular -->
                                @if($fatura->pode_ser_anulada)
                                <button wire:click="delete({{ $fatura->id }})"
                                    class="flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200"
                                    title="Anular Fatura">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @endif

                                <!-- ✅ Badge se foi ANULADA -->
                                @if($fatura->anulada)
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-gray-700 rounded">
                                    ANULADA
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm font-medium">Nenhuma fatura encontrada no período selecionado</p>
                            <p class="text-xs text-gray-400 mt-1">Faturas retificadas aparecem em "Notas de Crédito"</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $faturas->links() }}
        </div>
    </div>
    
</div>


@push('scripts')
<script>
    console.log('Script JS faturas carregado - início'); // Debug: remova depois

    // Listener DIRETO (sem 'livewire:initialized' – evita conflito no init)
    Livewire.on('abrirModalAnulacao', (event) => {
        console.log('Evento recebido no JS faturas!', event); // Debug
        window.dispatchEvent(new CustomEvent('abrir-modal-anulacao', { 
            detail: event 
        }));
        console.log('Custom event despachado para Alpine'); // Debug
    });

    console.log('Script JS faturas carregado - fim'); // Debug
</script>
@endpush