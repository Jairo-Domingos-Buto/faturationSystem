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

  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <header class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Ponto de Venda</h1>
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
              class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
              <option value="fatura">Fatura</option>
              <option value="fatura-recibo">Fatura-Recibo</option>
              <option value="recibo">Recibo</option>
            </select>
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
              <button wire:click="abrirModal" class="ml-auto text-blue-500 hover:text-blue-600 text-sm font-medium">
                Selecionar
              </button>
            </div>
            <div class="p-2.5 bg-gray-100 border border-gray-300 rounded-lg text-center text-gray-600">
              <span>{{ $clienteNome }}</span>
            </div>
          </div>
        </div>

        <!-- Products Section -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
          <div class="mb-4">
            <div class="relative">
              <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
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
                  <span class="text-sm font-bold text-blue-600">{{ number_format($produto->preco_venda, 2, ',', '.') }}
                    KZ</span>
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
              Carrinho ({{ count($produtosCarrinho) }} {{ count($produtosCarrinho) === 1 ? 'item' : 'itens' }})
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
                    <span class="text-sm text-gray-600">{{ number_format($item['preco_venda'], 2, ',', '.') }}
                      KZ/un</span>
                  </div>
                  <button wire:click="removerProduto({{ $index }})" wire:confirm="Deseja remover este produto?"
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
              <p class="text-sm">Adicione produtos para iniciar a venda</p>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Right Section (35%) - Resumo -->
      <div class="w-[400px]">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 sticky top-6">
          <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">
            Resumo da Fatura
          </h2>

          <div class="mb-4 text-sm text-gray-600">
            <p class="flex items-center gap-2">
              <i class='bx bx-calendar'></i>
              <span>Data: <strong>{{ now()->format('d/m/Y') }}</strong></span>
            </p>
          </div>

          <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
            <div class="flex justify-between text-gray-700">
              <span>Subtotal:</span>
              <span class="font-semibold">{{ number_format($subtotal, 2, ',', '.') }} KZ</span>
            </div>
            <div class="flex justify-between text-gray-700">
              <span>Incidência:</span>
              <span class="font-semibold">{{ number_format($incidencia, 2, ',', '.') }} KZ</span>
            </div>
            <div class="flex justify-between text-gray-700">
              <span>IVA (14%):</span>
              <span class="font-semibold">{{ number_format($iva, 2, ',', '.') }} KZ</span>
            </div>
          </div>

          <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Total a Pagar:</h3>
            <span class="text-2xl font-bold text-blue-600">{{ number_format($total, 2, ',', '.') }} KZ</span>
          </div>

          <div class="space-y-3 mb-4">
            <div class="relative">
              <i class='bx bx-money absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
              <input type="text" wire:="totalRecebido"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                placeholder="Total recebido">
            </div>
            <div class="flex justify-between items-center text-gray-700">
              <span>Desconto:</span>
              <span class="font-semibold">{{ number_format($desconto, 2, ',', '.') }} KZ</span>
            </div>
            <div class="flex justify-between items-center text-gray-700">
              <span>Troco:</span>
              <span class="font-semibold text-green-600">{{ number_format($troco, 2, ',', '.') }} KZ</span>
            </div>
          </div>

          <button wire:click="finalizarVenda"
            class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
            <i class='bx bx-check-circle text-2xl'></i>
            Finalizar Venda
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