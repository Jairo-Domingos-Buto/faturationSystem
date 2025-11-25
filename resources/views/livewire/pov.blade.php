<div class="min-h-screen bg-slate-100 pb-10 relative font-sans">

    <!-- Toasts -->
    <div class="fixed top-5 right-5 z-[70] space-y-3 w-full max-w-sm pointer-events-none">
        @if (session()->has('success'))
        <div
            class="pointer-events-auto flex p-4 bg-white rounded-xl shadow-lg border-l-4 border-emerald-500 animate-fade-in-right">
            <i class='bx bx-check-circle text-2xl text-emerald-500 mr-3'></i>
            <span class="text-slate-700 font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if (session()->has('error'))
        <div
            class="pointer-events-auto flex p-4 bg-white rounded-xl shadow-lg border-l-4 border-red-500 animate-fade-in-right">
            <i class='bx bx-error text-2xl text-red-500 mr-3'></i>
            <span class="text-slate-700 font-medium">{{ session('error') }}</span>
        </div>
        @endif
        @if (session()->has('info'))
        <div
            class="pointer-events-auto flex p-4 bg-white rounded-xl shadow-lg border-l-4 border-blue-500 animate-fade-in-right">
            <i class='bx bx-info-circle text-2xl text-blue-500 mr-3'></i>
            <span class="text-slate-700 font-medium">{{ session('info') }}</span>
        </div>
        @endif
    </div>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 pt-6 h-[calc(100vh-24px)] flex flex-col">

        <!-- Header Compacto -->
        <div class="flex items-center justify-between mb-4 shrink-0">
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                @if($modoRetificacao)
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-base shadow-sm">
                    <i class='bx bx-revision'></i> Retificando {{ $documentoOriginalNumero }}
                </span>
                @else
                <span class="text-indigo-700"><i class='bx bxs-store-alt'></i> PDV</span>
                @endif
            </h1>

            <div class="flex gap-3">
                @if($modoRetificacao)
                <button wire:click="cancelarRetificacao"
                    class="px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium shadow-sm">Cancelar</button>
                @else
                <button wire:click="exportarDadosFatura"
                    class="px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:text-indigo-600 hover:border-indigo-300 font-medium shadow-sm flex items-center gap-2">
                    <i class='bx bx-printer'></i> Última Venda
                </button>
                @endif
            </div>
        </div>

        @if($modoRetificacao)
        <div class="mb-4 shrink-0">
            <textarea wire:model="motivoRetificacao" rows="1"
                class="w-full px-4 py-2 rounded-lg border-amber-300 bg-amber-50 focus:ring-amber-500 placeholder-amber-400/70 text-sm"
                placeholder="Descreva o motivo da retificação obrigatório..."></textarea>
        </div>
        @endif

        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 flex-1 overflow-hidden">

            <!-- Coluna Esquerda: Configurações e Ações (4 colunas) -->
            <div class="lg:col-span-4 flex flex-col gap-4 h-full overflow-y-auto pr-1 custom-scrollbar">

                <!-- Card Cliente -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cliente</label>
                        <button wire:click="abrirModalCliente"
                            class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-100 font-bold">Alterar</button>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                            <i class='bx bxs-user'></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-semibold text-slate-800 truncate">{{ $clienteNome }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $clienteSelecionado ? 'NIF carregado' :
                                'Consumidor Final' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Tipo Doc -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-3">Dados do
                        Documento</label>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <select wire:model.live="tipoDocumento" @if($modoRetificacao) disabled @endif
                            class="col-span-2 bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            <option value="fatura">Fatura</option>
                            <option value="recibo">Recibo</option>
                        </select>
                    </div>

                    @if($tipoDocumento === 'recibo')
                    <div class="grid grid-cols-3 gap-2 animate-fade-in">
                        @foreach(['dinheiro' => 'Cash', 'cartao' => 'TPA', 'transferencia' => 'Transf'] as $key =>
                        $label)
                        <button wire:click="$set('metodoPagamento', '{{$key}}')"
                            class="py-2 text-xs font-bold rounded-lg border transition-all {{ $metodoPagamento === $key ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Botões de Ação Gigantes -->
                <div class="grid grid-cols-1 gap-3 mt-auto">
                    <button wire:click="abrirModalItem('produto')"
                        class="group relative w-full p-5 bg-white rounded-2xl border-2 border-indigo-100 hover:border-indigo-500 hover:shadow-lg transition-all duration-200 text-left overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class='bx bx-package text-6xl text-indigo-600'></i>
                        </div>
                        <div class="relative z-10">
                            <span class="block text-indigo-600 font-bold text-lg mb-1">Adicionar Produtos</span>
                            <span class="text-sm text-slate-500">Buscar e inserir itens no carrinho</span>
                        </div>
                    </button>

                    <button wire:click="abrirModalItem('servico')"
                        class="group relative w-full p-5 bg-white rounded-2xl border-2 border-emerald-100 hover:border-emerald-500 hover:shadow-lg transition-all duration-200 text-left overflow-hidden">
                        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class='bx bx-briefcase-alt-2 text-6xl text-emerald-600'></i>
                        </div>
                        <div class="relative z-10">
                            <span class="block text-emerald-600 font-bold text-lg mb-1">Adicionar Serviços</span>
                            <span class="text-sm text-slate-500">Mão de obra e consultoria</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Coluna Direita: Carrinho (8 colunas) -->
            <div
                class="lg:col-span-8 h-full flex flex-col bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                <!-- Cabeçalho Carrinho -->
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center shrink-0">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class='bx bx-cart'></i> Carrinho <span
                            class="bg-indigo-600 text-white text-xs py-0.5 px-2 rounded-full">{{
                            count($produtosCarrinho) }}</span>
                    </h2>
                    <span class="text-sm text-slate-500">Total Estimado</span>
                </div>

                <!-- Lista de Itens -->
                <div class="flex-1 overflow-y-auto p-0 bg-white custom-scrollbar relative">
                    @if(count($produtosCarrinho) > 0)
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 sticky top-0 z-10 text-xs uppercase text-slate-500 font-semibold">
                            <tr>
                                <th class="p-4">Item</th>
                                <th class="p-4 text-center">Qtd</th>
                                <th class="p-4 text-right">Total</th>
                                <th class="p-4 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($produtosCarrinho as $index => $item)
                            <tr class="hover:bg-slate-50 group transition-colors">
                                <td class="p-4">
                                    <div class="font-medium text-slate-800">{{ $item['descricao'] }}</div>
                                    <div class="text-xs text-slate-500">{{ number_format($item['preco_venda'], 2, ',',
                                        '.') }} KZ | Cód: {{ $item['codigo_barras'] }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="alterarQuantidade({{ $index }}, -1)"
                                            class="w-7 h-7 rounded bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600"><i
                                                class='bx bx-minus'></i></button>
                                        <input type="text" readonly value="{{ $item['quantidade'] }}"
                                            class="w-10 text-center font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0">
                                        <button wire:click="alterarQuantidade({{ $index }}, 1)"
                                            class="w-7 h-7 rounded bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600"><i
                                                class='bx bx-plus'></i></button>
                                    </div>
                                </td>
                                <td class="p-4 text-right font-bold text-indigo-600">
                                    {{ number_format($item['preco_venda'] * $item['quantidade'], 2, ',', '.') }}
                                </td>
                                <td class="p-4 text-center">
                                    <button wire:click="removerProduto({{ $index }})"
                                        class="text-slate-300 hover:text-red-500 transition-colors"><i
                                            class='bx bx-trash text-xl'></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-300">
                        <i class='bx bx-basket text-8xl mb-4 opacity-50'></i>
                        <p class="text-lg font-medium">O carrinho está vazio</p>
                        <p class="text-sm">Use os botões ao lado para adicionar itens</p>
                    </div>
                    @endif
                </div>

                <!-- Rodapé Totais -->
                <div class="p-6 bg-slate-50 border-t border-slate-200 shrink-0">
                    <div class="flex justify-between items-end mb-4">
                        <div class="text-sm text-slate-500 space-y-1">
                            <p>Subtotal: {{ number_format($subtotal, 2, ',', '.') }} KZ</p>
                            @if($tipoDocumento === 'fatura')
                            <p>IVA: {{ number_format($iva, 2, ',', '.') }} KZ</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-500 mb-1">Total a Pagar</p>
                            <p class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ number_format($total, 2,
                                ',', '.') }} <span class="text-lg font-medium text-slate-500">KZ</span></p>
                        </div>
                    </div>

                    <button wire:click="finalizarVenda" wire:loading.attr="disabled"
                        class="w-full py-4 rounded-xl font-bold text-lg text-white shadow-xl transform hover:-translate-y-0.5 transition-all
                        {{ $modoRetificacao ? 'bg-amber-600 hover:bg-amber-700 shadow-amber-200' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200' }}">
                        <span wire:loading.remove>
                            {{ $modoRetificacao ? 'Finalizar Retificação' : 'Finalizar Venda' }}
                        </span>
                        <span wire:loading>
                            <i class='bx bx-loader-alt animate-spin'></i> Processando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ MODAL DE ITENS (PRODUTOS/SERVIÇOS) - CENTRALIZADO COM FIX -->
    @if($showItemModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center w-full h-full bg-slate-900/75 backdrop-blur-sm p-4 animate-fade-in"
        aria-labelledby="modal-items" role="dialog" aria-modal="true">

        <div
            class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">

            <!-- Header Modal -->
            <div class="bg-white p-5 border-b border-slate-100 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        @if($natureza == 'servico')
                        <i class='bx bx-briefcase-alt-2 text-emerald-600'></i> Selecionar Serviços
                        @else
                        <i class='bx bx-package text-indigo-600'></i> Selecionar Produtos
                        @endif
                    </h3>
                    <p class="text-sm text-slate-500">Clique no item para adicionar ao carrinho</p>
                </div>
                <button wire:click="fecharModalItem"
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                    <i class='bx bx-x text-xl'></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="p-5 bg-slate-50 border-b border-slate-100 shrink-0">
                <div class="relative">
                    <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl'></i>
                    <input type="text" wire:model.live.debounce.200ms="searchProdutoTerm" autofocus
                        class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm text-lg"
                        placeholder="Digite o nome, código ou referência...">
                    <div wire:loading wire:target="searchProdutoTerm" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <i class='bx bx-loader-alt animate-spin text-indigo-500 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Lista de Itens -->
            <div class="flex-1 overflow-y-auto p-5 bg-slate-50 custom-scrollbar">
                @if(count($produtos) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($produtos as $produto)
                    <button wire:click="adicionarProduto({{ $produto->id }})" wire:key="prod-{{ $produto->id }}"
                        class="flex flex-col bg-white border border-slate-200 rounded-xl p-4 hover:border-indigo-500 hover:ring-1 hover:ring-indigo-500 hover:shadow-md transition-all text-left group h-full">

                        <div class="flex justify-between items-start w-full mb-2">
                            <span class="bg-slate-100 text-slate-500 text-[10px] font-mono px-1.5 py-0.5 rounded">{{
                                $produto->codigo_barras }}</span>
                            <span
                                class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $produto->estoque > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                Est: {{ $produto->estoque }}
                            </span>
                        </div>

                        <h4 class="font-bold text-slate-800 text-sm line-clamp-2 mb-2 group-hover:text-indigo-700">{{
                            $produto->descricao }}</h4>

                        <div class="mt-auto pt-2 border-t border-slate-50 flex justify-between items-center w-full">
                            <span class="text-lg font-bold text-slate-900">{{ number_format($produto->preco_venda, 2,
                                ',', '.') }} <small class="text-slate-400 font-normal">KZ</small></span>
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class='bx bx-plus'></i>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-60">
                    <i class='bx bx-search-x text-6xl mb-2'></i>
                    <p>Nenhum item encontrado</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- ✅ MODAL DE CLIENTES - CENTRALIZADO COM FIX -->
    @if($showModalCliente)
    <div class="fixed inset-0 z-[60] flex items-center justify-center w-full h-full bg-slate-900/75 backdrop-blur-sm p-4 animate-fade-in"
        aria-labelledby="modal-clientes" role="dialog" aria-modal="true">

        <div
            class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Selecionar Cliente</h3>
                <button wire:click="fecharModalCliente" class="text-slate-400 hover:text-slate-600"><i
                        class='bx bx-x text-2xl'></i></button>
            </div>

            <div class="p-4 bg-slate-50">
                <input type="text" wire:model.live.debounce.300ms="searchClienteTerm" autofocus
                    class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 py-3"
                    placeholder="Pesquisar cliente...">
            </div>

            <div class="flex-1 overflow-y-auto p-0 custom-scrollbar bg-white">
                @foreach($clientes as $cliente)
                <div wire:click="selecionarCliente({{ $cliente->id }})"
                    class="p-4 border-b border-slate-100 hover:bg-indigo-50 cursor-pointer transition-colors flex justify-between items-center">
                    <div>
                        <p class="font-bold text-slate-800">{{ $cliente->nome }}</p>
                        <p class="text-xs text-slate-500">NIF: {{ $cliente->nif }}</p>
                    </div>
                    <i class='bx bx-chevron-right text-slate-300'></i>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
</div>