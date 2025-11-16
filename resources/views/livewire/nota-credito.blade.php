<div class="bg-white p-6 min-h-screen">
    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between mb-6">
        <div class="mb-2">
            <h2 class="text-2xl font-bold text-gray-800">Notas de Crédito</h2>
            <p class="text-sm text-gray-600 mt-1">Documentos retificados e histórico de alterações</p>
        </div>

        {{-- Filtros de Data --}}
        <div class="bg-gray-50 rounded-lg border p-2 w-[400px]">
            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="start_date"
                    class="text-black border border-gray-400 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <span class="text-gray-600">→</span>
                <input type="date" wire:model.live="end_date"
                    class="text-black border border-gray-400 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Faturas Retificadas -->
        <div class="bg-white p-4 rounded-lg shadow border border-red-200">
            <p class="text-sm text-gray-600 font-medium">Faturas Retificadas</p>
            <p class="text-2xl font-bold text-red-700">
                {{ number_format($dados->faturas->sum('total'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->faturas->count() }} faturas</p>
        </div>

        <!-- Total Recibos Retificados -->
        <div class="bg-white p-4 rounded-lg shadow border border-orange-200">
            <p class="text-sm text-gray-600 font-medium">Recibos Retificados</p>
            <p class="text-2xl font-bold text-orange-700">
                {{ number_format($dados->recibos->sum('valor'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->recibos->count() }} recibos</p>
        </div>

        <!-- Total Geral -->
        <div class="bg-white p-4 rounded-lg shadow border border-purple-200">
            <p class="text-sm text-gray-600 font-medium">Total Retificações</p>
            <p class="text-2xl font-bold text-purple-700">
                {{ number_format($dados->faturas->sum('total') + $dados->recibos->sum('valor'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->faturas->count() + $dados->recibos->count() }} documentos
            </p>
        </div>
    </div>

    {{-- Tabela de Documentos Retificados --}}
    <div class="bg-white rounded-lg shadow overflow-hidden border">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Doc. Original
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nova Versão
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data Retificação
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Motivo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valores
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- FATURAS RETIFICADAS --}}
                    @foreach ($dados->faturas as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Documento Original -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">
                                    RETIFICADA
                                </span>
                            </div>
                            <div class="text-sm font-medium text-gray-900 line-through mt-1">
                                {{ $item->numero }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->emissao }}
                            </div>
                        </td>

                        <!-- Nova Versão -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->faturaRetificacao)
                            <div class="text-sm font-medium text-green-700">
                                {{ $item->faturaRetificacao->numero }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->faturaRetificacao->emissao }}
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <!-- Cliente -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $item->cliente->nome ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                NIF: {{ $item->cliente->nif ?? '-' }}
                            </div>
                        </td>

                        <!-- Data Retificação -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">
                                {{ $item->data_retificacao ? $item->data_retificacao->format('d/m/Y') : '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->data_retificacao ? $item->data_retificacao->format('H:i') : '' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Por: {{ $item->user?->name ?? 'N/A' }}
                            </div>
                        </td>

                        <!-- Motivo -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs" title="{{ $item->motivo_retificacao }}">
                                {{ Str::limit($item->motivo_retificacao ?? 'Sem motivo informado', 50) }}
                            </div>
                        </td>

                        <!-- Valores -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 line-through">
                                {{ number_format($item->total, 2, ',', '.') }} KZ
                            </div>
                            @if($item->faturaRetificacao)
                            <div class="text-sm text-green-600 font-medium">
                                {{ number_format($item->faturaRetificacao->total, 2, ',', '.') }} KZ
                            </div>
                            <div class="text-xs text-gray-500">
                                @php
                                $diferenca = $item->faturaRetificacao->total - $item->total;
                                @endphp
                                @if($diferenca > 0)
                                <span class="text-green-600">+{{ number_format($diferenca, 2, ',', '.') }} KZ</span>
                                @elseif($diferenca < 0) <span class="text-red-600">{{ number_format($diferenca, 2, ',',
                                    '.') }} KZ</span>
                                    @else
                                    <span class="text-gray-500">Sem alteração</span>
                                    @endif
                            </div>
                            @endif
                        </td>

                        <!-- Ações -->
                        <!-- resources/views/livewire/nota-credito.blade.php -->
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- ✅ Botão Ver Nota de Crédito (Original) -->
                                <a href="{{ route('admin.notas-credito.fatura', $item->id) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200"
                                    title="Ver Nota de Crédito">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                        
                                <!-- ✅ Botão Ver Nova Versão -->
                                @if($item->faturaRetificacao)
                                <a href="#"
                                    class="flex items-center justify-center w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg transition-colors duration-200"
                                    title="Ver Nova Fatura">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- RECIBOS RETIFICADOS --}}
                    @foreach ($dados->recibos as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Documento Original -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded">
                                    RETIFICADO
                                </span>
                            </div>
                            <div class="text-sm font-medium text-gray-900 line-through mt-1">
                                {{ $item->numero }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->emissao }}
                            </div>
                        </td>

                        <!-- Nova Versão -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->reciboRetificacao)
                            <div class="text-sm font-medium text-green-700">
                                {{ $item->reciboRetificacao->numero }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->reciboRetificacao->emissao }}
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <!-- Cliente -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $item->cliente->nome ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                NIF: {{ $item->cliente->nif ?? '-' }}
                            </div>
                        </td>

                        <!-- Data Retificação -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">
                                {{ $item->data_retificacao ? $item->data_retificacao->format('d/m/Y') : '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->data_retificacao ? $item->data_retificacao->format('H:i') : '' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                Por: {{ $item->user?->name ?? 'N/A' }}
                            </div>
                        </td>

                        <!-- Motivo -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs" title="{{ $item->motivo_retificacao }}">
                                {{ Str::limit($item->motivo_retificacao ?? 'Sem motivo informado', 50) }}
                            </div>
                        </td>

                        <!-- Valores -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 line-through">
                                {{ number_format($item->valor, 2, ',', '.') }} KZ
                            </div>
                            @if($item->reciboRetificacao)
                            <div class="text-sm text-green-600 font-medium">
                                {{ number_format($item->reciboRetificacao->valor, 2, ',', '.') }} KZ
                            </div>
                            <div class="text-xs text-gray-500">
                                @php
                                $diferenca = $item->reciboRetificacao->valor - $item->valor;
                                @endphp
                                @if($diferenca > 0)
                                <span class="text-green-600">+{{ number_format($diferenca, 2, ',', '.') }} KZ</span>
                                @elseif($diferenca < 0) <span class="text-red-600">{{ number_format($diferenca, 2, ',',
                                    '.') }} KZ</span>
                                    @else
                                    <span class="text-gray-500">Sem alteração</span>
                                    @endif
                            </div>
                            @endif
                        </td>

                        <!-- Ações -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Ver Original -->
                                <a href="{{ route('admin.notas-credito.recibo', $item->id) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors duration-200"
                                    title="Ver Recibo Original">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                @if($item->reciboRetificacao)
                                <!-- Ver Nova Versão -->
                                <a href="{{ route('admin.notas-credito.recibo', $item->reciboRetificacao->id) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg transition-colors duration-200"
                                    title="Ver Novo Recibo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if ($dados->faturas->isEmpty() && $dados->recibos->isEmpty())
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm font-medium">Nenhuma nota de crédito encontrada</p>
                            <p class="text-xs text-gray-400 mt-1">Documentos retificados aparecerão aqui</p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>