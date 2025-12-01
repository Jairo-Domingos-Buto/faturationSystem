<div>
    {{-- Notificações Toast --}}
    @if (session()->has('success'))
    <div
        class="fixed top-20 right-5 z-50 mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r shadow-lg animate-bounce">
        <div class="flex items-center">
            <i class='bx bx-check-circle text-2xl mr-2'></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div
        class="fixed top-20 right-5 z-50 mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r shadow-lg animate-pulse">
        <div class="flex items-center">
            <i class='bx bx-error-circle text-2xl mr-2'></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="max-w-[1600px] mx-auto p-4 sm:p-6">

        <!-- ✅ Banner de Retificação -->
        @if($modoRetificacao)
        <div
            class="mb-6 p-5 bg-orange-50 border-l-4 border-orange-500 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class='bx bx-revision text-2xl text-orange-600'></i>
                    <h3 class="text-xl font-bold text-orange-800">Modo Retificação</h3>
                </div>
                <p class="text-sm text-orange-700">
                    Alterando documento original: <strong class="font-mono bg-orange-200 px-2 rounded">{{
                        $documentoOriginalNumero }}</strong>
                </p>
                <!-- Campo de Motivo Obrigatório -->
                <div class="mt-3 w-full md:w-[500px]">
                    <textarea wire:model="motivoRetificacao" rows="1"
                        class="w-full text-sm border-orange-300 rounded focus:ring-orange-500 placeholder-orange-400"
                        placeholder="Motivo da retificação (Obrigatório)..."></textarea>
                </div>
            </div>
            <button wire:click="cancelarRetificacao" // Caso tenha implementado esse método ou use um link de volta
                class="px-4 py-2 bg-white text-orange-700 border border-orange-300 rounded hover:bg-orange-100 transition-colors shadow-sm font-medium">
                Cancelar
            </button>
        </div>
        @endif

        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-2">
                    <i class='bx bxs-store-alt text-blue-600'></i>
                    @if($modoRetificacao)
                    Retificação de Documento
                    @else
                    Ponto de Venda
                    @endif
                </h1>
                <p class="text-slate-500 text-sm">Emita Faturas, Recibos e Proformas de forma simples.</p>
            </div>

            <div class="flex gap-2">
                <button wire:click="exportarDadosFatura"
                    class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-lg hover:border-blue-500 hover:text-blue-600 transition-all font-medium flex items-center gap-2 shadow-sm">
                    <i class='bx bx-printer text-lg'></i> Última Venda
                </button>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-6">

            <!-- SEÇÃO ESQUERDA: Configurações e Itens (65-70%) -->
            <div class="flex-1 space-y-5">

                <!-- Cards de Configuração (Top) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Card Documento -->
                    <div
                        class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 hover:border-blue-300 transition-colors">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipo de Documento</label>
                        <select wire:model.live="tipoDocumento" @if($modoRetificacao) disabled @endif
                            class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-medium">
                            <option value="FT">Fatura (FT)</option>
                            <option value="FR">Fatura-Recibo (FR)</option>
                            <option value="FP">Proforma (FP)</option>
                            <option value="RC">Recibo Liquidação (RC)</option>
                        </select>

                        <!-- Condicional: Método de Pagamento (FR ou RC) -->
                        @if(in_array($tipoDocumento, ['FR', 'RC']))
                        <div class="mt-3 animate-fade-in-down">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pagamento</label>
                            <select wire:model="metodoPagamento"
                                class="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:ring-blue-500">
                                <option value="dinheiro">Numérario (Dinheiro)</option>
                                <option value="cartao">Multicaixa / TPA</option>
                                <option value="transferencia">Transferência Bancária</option>
                            </select>
                        </div>
                        @endif

                        <!-- Condicional: Vencimento (FT ou FP) -->
                        @if(in_array($tipoDocumento, ['FT', 'FP']))
                        <div class="mt-3 animate-fade-in-down">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Vencimento</label>
                            <input type="date" wire:model="dataVencimento"
                                class="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-sm focus:ring-blue-500">
                        </div>
                        @endif
                    </div>

                    <!-- Card Natureza (Toggle Produto / Serviço) -->
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Natureza do Item</label>
                        <div class="flex gap-2">
                            <button wire:click="alterarNatureza('produto')"
                                class="flex-1 py-2 rounded-lg font-medium text-sm transition-all {{ $natureza === 'produto' ? 'bg-blue-600 text-white shadow-md ring-2 ring-blue-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class='bx bx-package'></i> Produto
                            </button>
                            <button wire:click="alterarNatureza('servico')"
                                class="flex-1 py-2 rounded-lg font-medium text-sm transition-all {{ $natureza === 'servico' ? 'bg-green-600 text-white shadow-md ring-2 ring-green-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class='bx bx-briefcase-alt-2'></i> Serviço
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 text-center">
                            @if($natureza === 'produto') Exibindo stock e código de barras @else Isento de stock e
                            impostos (M02) @endif
                        </p>
                    </div>

                    <!-- Card Cliente -->
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 relative">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-slate-500 uppercase">Cliente</label>
                            <button wire:click="abrirModal"
                                class="text-blue-600 text-xs font-bold hover:underline">Alterar</button>
                        </div>

                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-3 cursor-pointer hover:bg-blue-100 transition-colors"
                            wire:click="abrirModal">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-blue-700">
                                <i class='bx bxs-user'></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ Str::limit($clienteNome, 18) }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $clienteSelecionado ? 'Cliente Registado' :
                                    'Consumidor Final' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção de Listagem e Carrinho -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col h-[500px]">
                    <!-- Busca -->
                    <div class="p-4 border-b border-slate-100">
                        <div class="relative">
                            <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="search" wire:model.live.debounce.300ms="searchProdutoTerm"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm text-lg placeholder-slate-400"
                                placeholder="{{ $natureza === 'produto' ? 'Pesquise por nome, código de barras...' : 'Pesquise por descrição do serviço...' }}">
                        </div>
                    </div>

                    <div class="flex flex-1 overflow-hidden">

                        <!-- Lista de Resultados (Esquerda - 60%) -->
                        <div
                            class="w-3/5 overflow-y-auto p-4 border-r border-slate-100 custom-scrollbar bg-slate-50/50">
                            <h4
                                class="text-xs font-bold text-slate-400 uppercase mb-3 ml-1 flex justify-between items-center">
                                <span>{{ $natureza === 'produto' ? 'Produtos Disponíveis' : 'Serviços Disponíveis'
                                    }}</span>
                                <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-[10px]">
                                    {{ $natureza === 'produto' ? count($produtos) : count($servicos) }}
                                </span>
                            </h4>

                            {{-- ================= LISTA DE PRODUTOS ================= --}}
                            @if($natureza === 'produto')
                            @if(count($produtos) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($produtos as $produto)
                                <button wire:click="adicionarProduto({{ $produto->id }})"
                                    wire:key="prod-{{$produto->id}}"
                                    class="text-left bg-white p-3 rounded-lg border border-slate-200 shadow-sm hover:border-blue-400 hover:shadow-md transition-all group relative overflow-hidden">

                                    <!-- Ícone de Fundo Decorativo -->
                                    <i
                                        class='bx bx-package absolute -bottom-2 -right-2 text-6xl text-slate-100 group-hover:text-blue-50 transition-colors z-0'></i>

                                    <div class="relative z-10">
                                        <div
                                            class="font-bold text-slate-700 text-sm line-clamp-2 group-hover:text-blue-700 mb-1 flex items-start gap-2">
                                            <i class='bx bx-package text-blue-500 mt-0.5'></i> <!-- ÍCONE DE PRODUTO -->
                                            {{ $produto->descricao }}
                                        </div>

                                        <div class="flex justify-between items-end mt-2">
                                            <div>
                                                <div
                                                    class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded w-fit mb-1 font-mono">
                                                    {{ $produto->codigo_barras ?? 'SEM COD' }}
                                                </div>
                                                <span
                                                    class="text-xs {{ $produto->estoque > 0 ? 'text-green-600 bg-green-50' : 'text-red-500 bg-red-50' }} px-1.5 py-0.5 rounded font-bold border {{ $produto->estoque > 0 ? 'border-green-100' : 'border-red-100' }}">
                                                    {{ $produto->estoque }} un
                                                </span>
                                            </div>
                                            <span class="text-lg font-bold text-slate-800">{{
                                                number_format($produto->preco_venda, 2, ',', '.')
                                                }}</span>
                                        </div>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center h-60 text-slate-400">
                                <i class='bx bx-package text-5xl mb-2 opacity-30'></i>
                                <p class="text-sm">Nenhum produto encontrado.</p>
                            </div>
                            @endif
                            @endif

                            {{-- ================= LISTA DE SERVIÇOS ================= --}}
                            @if($natureza === 'servico')
                            @if(count($servicos) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($servicos as $servico)
                                <button wire:click="adicionarServico({{ $servico->id }})"
                                    wire:key="serv-{{$servico->id}}"
                                    class="text-left bg-white p-3 rounded-lg border border-green-200 shadow-sm hover:border-green-400 hover:shadow-md transition-all group relative overflow-hidden">

                                    <!-- Ícone de Fundo Decorativo -->
                                    <i
                                        class='bx bx-briefcase-alt-2 absolute -bottom-2 -right-2 text-6xl text-green-50 group-hover:text-green-100 transition-colors z-0'></i>

                                    <div class="relative z-10">
                                        <div
                                            class="font-bold text-slate-700 text-sm line-clamp-2 group-hover:text-green-700 mb-2 flex items-start gap-2">
                                            <i class='bx bx-briefcase-alt-2 text-green-500 mt-0.5'></i>
                                            <!-- ÍCONE DE SERVIÇO -->
                                            {{ $servico->descricao }}
                                        </div>

                                        <div class="flex justify-between items-center mt-auto">
                                            <span
                                                class="text-[10px] text-green-600 bg-green-50 px-2 py-1 rounded font-bold uppercase tracking-wide border border-green-100">
                                                SERVIÇO (M02)
                                            </span>
                                            <span class="text-lg font-bold text-slate-800">{{
                                                number_format($servico->valor ??
                                                $servico->preco_venda, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center h-60 text-slate-400">
                                <i class='bx bx-briefcase-alt-2 text-5xl mb-2 opacity-30'></i>
                                <p class="text-sm">Nenhum serviço encontrado.</p>
                            </div>
                            @endif
                            @endif
                        </div>

                        <!-- Carrinho (Direita - 40%) -->
                        <div class="w-2/5 flex flex-col bg-white">
                            <div class="p-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                                <h4 class="text-xs font-bold text-slate-500 uppercase flex items-center gap-2">
                                    <i class='bx bx-cart text-lg'></i> Itens no Carrinho
                                </h4>
                                <span class="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{
                                    count($produtosCarrinho)
                                    }}</span>
                            </div>

                            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                                @forelse($produtosCarrinho as $index => $item)
                                <div
                                    class="bg-white border border-slate-100 rounded-lg p-3 hover:border-blue-200 transition-colors shadow-sm relative group">

                                    {{-- ✅ LÓGICA DO BADGE: Verifica a natureza e troca ícone e cor --}}
                                    <div class="absolute top-2 right-8">
                                        @if(isset($item['natureza']) && $item['natureza'] == 'servico')
                                        <span
                                            class="text-[9px] bg-green-100 text-green-700 border border-green-200 px-1.5 py-0.5 rounded font-bold uppercase flex items-center gap-1">
                                            <i class='bx bx-briefcase-alt-2'></i> SERV
                                        </span>
                                        @else
                                        <span
                                            class="text-[9px] bg-blue-100 text-blue-700 border border-blue-200 px-1.5 py-0.5 rounded font-bold uppercase flex items-center gap-1">
                                            <i class='bx bx-package'></i> PROD
                                        </span>
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-start mb-2 pr-12">
                                        <div>
                                            {{-- ✅ ÍCONE AO LADO DO NOME TAMBÉM --}}
                                            <div class="font-bold text-sm text-slate-700 line-clamp-2 flex items-center gap-1.5"
                                                title="{{ $item['descricao'] }}">
                                                @if(isset($item['natureza']) && $item['natureza'] == 'servico')
                                                <i class='bx bx-briefcase-alt-2 text-green-500'></i>
                                                @else
                                                <i class='bx bx-package text-blue-500'></i>
                                                @endif
                                                {{ $item['descricao'] }}
                                            </div>

                                            <div class="text-[11px] text-slate-500 font-medium pl-5">
                                                {{ number_format($item['preco_venda'], 2, ',', '.') }} KZ <span
                                                    class="text-slate-300">|
                                                    un</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center mt-2 pl-5">
                                        <!-- Controle Quantidade -->
                                        <div class="flex items-center border border-slate-200 rounded bg-slate-50">
                                            <button wire:click="alterarQuantidade({{ $index }}, -1)"
                                                class="w-8 h-8 hover:bg-white text-slate-600 flex items-center justify-center transition-all border-r border-slate-200 rounded-l hover:text-red-500 active:bg-slate-100">
                                                <i class='bx bx-minus text-xs'></i>
                                            </button>

                                            <span
                                                class="w-10 text-center text-sm font-bold text-slate-800 bg-white h-8 flex items-center justify-center">
                                                {{ $item['quantidade'] }}
                                            </span>

                                            <button wire:click="alterarQuantidade({{ $index }}, 1)"
                                                class="w-8 h-8 hover:bg-white text-slate-600 flex items-center justify-center transition-all border-l border-slate-200 rounded-r hover:text-green-500 active:bg-slate-100">
                                                <i class='bx bx-plus text-xs'></i>
                                            </button>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-base font-extrabold text-blue-600 tracking-tight">
                                                {{ number_format($item['preco_venda'] * $item['quantidade'], 2, ',',
                                                '.') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botão Remover -->
                                    <button wire:click="removerProduto({{ $index }})"
                                        class="absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">
                                        <i class='bx bx-trash text-lg'></i>
                                    </button>
                                </div>
                                @empty
                                <div
                                    class="flex flex-col items-center justify-center h-full text-slate-400 text-center p-4">
                                    <div
                                        class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i class='bx bx-cart-alt text-3xl opacity-30'></i>
                                    </div>
                                    <p class="text-sm font-medium">O carrinho está vazio</p>
                                    <p class="text-xs mt-1 opacity-70">Adicione produtos ou serviços.</p>
                                </div>
                                @endforelse
                            </div>

                            {{-- Resumo Rápido dentro do Carrinho --}}
                            @if(count($produtosCarrinho) > 0)
                            <div
                                class="p-3 bg-slate-50 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
                                <span>Itens: <strong>{{ count($produtosCarrinho) }}</strong></span>
                                <span>Qtd Total: <strong>{{ array_sum(array_column($produtosCarrinho, 'quantidade'))
                                        }}</strong></span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO DIREITA: Resumo Financeiro (30-35%) -->
            <div class="w-[400px] flex flex-col gap-4">

                <!-- Painel Resumo -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden sticky top-6">
                    <div class="bg-slate-900 p-5 text-white shadow-md">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <i class='bx bx-calculator'></i> Totais & Pagamento
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">

                        <!-- Linhas de Totais -->
                        <div class="space-y-3 pb-6 border-b border-dashed border-slate-200 text-slate-600 text-sm">
                            <div class="flex justify-between items-center">
                                <span>Subtotal</span>
                                <span class="font-bold text-slate-800">{{ number_format($subtotal, 2, ',', '.') }}
                                    KZ</span>
                            </div>

                            @if(in_array($tipoDocumento, ['FT', 'FR', 'FP']) && $iva > 0)
                            <div class="flex justify-between items-center text-slate-500">
                                <span>Total IVA (14%)</span>
                                <span class="font-medium">{{ number_format($iva, 2, ',', '.') }} KZ</span>
                            </div>
                            @endif

                            @if($desconto > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span>Descontos</span>
                                <span>- {{ number_format($desconto, 2, ',', '.') }} KZ</span>
                            </div>
                            @endif

                            <!-- Exibe se houver isenção (serviços por ex) -->
                            @php
                            $totalIsento = collect($resumoImpostos)->where('taxa', 0)->sum('incidencia');
                            @endphp
                            @if($totalIsento > 0)
                            <div
                                class="flex justify-between items-center text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded">
                                <span>Total Isento (M02/Outros)</span>
                                <span>{{ number_format($totalIsento, 2, ',', '.') }} KZ</span>
                            </div>
                            @endif
                        </div>

                        <!-- TOTAL GRANDÃO -->
                        <div
                            class="flex justify-between items-center bg-blue-50 p-4 rounded-xl border border-blue-100 shadow-sm">
                            <span class="text-blue-900 font-bold uppercase text-xs tracking-wider">A Pagar</span>
                            <span class="text-3xl font-extrabold text-blue-700 tracking-tight">{{ number_format($total,
                                2, ',', '.') }}<span class="text-sm font-bold ml-1 opacity-70">KZ</span></span>
                        </div>

                        <!-- CAMPOS CONDICIONAIS DE PAGAMENTO (Só aparecem para FR e RC) -->
                        @if(in_array($tipoDocumento, ['FR', 'RC']))
                        <div
                            class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 animate-fade-in-up shadow-inner">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">
                                    Valor Entregue ({{ strtoupper($metodoPagamento) }})
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class='bx bx-money text-slate-400'></i>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="totalRecebido"
                                        class="w-full pl-10 pr-12 py-3 border border-slate-300 rounded-lg text-lg font-bold text-slate-800 focus:border-green-500 focus:ring-1 focus:ring-green-500 text-right transition-all bg-white"
                                        placeholder="0,00">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">KZ</span>
                                </div>
                            </div>

                            @if($metodoPagamento === 'dinheiro')
                            <div class="flex justify-between items-center pt-2 mt-2 border-t border-slate-200">
                                <span class="text-sm font-bold text-slate-500 uppercase">Troco</span>
                                <span
                                    class="text-xl font-extrabold {{ $troco > 0 ? 'text-green-600' : 'text-slate-400' }}">
                                    {{ number_format($troco, 2, ',', '.') }} KZ
                                </span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Botão Finalizar -->
                        <button wire:click="finalizarVenda" wire:loading.attr="disabled"
                            class="w-full py-4 rounded-xl font-bold text-white text-lg shadow-lg transform active:scale-95 transition-all
                            {{ $modoRetificacao 
                                ? 'bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 shadow-orange-500/30' 
                                : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-blue-500/30' }}">

                            <span wire:loading.remove class="flex items-center justify-center gap-2">
                                @if($modoRetificacao)
                                <i class='bx bx-revision text-xl'></i> Finalizar Retificação
                                @else
                                @switch($tipoDocumento)
                                @case('FR') <i class='bx bx-check-double text-2xl'></i> Emitir Fatura-Recibo @break
                                @case('FT') <i class='bx bx-file text-2xl'></i> Emitir Fatura @break
                                @case('FP') <i class='bx bx-time-five text-2xl'></i> Criar Proforma @break
                                @case('RC') <i class='bx bx-receipt text-2xl'></i> Gerar Recibo @break
                                @default <i class='bx bx-check text-2xl'></i> Finalizar
                                @endswitch
                                @endif
                            </span>

                            <span wire:loading class="flex items-center justify-center gap-2">
                                <i class='bx bx-loader-alt animate-spin text-xl'></i> Processando...
                            </span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Seleção de Clientes -->
    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-[60] transition-all p-4"
        aria-modal="true" role="dialog">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[85vh] animate-fade-in-up">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-white sticky top-0 z-10">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600"><i class='bx bx-user-pin'></i></div>
                    Selecionar Cliente
                </h2>
                <button wire:click="fecharModal"
                    class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full p-2 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Modal Search -->
            <div class="p-4 bg-slate-50 border-b border-slate-100">
                <div class="relative group">
                    <i
                        class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-blue-500'></i>
                    <input type="text" wire:model.live.debounce.300ms="searchClienteTerm" autofocus
                        class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all shadow-sm"
                        placeholder="Pesquisar por Nome, NIF ou Telefone...">
                </div>
            </div>

            <!-- Modal List -->
            <div class="overflow-y-auto flex-1 p-0 bg-white custom-scrollbar">
                @forelse($clientes as $cliente)
                <div wire:click="selecionarCliente({{ $cliente->id }})"
                    class="p-4 border-b border-slate-50 hover:bg-blue-50 cursor-pointer transition-colors duration-150 flex justify-between items-center group">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center group-hover:bg-blue-200 group-hover:text-blue-700 transition-colors uppercase border border-slate-200">
                            {{ substr($cliente->nome, 0, 2) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors">{{
                                $cliente->nome }}</p>
                            <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-500 mt-0.5">
                                <span class="flex items-center gap-1"><i class='bx bx-id-card'></i> {{ $cliente->nif
                                    }}</span>
                                @if($cliente->telefone)
                                <span class="flex items-center gap-1"><i class='bx bx-phone'></i> {{ $cliente->telefone
                                    }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <i
                        class='bx bx-chevron-right text-slate-300 group-hover:text-blue-500 text-xl transform group-hover:translate-x-1 transition-transform'></i>
                </div>
                @empty
                <div class="py-12 text-center text-slate-400 flex flex-col items-center">
                    <div class="bg-slate-50 p-4 rounded-full mb-3">
                        <i class='bx bx-user-x text-4xl opacity-50'></i>
                    </div>
                    <p class="font-medium">Nenhum cliente encontrado</p>
                    <p class="text-xs mt-1">Tente pesquisar por outro termo.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }

        @keyframes pulse-light {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
</div>