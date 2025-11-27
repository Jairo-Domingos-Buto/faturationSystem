<div class="min-h-screen p-6 bg-slate-50 font-sans" x-data="{ activeTab: 'all' }">

    {{-- Cabeçalho --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bx-file-find text-indigo-600'></i> Notas de Crédito
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Histórico consolidado de anulações e retificações fiscais.
            </p>
        </div>

        {{-- Filtro de Data --}}
        <div class="flex items-center bg-white border border-slate-200 rounded-xl shadow-sm p-1.5">
            <div class="relative">
                <input type="date" wire:model.live="start_date"
                    class="border-none text-sm text-slate-600 focus:ring-0 bg-transparent cursor-pointer py-1">
            </div>
            <span class="text-slate-300 px-2"><i class='bx bx-right-arrow-alt'></i></span>
            <div class="relative">
                <input type="date" wire:model.live="end_date"
                    class="border-none text-sm text-slate-600 focus:ring-0 bg-transparent cursor-pointer py-1">
            </div>
        </div>
    </div>

    {{-- Feedback de Sessão --}}
    @if(session()->has('message'))
    <div
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex items-center animate-fade-in-down">
        <i class='bx bx-check-circle text-2xl mr-3'></i>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    {{-- Cards de Resumo Financeiro --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Faturas Retificadas -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-10px_rgba(245,158,11,0.2)] hover:border-amber-200 transition-all group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class='bx bx-revision text-8xl text-amber-600'></i>
            </div>
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Faturas Retificadas</span>
                <p class="text-2xl font-extrabold text-slate-800 mt-2">
                    {{ number_format($dados->faturas_retificadas->sum('total'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small>
                </p>
                <div class="mt-3 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase">
                        {{ $dados->faturas_retificadas->count() }} documentos
                    </span>
                </div>
            </div>
        </div>

        <!-- Faturas Anuladas -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-10px_rgba(239,68,68,0.2)] hover:border-red-200 transition-all group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class='bx bx-x-circle text-8xl text-red-600'></i>
            </div>
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Faturas Anuladas</span>
                <p class="text-2xl font-extrabold text-slate-800 mt-2">
                    {{ number_format($dados->faturas_anuladas->sum('total'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small>
                </p>
                <div class="mt-3 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">
                        {{ $dados->faturas_anuladas->count() }} documentos
                    </span>
                </div>
            </div>
        </div>

        <!-- Recibos Retificados -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-10px_rgba(59,130,246,0.2)] hover:border-blue-200 transition-all group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class='bx bx-receipt text-8xl text-blue-600'></i>
            </div>
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Recibos Retificados</span>
                <p class="text-2xl font-extrabold text-slate-800 mt-2">
                    {{ number_format($dados->recibos_retificados->sum('valor'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small>
                </p>
                <div class="mt-3 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                        {{ $dados->recibos_retificados->count() }} documentos
                    </span>
                </div>
            </div>
        </div>

        <!-- Recibos Anulados -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_4px_20px_-10px_rgba(107,114,128,0.2)] hover:border-slate-300 transition-all group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class='bx bx-block text-8xl text-slate-600'></i>
            </div>
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Recibos Anulados</span>
                <p class="text-2xl font-extrabold text-slate-800 mt-2">
                    {{ number_format($dados->recibos_anulados->sum('valor'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small>
                </p>
                <div class="mt-3 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                        {{ $dados->recibos_anulados->count() }} documentos
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Abas de Navegação --}}
    <div class="mb-6 overflow-x-auto pb-2">
        <div class="flex space-x-2 min-w-max p-1 bg-slate-200/50 rounded-xl w-fit">
            @php
            $tabs = [
            'all' => ['label' => 'Visão Geral', 'icon' => 'bx-layer'],
            'retificadas' => ['label' => 'Faturas Retificadas', 'icon' => 'bx-revision'],
            'anuladas' => ['label' => 'Faturas Anuladas', 'icon' => 'bx-x-circle'],
            'recibos-retificados' => ['label' => 'Recibos Retif.', 'icon' => 'bx-receipt'],
            'recibos-anulados' => ['label' => 'Recibos Anul.', 'icon' => 'bx-block'],
            ];
            @endphp

            @foreach($tabs as $key => $tab)
            <button @click="activeTab = '{{ $key }}'" type="button" :class="activeTab === '{{ $key }}' 
                    ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5 font-bold' 
                    : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 font-medium'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm transition-all duration-200">
                <i class='bx icon text-lg'></i>
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Tabela Unificada --}}
    <div wire:loading.remove class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full whitespace-nowrap text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Doc. Origem</th>
                        <th class="px-6 py-4">Doc. Novo</th>
                        <th class="px-6 py-4">Entidade</th>
                        <th class="px-6 py-4">Detalhes</th>
                        <th class="px-6 py-4 text-right">Valor Afetado</th>
                        <th class="px-6 py-4 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    {{-- 1. FATURAS RETIFICADAS --}}
                    @foreach ($dados->faturas_retificadas as $item)
                    <tr class="hover:bg-amber-50/40 transition-colors group"
                        x-show="activeTab === 'all' || activeTab === 'retificadas'" x-transition>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                <i class='bx bx-revision'></i> FATURA RETIF.
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->faturaRetificacao)
                            <span class="font-bold text-emerald-600">{{ $item->faturaRetificacao->numero }}</span>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700">{{ Str::limit($item->cliente->nome ?? 'N/A', 15)
                                    }}</span>
                                <span class="text-xs text-slate-400">NIF: {{ $item->cliente->nif ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500 space-y-1">
                                <div class="flex items-center gap-1"><i class='bx bx-calendar'></i> {{
                                    $item->data_retificacao ? $item->data_retificacao->format('d/m/Y') : '-' }}</div>
                                <div class="bg-slate-100 px-1.5 py-0.5 rounded w-fit"
                                    title="{{ $item->motivo_retificacao }}">
                                    {{ Str::limit($item->motivo_retificacao ?? 'Sem motivo', 20) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-slate-700">{{ number_format($item->total, 2, ',', '.') }}
                                KZ</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div
                                class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.nota-credito.fatura.view', $item->id) }}" target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors"
                                    title="Visualizar">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.nota-credito.fatura.download', $item->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-slate-100 text-slate-600 hover:bg-slate-600 hover:text-white transition-colors"
                                    title="Download">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 2. FATURAS ANULADAS --}}
                    @foreach ($dados->faturas_anuladas as $item)
                    <tr class="hover:bg-red-50/40 transition-colors group"
                        x-show="activeTab === 'all' || activeTab === 'anuladas'" x-transition>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                <i class='bx bx-x-circle'></i> FATURA ANUL.
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 italic">Anulação Total</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700">{{ Str::limit($item->cliente->nome ?? 'N/A', 15)
                                    }}</span>
                                <span class="text-xs text-slate-400">NIF: {{ $item->cliente->nif ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500 space-y-1">
                                <div class="flex items-center gap-1"><i class='bx bx-calendar'></i> {{
                                    $item->data_anulacao ? $item->data_anulacao->format('d/m/Y') : '-' }}</div>
                                <div class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded w-fit"
                                    title="{{ $item->motivo_anulacao }}">
                                    {{ Str::limit($item->motivo_anulacao ?? 'Sem motivo', 20) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-red-600 line-through">{{ number_format($item->total, 2,
                                ',', '.') }} KZ</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div
                                class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.notas-credito.anulacao', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.notas-credito.anulacao.pdf', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-slate-100 text-slate-600 hover:bg-slate-600 hover:text-white transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 3. RECIBOS RETIFICADOS --}}
                    @foreach ($dados->recibos_retificados as $item)
                    <tr class="hover:bg-blue-50/40 transition-colors group"
                        x-show="activeTab === 'all' || activeTab === 'recibos-retificados'" x-transition>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                <i class='bx bx-revision'></i> RECIBO RETIF.
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->reciboRetificacao)
                            <span class="font-bold text-emerald-600">{{ $item->reciboRetificacao->numero }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700">{{ Str::limit($item->cliente->nome ?? 'N/A', 15)
                                    }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500">
                                <div class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded w-fit"
                                    title="{{ $item->motivo_retificacao }}">
                                    {{ Str::limit($item->motivo_retificacao ?? 'Motivo', 20) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-slate-700">{{ number_format($item->valor, 2, ',', '.') }}
                                KZ</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div
                                class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.nota-credito.recibo.view', $item->id) }}" target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.nota-credito.recibo.download', $item->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-slate-100 text-slate-600 hover:bg-slate-600 hover:text-white transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 4. RECIBOS ANULADOS --}}
                    @foreach ($dados->recibos_anulados as $item)
                    <tr class="hover:bg-slate-100/50 transition-colors group"
                        x-show="activeTab === 'all' || activeTab === 'recibos-anulados'" x-transition>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                <i class='bx bx-block'></i> RECIBO ANUL.
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400 italic">Anulação</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700">{{ Str::limit($item->cliente->nome ?? 'N/A', 15)
                                }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded w-fit"
                                title="{{ $item->motivo_anulacao }}">
                                {{ Str::limit($item->motivo_anulacao ?? 'Motivo', 20) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-red-600 line-through">{{ number_format($item->valor, 2,
                                ',', '.') }} KZ</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div
                                class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.notas-credito.anulacao', ['tipo' => 'recibo', 'id' => $item->id]) }}"
                                    target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.notas-credito.anulacao.pdf', ['tipo' => 'recibo', 'id' => $item->id]) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded bg-slate-100 text-slate-600 hover:bg-slate-600 hover:text-white transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Empty State Global --}}
                    <tr x-show="(
                        (activeTab === 'all' && !{{ $dados->faturas_retificadas->count() + $dados->faturas_anuladas->count() + $dados->recibos_retificados->count() + $dados->recibos_anulados->count() }}) ||
                        (activeTab === 'retificadas' && !{{ $dados->faturas_retificadas->count() }}) ||
                        (activeTab === 'anuladas' && !{{ $dados->faturas_anuladas->count() }}) ||
                        (activeTab === 'recibos-retificados' && !{{ $dados->recibos_retificados->count() }}) ||
                        (activeTab === 'recibos-anulados' && !{{ $dados->recibos_anulados->count() }})
                    )">
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-300">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class='bx bx-folder-open text-5xl opacity-30'></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-500">Sem registos</h3>
                                <p class="text-sm text-slate-400 mt-1">Nenhum documento encontrado neste filtro ou data.
                                </p>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    {{-- CSS Customizado para Scrollbar --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Animação suave para alert */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }
    </style>
</div>