<x-app-layout>
  <div class="p-6 bg-white">
    <div class="max-w-7xl mx-auto">
      {{-- Cabeçalho --}}
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Nota de Crédito</h1>
          <p class="text-sm text-gray-600 mt-1">{{ $dados['numero_nota_credito'] }}</p>
        </div>
        <div class="flex gap-2">
          <a href="{{ route('admin.notas-credito') }}"
            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Voltar
          </a>
          <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Imprimir
          </button>
        </div>
      </div>

      {{-- Informações da Retificação --}}
      <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6">
        <h3 class="font-bold text-orange-800 mb-2">Informações da Retificação</h3>
        <div class="grid grid-cols-3 gap-4 text-sm">
          <div>
            <span class="text-gray-600">Data:</span>
            <strong>{{ $dados['retificacao']['data'] }}</strong>
          </div>
          <div>
            <span class="text-gray-600">Usuário:</span>
            <strong>{{ $dados['retificacao']['usuario'] }}</strong>
          </div>
          <div class="col-span-3">
            <span class="text-gray-600">Motivo:</span>
            <strong>{{ $dados['retificacao']['motivo'] }}</strong>
          </div>
        </div>
      </div>

      {{-- Dados da Empresa e Cliente --}}
      <div class="grid grid-cols-2 gap-6 mb-6">
        {{-- Empresa --}}
        <div class="border rounded-lg p-4">
          <h3 class="font-bold text-gray-800 mb-3">Dados da Empresa</h3>
          <div class="text-sm space-y-1">
            <p><strong>{{ $dados['empresa']['nome'] }}</strong></p>
            <p>NIF: {{ $dados['empresa']['nif'] }}</p>
            <p>{{ $dados['empresa']['endereco'] }}</p>
            <p>{{ $dados['empresa']['cidade'] }}</p>
            <p>Tel: {{ $dados['empresa']['telefone'] }}</p>
          </div>
        </div>

        {{-- Cliente --}}
        <div class="border rounded-lg p-4">
          <h3 class="font-bold text-gray-800 mb-3">Dados do Cliente</h3>
          <div class="text-sm space-y-1">
            <p><strong>{{ $dados['cliente']['nome'] }}</strong></p>
            <p>NIF: {{ $dados['cliente']['nif'] }}</p>
            <p>{{ $dados['cliente']['endereco'] }}</p>
            <p>{{ $dados['cliente']['cidade'] }}</p>
            <p>Tel: {{ $dados['cliente']['telefone'] }}</p>
          </div>
        </div>
      </div>

      {{-- Comparativo de Documentos --}}
      <div class="grid grid-cols-2 gap-6 mb-6">
        {{-- Fatura Original (Anulada) --}}
        <div class="border border-red-300 rounded-lg p-4 bg-red-50">
          <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-red-800">Documento Original (Anulado)</h3>
            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">
              RETIFICADA
            </span>
          </div>

          <div class="space-y-2 text-sm">
            <p><strong>Número:</strong> <span class="line-through">{{ $dados['fatura_original']['numero'] }}</span></p>
            <p><strong>Data:</strong> {{ $dados['fatura_original']['data_emissao'] }}</p>

            <div class="border-t pt-2 mt-2">
              <p class="flex justify-between">
                <span>Subtotal:</span>
                <span class="line-through text-red-600">{{ number_format($dados['fatura_original']['subtotal'], 2, ',',
                  '.') }} KZ</span>
              </p>
              <p class="flex justify-between">
                <span>Impostos:</span>
                <span class="line-through text-red-600">{{ number_format($dados['fatura_original']['total_impostos'], 2,
                  ',', '.') }} KZ</span>
              </p>
              <p class="flex justify-between font-bold">
                <span>Total:</span>
                <span class="line-through text-red-600">{{ number_format($dados['fatura_original']['total'], 2, ',',
                  '.') }} KZ</span>
              </p>
            </div>
          </div>
        </div>

        {{-- Fatura Retificação (Válida) --}}
        @if($dados['fatura_retificacao'])
        <div class="border border-green-300 rounded-lg p-4 bg-green-50">
          <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-green-800">Documento Retificado (Válido)</h3>
            <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded">
              VÁLIDA
            </span>
          </div>

          <div class="space-y-2 text-sm">
            <p><strong>Número:</strong> {{ $dados['fatura_retificacao']['numero'] }}</p>
            <p><strong>Data:</strong> {{ $dados['fatura_retificacao']['data_emissao'] }}</p>

            <div class="border-t pt-2 mt-2">
              <p class="flex justify-between">
                <span>Subtotal:</span>
                <span class="text-green-700 font-medium">{{ number_format($dados['fatura_retificacao']['subtotal'], 2,
                  ',', '.') }} KZ</span>
              </p>
              <p class="flex justify-between">
                <span>Impostos:</span>
                <span class="text-green-700 font-medium">{{
                  number_format($dados['fatura_retificacao']['total_impostos'], 2, ',', '.') }} KZ</span>
              </p>
              <p class="flex justify-between font-bold">
                <span>Total:</span>
                <span class="text-green-700">{{ number_format($dados['fatura_retificacao']['total'], 2, ',', '.') }}
                  KZ</span>
              </p>
            </div>
          </div>
        </div>
        @endif
      </div>

      {{-- Análise Comparativa --}}
      @if($dados['comparativo'])
      <div class="bg-blue-50 border border-blue-300 rounded-lg p-4 mb-6">
        <h3 class="font-bold text-blue-800 mb-3">Análise Comparativa</h3>
        <div class="grid grid-cols-4 gap-4 text-sm">
          <div class="text-center">
            <p class="text-gray-600 mb-1">Diferença Subtotal</p>
            <p
              class="text-lg font-bold {{ $dados['comparativo']['diferenca_subtotal'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
              {{ $dados['comparativo']['diferenca_subtotal'] >= 0 ? '+' : '' }}{{
              number_format($dados['comparativo']['diferenca_subtotal'], 2, ',', '.') }} KZ
            </p>
          </div>
          <div class="text-center">
            <p class="text-gray-600 mb-1">Diferença Impostos</p>
            <p
              class="text-lg font-bold {{ $dados['comparativo']['diferenca_impostos'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
              {{ $dados['comparativo']['diferenca_impostos'] >= 0 ? '+' : '' }}{{
              number_format($dados['comparativo']['diferenca_impostos'], 2, ',', '.') }} KZ
            </p>
          </div>
          <div class="text-center">
            <p class="text-gray-600 mb-1">Diferença Total</p>
            <p
              class="text-lg font-bold {{ $dados['comparativo']['diferenca_total'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
              {{ $dados['comparativo']['diferenca_total'] >= 0 ? '+' : '' }}{{
              number_format($dados['comparativo']['diferenca_total'], 2, ',', '.') }} KZ
            </p>
          </div>
          <div class="text-center">
            <p class="text-gray-600 mb-1">Variação (%)</p>
            <p
              class="text-lg font-bold {{ $dados['comparativo']['percentual_variacao'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
              {{ $dados['comparativo']['percentual_variacao'] >= 0 ? '+' : '' }}{{
              number_format($dados['comparativo']['percentual_variacao'], 2, ',', '.') }}%
            </p>
          </div>
        </div>
      </div>
      @endif

      {{-- Análise de Produtos --}}
      @if($dados['analise_produtos'])
      <div class="mb-6">
        <h3 class="font-bold text-gray-800 mb-4">Análise de Alterações nos Produtos</h3>

        {{-- Produtos Removidos --}}
        @if(count($dados['analise_produtos']['produtos_removidos']) > 0)
        <div class="mb-4">
          <h4 class="font-semibold text-red-700 mb-2">Produtos Removidos</h4>
          <div class="bg-red-50 border border-red-200 rounded-lg p-3">
            @foreach($dados['analise_produtos']['produtos_removidos'] as $produto)
            <div class="flex justify-between text-sm mb-1">
              <span class="line-through">{{ $produto['descricao'] }} ({{ $produto['quantidade'] }}x)</span>
              <span class="line-through text-red-600">{{ number_format($produto['total'], 2, ',', '.') }} KZ</span>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Produtos Adicionados --}}
        @if(count($dados['analise_produtos']['produtos_adicionados']) > 0)
        <div class="mb-4">
          <h4 class="font-semibold text-green-700 mb-2">Produtos Adicionados</h4>
          <div class="bg-green-50 border border-green-200 rounded-lg p-3">
            @foreach($dados['analise_produtos']['produtos_adicionados'] as $produto)
            <div class="flex justify-between text-sm mb-1">
              <span class="text-green-700 font-medium">{{ $produto['descricao'] }} ({{ $produto['quantidade']
                }}x)</span>
              <span class="text-green-700 font-bold">{{ number_format($produto['total'], 2, ',', '.') }} KZ</span>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Produtos Alterados --}}
        @if(count($dados['analise_produtos']['produtos_alterados']) > 0)
        <div class="mb-4">
          <h4 class="font-semibold text-blue-700 mb-2">Produtos com Alterações</h4>
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            @foreach($dados['analise_produtos']['produtos_alterados'] as $produto)
            <div class="border-b border-blue-200 pb-2 mb-2 last:border-0">
              <p class="font-medium text-sm">{{ $produto['descricao'] }}</p>
              <div class="grid grid-cols-2 gap-2 text-xs mt-1">
                <div>
                  <span class="text-gray-600">Qtd:</span>
                  <span class="line-through text-red-600">{{ $produto['quantidade_original'] }}</span>
                  →
                  <span class="text-green-600 font-medium">{{ $produto['quantidade_nova'] }}</span>
                </div>
                <div>
                  <span class="text-gray-600">Total:</span>
                  <span class="line-through text-red-600">{{ number_format($produto['total_original'], 2, ',', '.')
                    }}</span>
                  →
                  <span class="text-green-600 font-medium">{{ number_format($produto['total_novo'], 2, ',', '.') }}
                    KZ</span>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
      @endif

      {{-- Produtos Detalhados - Original --}}
      <div class="mb-6">
        <h3 class="font-bold text-gray-800 mb-3">Produtos do Documento Original</h3>
        <div class="border rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Produto</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Qtd</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Preço Un.</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Subtotal</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">IVA</th>
                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($dados['fatura_original']['produtos'] as $produto)
              <tr>
                <td class="px-4 py-2 text-sm">
                  {{ $produto['descricao'] }}
                  @if($produto['motivo_isencao'])
                  <span class="text-xs text-orange-600">({{ $produto['motivo_isencao'] }})</span>
                  @endif
                </td>
                <td class="px-4 py-2 text-sm text-right">{{ $produto['quantidade'] }}</td>
                <td class="px-4 py-2 text-sm text-right">{{ number_format($produto['preco_unitario'], 2, ',', '.') }}
                </td>
                <td class="px-4 py-2 text-sm text-right">{{ number_format($produto['subtotal'], 2, ',', '.') }}</td>
                <td class="px-4 py-2 text-sm text-right">{{ number_format($produto['valor_iva'], 2, ',', '.') }}</td>
                <td class="px-4 py-2 text-sm text-right font-medium">{{ number_format($produto['total'], 2, ',', '.') }}
                  KZ</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>