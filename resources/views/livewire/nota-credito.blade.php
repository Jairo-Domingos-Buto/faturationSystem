{{-- resources/views/livewire/nota-credito.blade.php --}}

<div class="bg-white p-6 min-h-screen">
    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between mb-6">
        <div class="mb-2">
            <h2 class="text-2xl font-bold text-gray-800">Notas de Crédito</h2>
            <p class="text-sm text-gray-600 mt-1">Documentos retificados e anulados com histórico completo</p>
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Faturas Retificadas -->
        <div class="bg-white p-4 rounded-lg shadow border border-red-200">
            <p class="text-sm text-gray-600 font-medium">Faturas Retificadas</p>
            <p class="text-2xl font-bold text-red-700">
                {{ number_format($dados->faturas_retificadas->sum('total'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->faturas_retificadas->count() }} documentos</p>
        </div>

        <!-- Faturas Anuladas -->
        <div class="bg-white p-4 rounded-lg shadow border border-gray-400">
            <p class="text-sm text-gray-600 font-medium">Faturas Anuladas</p>
            <p class="text-2xl font-bold text-gray-700">
                {{ number_format($dados->faturas_anuladas->sum('total'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->faturas_anuladas->count() }} documentos</p>
        </div>

        <!-- Recibos Retificados -->
        <div class="bg-white p-4 rounded-lg shadow border border-orange-200">
            <p class="text-sm text-gray-600 font-medium">Recibos Retificados</p>
            <p class="text-2xl font-bold text-orange-700">
                {{ number_format($dados->recibos_retificados->sum('valor'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->recibos_retificados->count() }} documentos</p>
        </div>

        <!-- Recibos Anulados -->
        <div class="bg-white p-4 rounded-lg shadow border border-gray-400">
            <p class="text-sm text-gray-600 font-medium">Recibos Anulados</p>
            <p class="text-2xl font-bold text-gray-700">
                {{ number_format($dados->recibos_anulados->sum('valor'), 2, ',', '.') }} KZ
            </p>
            <p class="text-xs text-gray-500 mt-1">{{ $dados->recibos_anulados->count() }} documentos</p>
        </div>
    </div>

    {{-- Tabela Unificada --}}
    <div class="bg-white rounded-lg shadow overflow-hidden border">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doc.
                            Original</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nova
                            Versão</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Motivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valores</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    {{-- ========== FATURAS RETIFICADAS ========== --}}
                    @foreach ($dados->faturas_retificadas as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">
                                FATURA RETIFICADA
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 line-through">{{ $item->numero }}</div>
                            <div class="text-xs text-gray-500">{{ $item->emissao }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->faturaRetificacao)
                            <div class="text-sm font-medium text-green-700">{{ $item->faturaRetificacao->numero }}</div>
                            <div class="text-xs text-gray-500">{{ $item->faturaRetificacao->emissao }}</div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->cliente->nome ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">NIF: {{ $item->cliente->nif ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $item->data_retificacao ?
                                $item->data_retificacao->format('d/m/Y H:i') : '-' }}</div>
                            <div class="text-xs text-gray-500">Por: {{ $item->user?->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 truncate py-4">
                            <div class="text-sm text-gray-700 max-w-xs" title="{{ $item->motivo_retificacao }}">
                                {{ Str::limit($item->motivo_retificacao ?? 'Sem motivo informado', 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 line-through">{{ number_format($item->total, 2, ',', '.')
                                }} KZ</div>
                            @if($item->faturaRetificacao)
                            <div class="text-sm text-green-600 font-medium">{{
                                number_format($item->faturaRetificacao->total, 2, ',', '.') }} KZ</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.notas-credito.fatura', $item->id) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg"
                                    title="Ver Nota de Crédito">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- ========== FATURAS ANULADAS ========== --}}
                    @foreach ($dados->faturas_anuladas as $item)
                    <tr class="hover:bg-gray-50 transition bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-700 text-white rounded">
                                FATURA ANULADA
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 line-through">{{ $item->numero }}</div>
                            <div class="text-xs text-gray-500">{{ $item->emissao }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500 text-xs italic">Documento anulado</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->cliente->nome ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">NIF: {{ $item->cliente->nif ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $item->data_anulacao ?
                                $item->data_anulacao->format('d/m/Y H:i') : '-' }}</div>
                            <div class="text-xs text-gray-500">Por: {{ $item->anuladaPor?->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs" title="{{ $item->motivo_anulacao }}">
                                {{ Str::limit($item->motivo_anulacao ?? 'Sem motivo informado', 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 line-through font-semibold">{{ number_format($item->total,
                                2, ',', '.') }} KZ</div>
                            <div class="text-xs text-gray-500 mt-1">Estoque devolvido</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.notas-credito.anulacao', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg"
                                    title="Ver Nota de Crédito de Anulação">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.notas-credito.anulacao.pdf', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg"
                                    title="Download PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- ========== RECIBOS RETIFICADOS ========== --}}
                    @foreach ($dados->recibos_retificados as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded">
                                RECIBO RETIFICADO
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 line-through">{{ $item->numero }}</div>
                            <div class="text-xs text-gray-500">{{ $item->emissao }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->reciboRetificacao)
                            <div class="text-sm font-medium text-green-700">{{ $item->reciboRetificacao->numero }}</div>
                            <div class="text-xs text-gray-500">{{ $item->reciboRetificacao->emissao }}</div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->cliente->nome ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">NIF: {{ $item->cliente->nif ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $item->data_retificacao ?
                                $item->data_retificacao->format('d/m/Y H:i') : '-' }}</div>
                            <div class="text-xs text-gray-500">Por: {{ $item->user?->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs" title="{{ $item->motivo_retificacao }}">
                                {{ Str::limit($item->motivo_retificacao ?? 'Sem motivo informado', 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 line-through">{{ number_format($item->valor, 2, ',', '.')
                                }} KZ</div>
                            @if($item->reciboRetificacao)
                            <div class="text-sm text-green-600 font-medium">{{
                                number_format($item->reciboRetificacao->valor, 2, ',', '.') }} KZ</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.notas-credito.recibo', $item->id) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg"
                                    title="Ver Nota de Crédito">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- ========== RECIBOS ANULADOS ========== --}}
                    @foreach ($dados->recibos_anulados as $item)
                    <tr class="hover:bg-gray-50 transition bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-700 text-white rounded">
                                RECIBO ANULADO
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 line-through">{{ $item->numero }}</div>
                            <div class="text-xs text-gray-500">{{ $item->emissao }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500 text-xs italic">Documento anulado</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->cliente->nome ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">NIF: {{ $item->cliente->nif ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $item->data_anulacao ?
                                $item->data_anulacao->format('d/m/Y H:i') : '-' }}</div>
                            <div class="text-xs text-gray-500">Por: {{ $item->anuladoPor?->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs" title="{{ $item->motivo_anulacao }}">
                                {{ Str::limit($item->motivo_anulacao ?? 'Sem motivo informado', 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 line-through font-semibold">{{ number_format($item->valor,
                                2, ',', '.') }} KZ</div>
                            <div class="text-xs text-gray-500 mt-1">Estoque devolvido</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.notas-credito.anulacao', ['tipo' => 'recibo', 'id' => $item->id]) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg"
                                    title="Ver Nota de Crédito de Anulação">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.notas-credito.anulacao.pdf', ['tipo' => 'recibo', 'id' => $item->id]) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg"
                                    title="Download PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Se não houver dados --}}
                    @if ($dados->faturas_retificadas->isEmpty() &&
                    $dados->faturas_anuladas->isEmpty() &&
                    $dados->recibos_retificados->isEmpty() &&
                    $dados->recibos_anulados->isEmpty())
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm font-medium">Nenhuma nota de crédito encontrada</p>
                            <p class="text-xs text-gray-400 mt-1">Documentos retificados e anulados aparecerão aqui</p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>