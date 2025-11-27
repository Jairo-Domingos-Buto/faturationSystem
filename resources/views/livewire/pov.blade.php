<div>
    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    @if (session()->has('info'))
    <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
        {{ session('info') }}
    </div>
    @endif

    <div class="max-w-7xl mx-auto">
        <!-- ✅ NOVO: Banner de Retificação -->
        @if($modoRetificacao)
        <div class="mb-6 p-5 bg-orange-50 border-l-4 border-orange-500 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <i class='bx bx-refresh text-2xl text-orange-600'></i>
                        <h3 class="text-xl font-bold text-orange-800">
                            🔄 Modo Retificação Ativo
                        </h3>
                    </div>
                    <p class="text-sm text-orange-700">
                        Retificando documento: <strong class="text-orange-900">{{ $documentoOriginalNumero }}</strong>
                    </p>
                    <p class="text-xs text-orange-600 mt-1">
                        Faça as alterações necessárias (adicionar/remover produtos, trocar cliente) e finalize a
                        retificação
                    </p>
                </div>
                <button wire:click="cancelarRetificacao"
                    class="px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center gap-2">
                    <i class='bx bx-x text-xl'></i>
                    Cancelar Retificação
                </button>
            </div>

            <!-- Campo de motivo -->
            <div class="mt-4">
                <label class="block text-sm font-semibold text-orange-800 mb-2">
                    Motivo da Retificação <span class="text-red-600">*</span>
                </label>
                <textarea wire:model="motivoRetificacao" rows="2"
                    class="w-full border-orange-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 p-3"
                    placeholder="Ex: Correção de valores, alteração de produtos, erro no cliente, etc."></textarea>
                @error('motivoRetificacao')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @endif

        <!-- Header -->
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                @if($modoRetificacao)
                Retificação de {{ $tipoDocumento === 'fatura' ? 'Fatura' : 'Recibo' }}
                @else
                Ponto de Venda
                @endif
            </h1>

            <button wire:click="exportarDadosFatura"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-blue-500 hover:text-white transition-colors duration-200">
                <i class='bx bx-printer mr-2'></i>Imprimir
            </button>
        </header>

        <div class="flex gap-6">
            <!-- Left Section (65%) -->
            <div class="flex-1 space-y-4">
                <!-- Top Cards Row -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Documento Card -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <i class='bx bx-file-blank text-blue-500 text-xl'></i>
                            <span class="font-semibold text-gray-700">Documento</span>
                        </div>
                        <select wire:model="tipoDocumento"
                            class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            @if($modoRetificacao) disabled @endif>
                            <option value="fatura">Fatura</option>
                            <option value="recibo">Recibo</option>
                            <option value="fatura_recibo">Fatura Recibo</option>
                            <option value="fatura_porForma">Fatura Por Forma</option>
                        </select>

                        @if($tipoDocumento === 'recibo')
                        <div class="mt-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Método de Pagamento</label>
                            <select wire:model="metodoPagamento"
                                class="w-full p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                                <option value="dinheiro">Dinheiro</option>
                                <option value="cartao">Cartão</option>
                                <option value="transferencia">Transferência</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    <!-- Natureza Card -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="mb-3">
                            <span class="font-semibold text-gray-700">Natureza</span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="alterarNatureza('produto')"
                                class="flex-1 py-2.5 rounded-lg font-medium transition-colors duration-200 {{ $natureza === 'produto' ? 'text-white bg-blue-500' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">
                                Produto
                            </button>
                            <button wire:click="alterarNatureza('servico')"
                                class="flex-1 py-2.5 rounded-lg font-medium transition-colors duration-200 {{ $natureza === 'servico' ? 'text-white bg-blue-500' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">
                                Serviço
                            </button>
                        </div>
                    </div>

                    <!-- Cliente Card -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <i class='bx bx-user text-blue-500 text-xl'></i>
                            <span class="font-semibold text-gray-700">Cliente</span>
                            <button wire:click="abrirModal"
                                class="ml-auto text-blue-500 hover:text-blue-600 text-sm font-medium">
                                Selecionar
                            </button>
                        </div>
                        <div class="p-2.5 bg-gray-100 border border-gray-300 rounded-lg text-center text-gray-600">
                            <span class="text-sm">{{ $clienteNome }}</span>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="mb-4">
                        <div class="relative">
                            <i
                                class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                            <input type="search" wire:model.live.debounce.300ms="searchProdutoTerm"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Pesquisar produto por descrição ou código de barras">
                        </div>
                    </div>

                    <!-- Lista de Produtos Disponíveis -->
                    @if(count($produtos) > 0)
                    <div class="mb-4 border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class='bx bx-package text-blue-500'></i>
                            Produtos Disponíveis
                        </h3>
                        <div class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto">
                            @foreach($produtos as $produto)
                            <button wire:click="adicionarProduto({{ $produto->id }})"
                                class="p-3 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150 text-left">
                                <div class="font-semibold text-gray-800 text-sm">{{ $produto->descricao }}</div>
                                <div class="text-xs text-gray-500 mb-1">Cód: {{ $produto->codigo_barras }}</div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-blue-600">{{
                                        number_format($produto->preco_venda, 2, ',', '.') }} KZ</span>
                                    <span class="text-xs text-gray-600">
                                        <i class='bx bx-package'></i> {{ $produto->estoque }}
                                    </span>
                                </div>
                                @if($produto->categoria)
                                <div class="text-xs text-gray-500 mt-1">{{ $produto->categoria->nome ?? '' }}</div>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Carrinho de Produtos -->
                    <div class="border border-gray-200 rounded-lg p-3">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class='bx bx-cart text-blue-500'></i>
                            Carrinho ({{ count($produtosCarrinho) }} {{ count($produtosCarrinho) === 1 ? 'item' :
                            'itens' }})
                        </h3>

                        @if(count($produtosCarrinho) > 0)
                        <ul class="space-y-2">
                            @foreach($produtosCarrinho as $index => $item)
                            <li
                                class="bg-gray-50 border border-gray-200 rounded-lg p-3 hover:bg-gray-100 transition-colors duration-150">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex flex-col flex-1">
                                        <span class="font-semibold text-gray-800">{{ $item['descricao'] }}</span>
                                        <span class="text-xs text-gray-500">Cód: {{ $item['codigo_barras'] }}</span>
                                        <span class="text-sm text-gray-600">{{ number_format($item['preco_venda'], 2,
                                            ',', '.') }} KZ/un</span>
                                    </div>
                                    <button wire:click="removerProduto({{ $index }})"
                                        wire:confirm="Deseja remover este produto?"
                                        class="text-red-500 hover:text-red-700 transition-colors duration-150">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="alterarQuantidade({{ $index }}, -1)"
                                            class="w-8 h-8 bg-gray-300 hover:bg-gray-400 rounded-lg flex items-center justify-center transition-colors duration-150">
                                            <i class='bx bx-minus'></i>
                                        </button>
                                        <input type="number" value="{{ $item['quantidade'] }}" readonly
                                            class="w-16 text-center border border-gray-300 rounded-lg py-1 bg-white font-semibold">
                                        <button wire:click="alterarQuantidade({{ $index }}, 1)"
                                            class="w-8 h-8 bg-gray-300 hover:bg-gray-400 rounded-lg flex items-center justify-center transition-colors duration-150">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                        <span class="text-xs text-gray-500 ml-2">
                                            (Est: {{ $item['estoque_disponivel'] }})
                                        </span>
                                    </div>
                                    <span class="font-bold text-gray-800 text-lg">
                                        {{ number_format($item['preco_venda'] * $item['quantidade'], 2, ',', '.') }} KZ
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class='bx bx-cart text-4xl mb-2'></i>
                            <p>Carrinho vazio</p>
                            <p class="text-sm">Adicione produtos para iniciar {{ $modoRetificacao ? 'a retificação' : 'a
                                venda' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Section (35%) - Resumo -->
            <div class="w-[400px]">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                        Resumo {{ $modoRetificacao ? '(Retificação)' : '' }}
                    </h2>

                    <div class="mb-4 text-sm text-gray-600">
                        <p class="flex items-center gap-2">
                            <i class='bx bx-calendar'></i>
                            <span>Data: <strong>{{ now()->format('d/m/Y') }}</strong></span>
                        </p>
                        @if($modoRetificacao)
                        <p class="flex items-center gap-2 mt-2 text-orange-600">
                            <i class='bx bx-info-circle'></i>
                            <span class="text-xs">Documento Original: <strong>{{ $documentoOriginalNumero
                                    }}</strong></span>
                        </p>
                        @endif
                    </div>

                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span class="font-semibold">{{ number_format($subtotal, 2, ',', '.') }} KZ</span>
                        </div>

                        @if($tipoDocumento === 'fatura')
                        <div class="flex justify-between text-gray-700">
                            <span>Incidência:</span>
                            <span class="font-semibold">{{ number_format($incidencia, 2, ',', '.') }} KZ</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>IVA (14%):</span>
                            <span class="font-semibold">{{ number_format($iva, 2, ',', '.') }} KZ</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Total a Pagar:</h3>
                        <span class="text-2xl font-bold text-blue-600">{{ number_format($total, 2, ',', '.') }}
                            KZ</span>
                    </div>

                    <!-- Botão de finalizar -->
                    <button wire:click="finalizarVenda" wire:loading.attr="disabled"
                        class="w-full py-3.5 {{ $modoRetificacao ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white text-lg font-semibold rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 disabled:opacity-50">
                        <i class='bx {{ $modoRetificacao ? ' bx-refresh' : 'bx-check-circle' }} text-2xl'></i>
                        <span wire:loading.remove>
                            {{ $modoRetificacao ? 'Finalizar Retificação' : 'Finalizar ' . ($tipoDocumento === 'fatura'
                            ? 'Fatura' : 'Recibo') }}
                        </span>
                        <span wire:loading>Processando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Clientes -->
    @if($showModal)
    <div class="fixed inset-0 bg-[rgba(0,0,0,0.28)] flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center p-5 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Selecionar Cliente</h2>
                <button wire:click="fecharModal" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <div class="relative">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        <input type="text" wire:model.live.debounce.300ms="searchClienteTerm"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Pesquisar por nome ou NIF">
                    </div>
                </div>
                <div class="overflow-auto max-h-96">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">NIF</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientes as $cliente)
                            <tr wire:click="selecionarCliente({{ $cliente->id }})"
                                class="border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                <td class="px-4 py-3 text-gray-700">{{ $cliente->nif }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $cliente->nome }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center text-gray-500">
                                    Nenhum cliente encontrado
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>