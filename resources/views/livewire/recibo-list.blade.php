<div class="bg-slate-50 p-6 min-h-screen">

    {{-- ✅ MODAL DE ANULAÇÃO --}}
    <x-modal-anulacao />

    {{-- Alerta Configuração --}}
    @if(!$empresa)
    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-r shadow-sm flex items-start">
        <i class='bx bx-error text-2xl text-amber-500 mr-3'></i>
        <div>
            <p class="text-sm text-amber-700 font-medium">Atenção: Dados da empresa ausentes.</p>
            <a href="{{ route('admin.configuracoes') }}"
                class="text-xs text-amber-600 underline hover:text-amber-800">Configurar agora</a>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bx-receipt text-emerald-600'></i> Gestão de Recibos
            </h2>
            <p class="text-sm text-slate-500 mt-1">Recibos de liquidação ativos (Pagamentos posteriores).</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.notas-credito') }}"
                class="px-4 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 font-medium flex items-center gap-2 transition shadow-sm">
                <i class='bx bx-history text-lg'></i> Histórico NC
            </a>
            <a href="{{ route('admin.pov') }}"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-md transition flex items-center gap-2 font-medium">
                <i class='bx bx-plus-circle text-xl'></i> Emitir Recibo
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Período de Emissão</label>
                <div class="flex items-center bg-slate-50 border border-slate-300 rounded-lg p-1">
                    <input type="date" wire:model.live="start_date"
                        class="bg-transparent border-none text-sm p-1.5 focus:ring-0 text-slate-700 cursor-pointer">
                    <span class="text-slate-400 px-1">➜</span>
                    <input type="date" wire:model.live="end_date"
                        class="bg-transparent border-none text-sm p-1.5 focus:ring-0 text-slate-700 cursor-pointer">
                </div>
            </div>
        </div>
    </div>

    {{-- Cards Estatísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Qtd -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-xs text-slate-500 font-bold uppercase">Total Emitido</p>
            <div class="flex items-center justify-between mt-1">
                <p class="text-2xl font-bold text-slate-800">{{ $totalRecibos }}</p>
                <div class="p-2 bg-slate-100 rounded-full text-slate-500"><i class='bx bx-file'></i></div>
            </div>
        </div>

        <!-- Valor Total -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
            <p class="text-xs text-emerald-600 font-bold uppercase">Volume Recebido</p>
            <div class="flex items-center justify-between mt-1">
                <p class="text-2xl font-bold text-emerald-700">{{ number_format($somaValores, 2, ',', '.') }}
                    <small>KZ</small>
                </p>
                <div class="p-2 bg-emerald-50 rounded-full text-emerald-600"><i class='bx bx-money'></i></div>
            </div>
        </div>

        <!-- Métodos -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm lg:col-span-2">
            <p class="text-xs text-slate-500 font-bold uppercase mb-2">Métodos de Pagamento</p>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <span class="text-sm text-slate-600">Dinheiro: <strong>{{ $recibosDinheiro }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    <span class="text-sm text-slate-600">Multicaixa: <strong>{{ $recibosMulticaixa }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span class="text-sm text-slate-600">Transf: <strong>{{ $recibosTransf }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left tracking-wider">Número / Data</th>
                        <th class="px-6 py-4 text-left tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-left tracking-wider">Pagamento</th>
                        <th class="px-6 py-4 text-right tracking-wider">Valor Total</th>
                        <th class="px-6 py-4 text-left tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-center tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($recibos as $recibo)
                    <tr class="hover:bg-slate-50 transition-colors">

                        <!-- Doc e Data -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">{{ $recibo->numero }}</span>
                                <span class="text-xs text-slate-500">{{ $recibo->data_emissao->format('d/m/Y') }}</span>
                            </div>
                        </td>

                        <!-- Cliente -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-700">{{ Str::limit($recibo->cliente->nome ??
                                    'Consumidor Final', 20) }}</span>
                                <span class="text-xs text-slate-400">NIF: {{ $recibo->cliente->nif ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Metodo Pagamento -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($recibo->metodo_pagamento)
                            @case('dinheiro')
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <i class='bx bx-money'></i> Dinheiro
                            </span>
                            @break
                            @case('cartao')
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                <i class='bx bx-credit-card'></i> TPA
                            </span>
                            @break
                            @default
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <i class='bx bx-transfer'></i> Transf.
                            </span>
                            @endswitch
                        </td>

                        <!-- Valor -->
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="font-bold text-slate-700 block">{{ number_format($recibo->valor, 2, ',', '.')
                                }} KZ</span>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded border border-green-200">
                                VÁLIDO
                            </span>
                        </td>

                        <!-- Ações -->
                        <!-- Coluna de Ações -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">


                                <a href="{{ route('admin.recibo.show', $recibo->id) }}" target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-200 shadow-sm"
                                    title="Visualizar Recibo">
                                    <i class='bx bx-show text-lg'></i>
                                </a>


                                @if(!$recibo->anulado && !$recibo->retificado)
                                <a href="{{ route('admin.pov') }}?retificar_id={{ $recibo->id }}&tipo=recibo"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all duration-200 shadow-sm"
                                    title="Retificar">
                                    <i class='bx bx-revision text-lg'></i>
                                </a>
                                @endif

                                {{-- 3. BOTÃO ANULAR (VERMELHO) --}}
                                {{-- Aciona o método no Livewire que abre o modal --}}
                                @if(!$recibo->anulado && !$recibo->retificado)
                                <button wire:click="confirmarAnulacao({{ $recibo->id }})" wire:loading.attr="disabled"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-200 shadow-sm"
                                    title="Anular">
                                    <i class='bx bx-trash text-lg'></i>
                                </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <i class='bx bx-receipt text-4xl mb-3'></i>
                            <p class="text-sm font-medium">Nenhum recibo encontrado no período.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="bg-white border-t border-slate-200 px-6 py-4">
            {{ $recibos->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listener Global
    Livewire.on('abrirModalAnulacao', (event) => {
        window.dispatchEvent(new CustomEvent('abrir-modal-anulacao', { detail: event }));
    });
</script>
@endpush