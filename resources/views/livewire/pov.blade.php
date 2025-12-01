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
            <button wire:click="cancelarRetificacao"
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

            <!-- SEÇÃO ESQUERDA: Configurações e Produtos (65-70%) -->
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

                    <!-- Card Natureza -->
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Natureza do Item</label>
                        <div class="flex gap-2">
                            <button wire:click="alterarNatureza('produto')"
                                class="flex-1 py-2 rounded-lg font-medium text-sm transition-all {{ $natureza === 'produto' ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class='bx bx-package'></i> Produto
                            </button>
                            <button wire:click="alterarNatureza('servico')"
                                class="flex-1 py-2 rounded-lg font-medium text-sm transition-all {{ $natureza === 'servico' ? 'bg-green-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                <i class='bx bx-briefcase-alt-2'></i> Serviço
                            </button>
                        </div>
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

                <!-- Seção de Produtos (Busca e Lista) -->
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col h-[500px]">
                    <!-- Busca -->
                    <div class="p-4 border-b border-slate-100">
                        <div class="relative">
                            <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                            <input type="search" wire:model.live.debounce.300ms="searchProdutoTerm"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm text-lg placeholder-slate-400"
                                placeholder="Pesquise por nome, código ou referência...">
                        </div>
                    </div>

                    <div class="flex flex-1 overflow-hidden">
                        <!-- Lista de Resultados (Esquerda - 60%) -->
                        <div
                            class="w-3/5 overflow-y-auto p-4 border-r border-slate-100 custom-scrollbar bg-slate-50/50">
                            <h4 class="text-xs font-bold text-slate-400 uppercase mb-3 ml-1">
                                @if($natureza === 'produto')
                                Produtos Disponíveis ({{ count($produtos) }})
                                @else
                                Serviços Disponíveis ({{ count($servicos) }})
                                @endif
                            </h4>

                            <!-- LISTAGEM DE PRODUTOS -->
                            @if($natureza === 'produto')
                            @if(count($produtos) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($produtos as $produto)
                                <button wire:click="adicionarProduto({{ $produto->id }})"
                                    wire:key="prod-{{$produto->id}}"
                                    class="text-left bg-white p-3 rounded-lg border border-slate-200 shadow-sm hover:border-blue-400 hover:shadow-md transition-all group relative">

                                    <div
                                        class="font-bold text-slate-700 text-sm line-clamp-2 group-hover:text-blue-700 mb-1">
                                        {{ $produto->descricao }}
                                    </div>

                                    <div class="flex justify-between items-end mt-2">
                                        <div>
                                            <div
                                                class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded w-fit mb-1">
                                                {{ $produto->codigo_barras }}
                                            </div>
                                            <span
                                                class="text-xs {{ $produto->estoque > 0 ? 'text-green-600 bg-green-50' : 'text-red-500 bg-red-50' }} px-1.5 py-0.5 rounded font-bold">
                                                {{ $produto->estoque }} un
                                            </span>
                                        </div>
                                        <span class="text-lg font-bold text-slate-800">
                                            {{ number_format($produto->preco_venda, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center h-40 text-slate-400">
                                <i class='bx bx-search-alt text-4xl mb-2'></i>
                                <p class="text-sm">Nenhum produto encontrado</p>
                            </div>
                            @endif
                            @endif

                            <!-- LISTAGEM DE SERVIÇOS -->
                            @if($natureza === 'servico')
                            @if(count($servicos) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($servicos as $servico)
                                <button wire:click="adicionarServico({{ $servico->id }})"
                                    wire:key="serv-{{$servico->id}}"
                                    class="text-left bg-white p-3 rounded-lg border border-green-200 shadow-sm hover:border-green-400 hover:shadow-md transition-all group relative">

                                    <!-- Badge de Serviço -->
                                    <div class="absolute top-2 right-2">
                                        <span
                                            class="text-[9px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full font-bold">
                                            SERVIÇO
                                        </span>
                                    </div>

                                    <div
                                        class="font-bold text-slate-700 text-sm line-clamp-2 group-hover:text-green-700 mb-1 pr-16">
                                        {{ $servico->descricao }}
                                    </div>

                                    <div class="flex justify-between items-end mt-3">
                                        <div>
                                            <span
                                                class="text-xs text-green-600 bg-green-50 px-1.5 py-0.5 rounded font-bold flex items-center gap-1">
                                                <i class='bx bx-infinite'></i> Disponível
                                            </span>
                                        </div>
                                        <span class="text-lg font-bold text-slate-800">
                                            {{ number_format($servico->preco_venda, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center h-40 text-slate-400">
                                <i class='bx bx-briefcase-alt-2 text-4xl mb-2'></i>
                                <p class="text-sm">Nenhum serviço encontrado</p>
                            </div>
                            @endif
                            @endif
                        </div>

                        <!-- Carrinho (Direita - 40%) -->
                        <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                            @forelse($produtosCarrinho as $index => $item)
                            <div
                                class="bg-white border border-slate-100 rounded-lg p-3 hover:border-blue-200 transition-colors shadow-sm relative group">
                                <!-- Badge de Natureza -->
                                <div class="absolute top-2 right-8">
                                    @if($item['natureza'] === 'servico')
                                    <span
                                        class="text-[8px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full font-bold">
                                        <i class='bx bx-briefcase-alt-2'></i> SERVIÇO
                                    </span>
                                    @else
                                    <span
                                        class="text-[8px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full font-bold">
                                        <i class='bx bx-package'></i> PRODUTO
                                    </span>
                                    @endif
                                </div>

                                <div class="flex justify-between items-start mb-2 pr-6">
                                    <div>
                                        <div class="font-bold text-sm text-slate-700 line-clamp-1"
                                            title="{{ $item['descricao'] }}">
                                            {{ $item['descricao'] }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ number_format($item['preco_venda'], 2, ',', '.') }} KZ
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <!-- Controle Quantidade -->
                                    <div class="flex items-center border border-slate-200 rounded">
                                        <button wire:click="alterarQuantidade({{ $index }}, -1)"
                                            class="w-6 h-6 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors">
                                            <i class='bx bx-minus text-xs'></i>
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-slate-800">
                                            {{ $item['quantidade'] }}
                                        </span>
                                        <button wire:click="alterarQuantidade({{ $index }}, 1)"
                                            class="w-6 h-6 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors">
                                            <i class='bx bx-plus text-xs'></i>
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-sm font-bold text-blue-600">
                                            {{ number_format($item['preco_venda'] * $item['quantidade'], 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Botão Remover -->
                                <button wire:click="removerProduto({{ $index }})"
                                    class="absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors">
                                    <i class='bx bx-trash text-lg'></i>
                                </button>
                            </div>
                            @empty
                            <div
                                class="flex flex-col items-center justify-center h-full text-slate-400 text-center p-4">
                                <i class='bx bx-basket text-4xl mb-2 opacity-50'></i>
                                <p class="text-sm">Carrinho vazio.</p>
                                <p class="text-xs mt-1">Selecione itens ao lado.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO DIREITA: Resumo e Pagamento (30-35%) -->
            <div class=" w-[420px] flex flex-col gap-4">

                <!-- Painel Resumo -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden sticky top-6">
                    <div class="bg-slate-900 p-4 text-white">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <i class='bx bx-receipt'></i> Resumo da Venda
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">

                        <!-- Totais -->
                        <div class="space-y-3 pb-6 border-b border-slate-100 text-slate-600 text-sm">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-bold">{{ number_format($subtotal, 2, ',', '.') }} KZ</span>
                            </div>

                            @if(in_array($tipoDocumento, ['FT', 'FR', 'FP']))
                            <div class="flex justify-between text-slate-500">
                                <span>Total Impostos (IVA)</span>
                                <span>{{ number_format($iva, 2, ',', '.') }} KZ</span>
                            </div>
                            @endif

                            <div class="flex justify-between text-slate-500">
                                <span>Descontos</span>
                                <span>{{ number_format($desconto, 2, ',', '.') }} KZ</span>
                            </div>
                        </div>

                        <!-- TOTAL GRANDÃO -->
                        <div class="flex justify-between items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <span class="text-blue-900 font-bold uppercase text-sm">Total a Pagar</span>
                            <span class="text-3xl font-extrabold text-blue-600">{{ number_format($total, 2, ',', '.') }}
                                <span class="text-sm text-blue-400 font-medium">KZ</span></span>
                        </div>

                        <!-- CAMPOS CONDICIONAIS DE PAGAMENTO (Só aparecem para FR e RC) -->
                        @if(in_array($tipoDocumento, ['FR', 'RC']))
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 animate-fade-in-up">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Valor Entregue ({{
                                    strtoupper($metodoPagamento) }})</label>
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="totalRecebido"
                                        class="w-full pl-4 pr-12 py-3 border-2 border-slate-300 rounded-lg text-lg font-bold text-slate-800 focus:border-green-500 focus:ring-0 text-right"
                                        placeholder="0,00">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">KZ</span>
                                </div>
                            </div>

                            @if($metodoPagamento === 'dinheiro')
                            <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                <span class="text-sm font-bold text-slate-500 uppercase">Troco</span>
                                <span class="text-xl font-bold {{ $troco > 0 ? 'text-green-600' : 'text-slate-400' }}">
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
                                ? 'bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 shadow-orange-500/20' 
                                : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-blue-500/20' }}">

                            <span wire:loading.remove class="flex items-center justify-center gap-2">
                                @if($modoRetificacao)
                                <i class='bx bx-revision text-xl'></i> Finalizar Retificação
                                @else
                                @switch($tipoDocumento)
                                @case('FR') <i class='bx bx-check-double text-2xl'></i> Emitir Fatura-Recibo @break
                                @case('FT') <i class='bx bx-file text-2xl'></i> Emitir Fatura @break
                                @case('FP') <i class='bx bx-time-five text-2xl'></i> Criar Proforma @break
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
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-[60] transition-all p-4"
        aria-modal="true" role="dialog">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[80vh]">
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
                <div class="relative">
                    <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg'></i>
                    <input type="text" wire:model.live.debounce.300ms="searchClienteTerm" autofocus
                        class="w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all shadow-sm"
                        placeholder="Pesquisar por Nome, NIF ou Telefone...">
                </div>
            </div>

            <!-- Modal List -->
            <div class="overflow-y-auto flex-1 p-0 bg-white custom-scrollbar">
                @forelse($clientes as $cliente)
                <div wire:click="selecionarCliente({{ $cliente->id }})"
                    class="p-4 border-b border-slate-100 hover:bg-blue-50 cursor-pointer transition-colors duration-150 flex justify-between items-center group">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-200 text-slate-500 font-bold flex items-center justify-center group-hover:bg-blue-200 group-hover:text-blue-700 transition-colors">
                            {{ substr($cliente->nome, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors">{{
                                $cliente->nome }}</p>
                            <div class="flex gap-3 text-xs text-slate-500">
                                <span><i class='bx bx-id-card'></i> {{ $cliente->nif }}</span>
                                @if($cliente->telefone)<span><i class='bx bx-phone'></i> {{ $cliente->telefone
                                    }}</span>@endif
                            </div>
                        </div>
                    </div>
                    <i class='bx bx-chevron-right text-slate-300 group-hover:text-blue-500 text-xl'></i>
                </div>
                @empty
                <div class="py-12 text-center text-slate-400">
                    <i class='bx bx-user-x text-5xl mb-2 opacity-50'></i>
                    <p>Nenhum cliente encontrado</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
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
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }
    </style>
</div>