<div class="bg-slate-50 p-6 min-h-screen">
    {{-- ✅ INCLUIR MODAL DE ANULAÇÃO --}}
    <x-modal-anulacao />

    {{-- Alerta se empresa não configurada --}}
    @if(!$empresa)
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-r shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0"><i class='bx bx-error text-2xl text-amber-500'></i></div>
            <div class="ml-3">
                <p class="text-sm text-amber-700 font-medium">Atenção: Dados da empresa ausentes.</p>
                <a href="{{ route('admin.configuracoes') }}"
                    class="text-xs text-amber-600 underline hover:text-amber-800">Configurar agora</a>
            </div>
        </div>
    </div>
    @endif

    {{-- Cabeçalho e Título --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bx-file-find text-blue-600'></i> Gestão de Documentos
            </h2>
            <p class="text-sm text-slate-500 mt-1">Consulte e gira Faturas, Recibos e Proformas.</p>
        </div>

        <a href="{{ route('admin.pov') }}"
            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition-all flex items-center gap-2 font-medium">
            <i class='bx bx-plus-circle text-xl'></i> Novo Documento
        </a>
    </div>

    {{-- Filtros Avançados --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">

            {{-- Filtro de Data --}}
            <div class="w-full md:w-auto">
                <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Período</label>
                <div class="flex items-center bg-slate-50 border border-slate-300 rounded-lg p-1">
                    <input type="date" wire:model.live="start_date"
                        class="bg-transparent border-none text-sm p-1.5 focus:ring-0 text-slate-700">
                    <span class="text-slate-400 px-1">➜</span>
                    <input type="date" wire:model.live="end_date"
                        class="bg-transparent border-none text-sm p-1.5 focus:ring-0 text-slate-700">
                </div>
            </div>

            {{-- Filtro de Tipo --}}
            <div class="w-full md:w-48">
                <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Tipo de Doc.</label>
                <select wire:model.live="filtro_tipo"
                    class="w-full bg-slate-50 border border-slate-300 rounded-lg text-sm p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    <option value="todos">Todos os Tipos</option>
                    <option value="FT">Faturas (FT)</option>
                    <option value="FR">Faturas-Recibo (FR)</option>
                    <option value="FP">Proformas (FP)</option>
                </select>
            </div>

            {{-- Indicadores Rápidos (Opcional, só texto) --}}
            <div class="hidden md:flex flex-1 justify-end gap-6 text-sm text-slate-500 pb-2">
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div> Faturas
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Fatura-Recibo
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-amber-500"></div> Proforma
                </div>
            </div>
        </div>
    </div>

    {{-- Estatísticas (Atualizam conforme filtro, Proformas excluídas do total "financeiro" no modo "Todos") --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Qtd Documentos -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs text-slate-500 font-bold uppercase">Total Emitido</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalFaturas }}</p>
            <p class="text-xs text-slate-400 mt-1">no período</p>
        </div>

        <!-- Subtotal -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs text-emerald-600 font-bold uppercase">Volume de Negócios</p>
            <p class="text-2xl font-bold text-emerald-700 mt-1">{{ number_format($somaSubtotal, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">Base tributável</p>
        </div>

        <!-- IVA -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs text-amber-600 font-bold uppercase">IVA Total</p>
            <p class="text-2xl font-bold text-amber-700 mt-1">{{ number_format($somaImpostos, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">Imposto liquidado</p>
        </div>

        <!-- Total -->
        <div
            class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow bg-gradient-to-br from-white to-blue-50">
            <p class="text-xs text-blue-600 font-bold uppercase">Faturação Total</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ number_format($somaTotal, 2, ',', '.') }} <small
                    class="text-sm font-normal">KZ</small></p>
            @if($filtro_tipo === 'todos') <p class="text-[10px] text-blue-400 mt-1">(Exclui Proformas)</p> @endif
        </div>
    </div>

    {{-- TABELA DE DADOS --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-left tracking-wider">Documento</th>
                        <th class="px-6 py-4 text-left tracking-wider">Entidade / Cliente</th>
                        <th class="px-6 py-4 text-right tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-center tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($faturas as $fatura)
                    <tr
                        class="hover:bg-slate-50 transition-colors group {{ $fatura->tipo_documento === 'FP' ? 'bg-amber-50/20' : '' }}">

                        <!-- Coluna 1: Tipo Badge -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($fatura->tipo_documento)
                            @case('FT')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                <i class='bx bx-file'></i> FT
                            </span>
                            @break
                            @case('FR')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <i class='bx bx-check-circle'></i> FR
                            </span>
                            @break
                            @case('FP')
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                <i class='bx bx-time-five'></i> FP
                            </span>
                            @break
                            @endswitch
                        </td>

                        <!-- Coluna 2: Dados Doc -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span
                                    class="text-sm font-bold text-slate-800 {{ $fatura->retificada ? 'line-through text-slate-400' : '' }}">{{
                                    $fatura->numero }}</span>
                                <span class="text-xs text-slate-500">{{ $fatura->data_emissao->format('d/m/Y') }}</span>

                                @if($fatura->retificada && $fatura->faturaRetificacao)
                                <div
                                    class="flex items-center gap-1 mt-1 text-[10px] text-amber-600 bg-amber-50 px-1 py-0.5 rounded w-fit">
                                    <i class='bx bx-revision'></i> Corrigido por: {{ $fatura->faturaRetificacao->numero
                                    }}
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Coluna 3: Cliente -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-700">{{ Str::limit($fatura->cliente->nome ??
                                    'Consumidor Final', 20) }}</span>
                                <span class="text-xs text-slate-400">NIF: {{ $fatura->cliente->nif ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Coluna 4: Totais -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span
                                class="block text-sm font-bold text-slate-700 {{ $fatura->retificada || $fatura->anulada ? 'line-through text-slate-400' : '' }}">
                                {{ number_format($fatura->total, 2, ',', '.') }} KZ
                            </span>
                            @if(!$fatura->anulada && !$fatura->retificada && $fatura->tipo_documento !== 'FP')
                            <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1.5 rounded">Válido</span>
                            @endif
                        </td>

                        <!-- Coluna 5: Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($fatura->anulada)
                            <span
                                class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded border border-red-200">ANULADA</span>
                            @elseif($fatura->retificada)
                            <span
                                class="text-xs font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded border border-amber-200">RETIFICADA</span>
                            @elseif($fatura->convertida)
                            <span
                                class="text-xs font-bold text-purple-600 bg-purple-100 px-2 py-1 rounded border border-purple-200">CONVERTIDA</span>
                            @else
                            <span
                                class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                {{ $fatura->status }}
                                <!-- Usa o accessor da model -->
                            </span>
                            @endif
                        </td>

                        <!-- Coluna 6: Ações -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Ver PDF -->
                                <a href="{{ route('admin.faturas.show', $fatura->id) }}" target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors"
                                    title="Visualizar">
                                    <i class='bx bx-show text-lg'></i>
                                </a>

                                <!-- Converter Proforma -->
                                @if($fatura->tipo_documento === 'FP' && !$fatura->convertida)
                                <button wire:click="converterProforma({{ $fatura->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white transition-colors"
                                    title="Converter em Fatura">
                                    <i class='bx bx-transfer-alt text-lg'></i>
                                </button>
                                @endif

                                <!-- Retificar (Se possível) -->
                                @if($fatura->pode_ser_retificada)
                                <a href="{{ route('admin.pov') }}?retificar_id={{ $fatura->id }}&tipo={{ $fatura->tipo_documento }}"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-colors"
                                    title="Retificar">
                                    <i class='bx bx-revision text-lg'></i>
                                </a>
                                @endif

                                <!-- Anular (Se possível) -->
                                @if($fatura->pode_ser_anulada)
                                <button wire:click="delete({{ $fatura->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors"
                                    title="Anular">
                                    <i class='bx bx-trash text-lg'></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <i class='bx bx-search-alt text-4xl mb-3'></i>
                            <p class="text-sm font-medium">Nenhum documento encontrado.</p>
                            <p class="text-xs">Tente ajustar os filtros.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="bg-white border-t border-slate-200 px-6 py-4">
            {{ $faturas->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Integração do modal via eventos do Livewire
    Livewire.on('abrirModalAnulacao', (event) => {
        window.dispatchEvent(new CustomEvent('abrir-modal-anulacao', { detail: event }));
    });
</script>
@endpush