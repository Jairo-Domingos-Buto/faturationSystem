<div class="min-h-screen p-4 sm:p-6 font-sans" x-data="{ activeTab: 'all' }">

    {{-- Header e Filtros --}}
    <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bx-file-find text-indigo-600'></i> Notas de Crédito
            </h1>
            <p class="text-sm text-slate-500 mt-1">Gerencie documentos retificados e anulados (Faturas e Recibos)</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
            {{-- Search Bar (Opcional - Adicione wire:model se tiver busca no backend)
            <div class="relative group w-full sm:w-64">
                <i
                    class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500'></i>
                <input type="text" placeholder="Buscar cliente ou nº..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all">
            </div>
            --}}
            {{-- Date Range --}}
            <div class="flex items-center bg-white border border-slate-200 rounded-xl shadow-sm p-1 w-full sm:w-auto">
                <div class="relative">
                    <input type="date" wire:model.live="start_date"
                        class="border-none text-xs sm:text-sm text-slate-600 focus:ring-0 bg-transparent p-1.5 cursor-pointer">
                </div>
                <span class="text-slate-300 px-2">
                    <i class='bx bx-right-arrow-alt'></i>
                </span>
                <div class="relative">
                    <input type="date" wire:model.live="end_date"
                        class="border-none text-xs sm:text-sm text-slate-600 focus:ring-0 bg-transparent p-1.5 cursor-pointer">
                </div>
            </div>
        </div>
    </div>

    {{-- Cards de Estatísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Faturas Retificadas -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class='bx bx-revision text-6xl text-amber-600'></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Faturas Retificadas</span>
                <span class="text-2xl font-extrabold text-slate-800">{{
                    number_format($dados->faturas_retificadas->sum('total'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small></span>
                <div class="mt-2 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">
                        {{ $dados->faturas_retificadas->count() }} docs
                    </span>
                </div>
            </div>
        </div>

        <!-- Faturas Anuladas -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class='bx bx-x-circle text-6xl text-red-600'></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Faturas Anuladas</span>
                <span class="text-2xl font-extrabold text-slate-800">{{
                    number_format($dados->faturas_anuladas->sum('total'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small></span>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-md bg-red-50 text-red-600 text-xs font-bold border border-red-100">
                        {{ $dados->faturas_anuladas->count() }} docs
                    </span>
                </div>
            </div>
        </div>

        <!-- Recibos Retificados -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class='bx bx-receipt text-6xl text-blue-600'></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Recibos Retificados</span>
                <span class="text-2xl font-extrabold text-slate-800">{{
                    number_format($dados->recibos_retificados->sum('valor'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small></span>
                <div class="mt-2 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">
                        {{ $dados->recibos_retificados->count() }} docs
                    </span>
                </div>
            </div>
        </div>

        <!-- Recibos Anulados -->
        <div
            class="bg-white p-5 rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class='bx bx-block text-6xl text-slate-600'></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Recibos Anulados</span>
                <span class="text-2xl font-extrabold text-slate-800">{{
                    number_format($dados->recibos_anulados->sum('valor'), 2, ',', '.') }} <small
                        class="text-xs text-slate-400 font-normal">KZ</small></span>
                <div class="mt-2 flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                        {{ $dados->recibos_anulados->count() }} docs
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Abas de Navegação --}}
    {{-- Abas de Navegação --}}
    <div class="mb-6 overflow-x-auto pb-2">
        <div class="flex space-x-2 min-w-max p-1 bg-slate-100/80 rounded-xl">
            @php
            $tabs = [
            'all' => [
            'label' => 'Todos',
            'icon' => 'bx-layer'
            ],
            'retificadas' => [
            'label' => 'Faturas Retificadas',
            'icon' => 'bx-revision'
            ],
            'anuladas' => [
            'label' => 'Faturas Anuladas',
            'icon' => 'bx-x-circle'
            ],
            'recibos-retificados' => [
            'label' => 'Recibos Retificados',
            'icon' => 'bx-receipt'
            ],
            'recibos-anulados' => [
            'label' => 'Recibos Anulados',
            'icon' => 'bx-block'
            ],
            ];
            @endphp

            @foreach($tabs as $key => $tab)
            <button @click="activeTab = '{{ $key }}'" type="button" :class="activeTab === '{{ $key }}' 
                    ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' 
                    : 'text-slate-500 hover:text-slate-700 hover:bg-white/60'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
                {{-- O erro estava provavelmente aqui. Adicionei o '??' para evitar crash --}}
                <i class='bx {{ $tab[' icon'] ?? 'bx-error' }} text-lg'></i>
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading class="w-full">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center animate-pulse">
            <div class="h-12 w-12 bg-slate-200 rounded-full mx-auto mb-4"></div>
            <div class="h-4 bg-slate-200 rounded w-1/4 mx-auto mb-2"></div>
            <div class="h-3 bg-slate-200 rounded w-1/6 mx-auto"></div>
        </div>
    </div>

    {{-- Tabela --}}
    <div wire:loading.remove class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full whitespace-nowrap text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tipo de Nota</th>
                        <th class="px-6 py-4 font-semibold">Documentos</th>
                        <th class="px-6 py-4 font-semibold">Cliente</th>
                        <th class="px-6 py-4 font-semibold">Detalhes</th>
                        <th class="px-6 py-4 font-semibold">Motivo</th>
                        <th class="px-6 py-4 font-semibold text-right">Valor</th>
                        <th class="px-6 py-4 font-semibold text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    {{-- 1. FATURAS RETIFICADAS --}}
                    @foreach ($dados->faturas_retificadas as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors"
                        x-show="activeTab === 'all' || activeTab === 'retificadas'" x-transition>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <i class='bx bx-revision text-xl'></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-700">Retificação</span>
                                    <span class="text-xs text-slate-500">Fatura</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-slate-400">De:</span>
                                <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                                <span class="text-xs text-slate-400">Para:</span>
                                <span class="font-mono text-xs font-bold text-emerald-600">
                                    {{ $item->faturaRetificacao->numero ?? '---' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($item->cliente->nome ?? 'C', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700">{{ Str::limit($item->cliente->nome ??
                                        'N/A', 20) }}</span>
                                    <span class="text-xs text-slate-400">NIF: {{ $item->cliente->nif ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500">
                                <div class="flex items-center gap-1 mb-1">
                                    <i class='bx bx-calendar'></i> {{ $item->data_retificacao ?
                                    $item->data_retificacao->format('d/m/Y') : '-' }}
                                </div>
                                <div class="flex items-center gap-1" title="Operador">
                                    <i class='bx bx-user'></i> {{ $item->user->name ?? 'Sistema' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-block max-w-[150px] truncate text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded"
                                title="{{ $item->motivo_retificacao }}">
                                {{ $item->motivo_retificacao ?? 'Não informado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-slate-700">{{ number_format($item->total, 2, ',', '.') }}
                                KZ</span>
                            @if($item->faturaRetificacao && $item->faturaRetificacao->total != $item->total)
                            <span class="text-xs text-emerald-600">Nova: {{
                                number_format($item->faturaRetificacao->total, 2, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.nota-credito.fatura.view', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    target="_blank"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.nota-credito.fatura.download', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 2. FATURAS ANULADAS --}}
                    @foreach ($dados->faturas_anuladas as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors bg-red-50/30"
                        x-show="activeTab === 'all' || activeTab === 'anuladas'" x-transition>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                    <i class='bx bx-x-circle text-xl'></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-700">Anulação</span>
                                    <span class="text-xs text-slate-500">Fatura</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($item->cliente->nome ?? 'C', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700">{{ Str::limit($item->cliente->nome ??
                                        'N/A', 20) }}</span>
                                    <span class="text-xs text-slate-400">NIF: {{ $item->cliente->nif ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500">
                                <div class="flex items-center gap-1 mb-1">
                                    <i class='bx bx-calendar'></i> {{ $item->data_anulacao ?
                                    $item->data_anulacao->format('d/m/Y') : '-' }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class='bx bx-user'></i> {{ $item->anuladaPor->name ?? 'Sistema' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-block max-w-[150px] truncate text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded"
                                title="{{ $item->motivo_anulacao }}">
                                {{ $item->motivo_anulacao ?? 'Não informado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-red-600 line-through">{{ number_format($item->total, 2,
                                ',', '.') }} KZ</span>
                            <span class="text-[10px] text-slate-400">Estorno total</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.notas-credito.anulacao', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    target="_blank"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.notas-credito.anulacao.pdf', ['tipo' => 'fatura', 'id' => $item->id]) }}"
                                    class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 3. RECIBOS RETIFICADOS --}}
                    @foreach ($dados->recibos_retificados as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors"
                        x-show="activeTab === 'all' || activeTab === 'recibos-retificados'" x-transition>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class='bx bx-receipt text-xl'></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-700">Retificação</span>
                                    <span class="text-xs text-slate-500">Recibo</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-slate-400">De:</span>
                                <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                                <span class="text-xs text-slate-400">Para:</span>
                                <span class="font-mono text-xs font-bold text-emerald-600">
                                    {{ $item->reciboRetificacao->numero ?? '---' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($item->cliente->nome ?? 'C', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700">{{ Str::limit($item->cliente->nome ??
                                        'N/A', 20) }}</span>
                                    <span class="text-xs text-slate-400">NIF: {{ $item->cliente->nif ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500">
                                <div class="flex items-center gap-1 mb-1">
                                    <i class='bx bx-calendar'></i> {{ $item->data_retificacao ?
                                    $item->data_retificacao->format('d/m/Y') : '-' }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class='bx bx-user'></i> {{ $item->user->name ?? 'Sistema' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-block max-w-[150px] truncate text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded"
                                title="{{ $item->motivo_retificacao }}">
                                {{ $item->motivo_retificacao ?? 'Não informado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-slate-700">{{ number_format($item->valor, 2, ',', '.') }}
                                KZ</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.nota-credito.recibo.view', $item->id) }}" target="_blank"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.nota-credito.recibo.download', $item->id) }}"
                                    class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 4. RECIBOS ANULADOS --}}
                    @foreach ($dados->recibos_anulados as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors bg-red-50/30"
                        x-show="activeTab === 'all' || activeTab === 'recibos-anulados'" x-transition>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center">
                                    <i class='bx bx-block text-xl'></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-700">Anulação</span>
                                    <span class="text-xs text-slate-500">Recibo</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-red-500 line-through">{{ $item->numero }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($item->cliente->nome ?? 'C', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700">{{ Str::limit($item->cliente->nome ??
                                        'N/A', 20) }}</span>
                                    <span class="text-xs text-slate-400">NIF: {{ $item->cliente->nif ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500">
                                <div class="flex items-center gap-1 mb-1">
                                    <i class='bx bx-calendar'></i> {{ $item->data_anulacao ?
                                    $item->data_anulacao->format('d/m/Y') : '-' }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class='bx bx-user'></i> {{ $item->anuladoPor->name ?? 'Sistema' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-block max-w-[150px] truncate text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded"
                                title="{{ $item->motivo_anulacao }}">
                                {{ $item->motivo_anulacao ?? 'Não informado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="block font-bold text-red-600 line-through">{{ number_format($item->valor, 2,
                                ',', '.') }} KZ</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.notas-credito.anulacao', ['tipo' => 'recibo', 'id' => $item->id]) }}"
                                    target="_blank"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                                <a href="{{ route('admin.notas-credito.anulacao.pdf', ['tipo' => 'recibo', 'id' => $item->id]) }}"
                                    class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                    <i class='bx bx-download text-lg'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Empty State --}}
                    <tr x-show="
                        (activeTab === 'all' && !{{ $dados->faturas_retificadas->count() }} && !{{ $dados->faturas_anuladas->count() }} && !{{ $dados->recibos_retificados->count() }} && !{{ $dados->recibos_anulados->count() }}) ||
                        (activeTab === 'retificadas' && !{{ $dados->faturas_retificadas->count() }}) ||
                        (activeTab === 'anuladas' && !{{ $dados->faturas_anuladas->count() }}) ||
                        (activeTab === 'recibos-retificados' && !{{ $dados->recibos_retificados->count() }}) ||
                        (activeTab === 'recibos-anulados' && !{{ $dados->recibos_anulados->count() }})
                    ">
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class='bx bx-search-alt text-3xl opacity-50'></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-600">Nenhum documento encontrado</h3>
                                <p class="text-sm">Ajuste os filtros ou selecione outra aba.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

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
    </style>
</div>