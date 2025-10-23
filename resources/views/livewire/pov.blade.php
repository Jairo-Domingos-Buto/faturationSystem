<div class="max-w-7xl mx-auto">
  <!-- Header -->
  <header class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Ponto de Venda</h1>
    <button onclick="window.print()"
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
          <select id="documento"
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
            <button onclick="toggleNatureza('produto')" id="btn-produto"
              class="flex-1 py-2.5 rounded-lg text-white bg-blue-500 font-medium transition-colors duration-200">
              Produto
            </button>
            <button onclick="toggleNatureza('servico')" id="btn-servico"
              class="flex-1 py-2.5 rounded-lg text-gray-700 bg-gray-100 font-medium hover:bg-gray-200 transition-colors duration-200">
              Serviço
            </button>
          </div>
        </div>

        <!-- Cliente Card -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
          <div class="flex items-center gap-2 mb-3">
            <i class='bx bx-user text-blue-500 text-xl'></i>
            <span class="font-semibold text-gray-700">Cliente</span>
            <button onclick="abrirModalClientes()"
              class="ml-auto text-blue-500 hover:text-blue-600 text-sm font-medium">
              Selecionar
            </button>
          </div>
          <div class="p-2.5 bg-gray-100 border border-gray-300 rounded-lg text-center text-gray-600">
            <span id="cliente-selecionado">Nenhum cliente selecionado</span>
          </div>
        </div>
      </div>

      <!-- Products Section -->
      <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
        <div class="mb-4">
          <div class="relative">
            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
            <input type="search" id="pesquisa-produto"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Pesquisar produto ou serviço" onkeyup="pesquisarProduto()">
          </div>
        </div>

        <div class="border border-gray-200 rounded-lg p-3">
          <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <i class='bx bx-cart text-blue-500'></i>
            Lista de Produtos
          </h3>
          <ul id="lista-produtos" class="space-y-2">
            <!-- Produto exemplo -->
            <li
              class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex justify-between items-center hover:bg-gray-100 transition-colors duration-150">
              <div class="flex flex-col">
                <span class="font-semibold text-gray-800">Computador Core i7</span>
                <span class="text-sm text-gray-600">812.522,00 kz</span>
              </div>
              <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                  <button onclick="alterarQuantidade(this, -1)"
                    class="w-8 h-8 bg-gray-300 hover:bg-gray-400 rounded-lg flex items-center justify-center transition-colors duration-150">
                    <i class='bx bx-minus'></i>
                  </button>
                  <input type="number" value="1" min="1"
                    class="w-16 text-center border border-gray-300 rounded-lg py-1 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                  <button onclick="alterarQuantidade(this, 1)"
                    class="w-8 h-8 bg-gray-300 hover:bg-gray-400 rounded-lg flex items-center justify-center transition-colors duration-150">
                    <i class='bx bx-plus'></i>
                  </button>
                </div>
                <div class="flex items-center gap-3">
                  <span class="font-semibold text-gray-800 min-w-[100px] text-right">812.522,00 kz</span>
                  <button onclick="removerProduto(this)"
                    class="text-red-500 hover:text-red-700 font-medium transition-colors duration-150">
                    <i class='bx bx-trash'></i> Remover
                  </button>
                </div>
              </div>
            </li>
          </ul>
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
            <span>Data: <strong id="data-atual"></strong></span>
          </p>
        </div>

        <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
          <div class="flex justify-between text-gray-700">
            <span>Subtotal:</span>
            <span class="font-semibold" id="subtotal">0,00 KZ</span>
          </div>
          <div class="flex justify-between text-gray-700">
            <span>Incidência:</span>
            <span class="font-semibold" id="incidencia">0,00 KZ</span>
          </div>
          <div class="flex justify-between text-gray-700">
            <span>IVA (14%):</span>
            <span class="font-semibold" id="iva">0,00 KZ</span>
          </div>
        </div>

        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
          <h3 class="text-lg font-bold text-gray-800">Total a Pagar:</h3>
          <span class="text-2xl font-bold text-blue-600" id="total">0,00 KZ</span>
        </div>

        <div class="space-y-3 mb-4">
          <div class="relative">
            <i class='bx bx-money absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
            <input type="text" id="total-recebido"
              class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Total recebido" onkeyup="calcularTroco()">
          </div>
          <div class="flex justify-between items-center text-gray-700">
            <span>Desconto:</span>
            <span class="font-semibold" id="desconto">0,00 KZ</span>
          </div>
          <div class="flex justify-between items-center text-gray-700">
            <span>Troco:</span>
            <span class="font-semibold text-green-600" id="troco">0,00 KZ</span>
          </div>
        </div>

        <button onclick="finalizarVenda()"
          class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
          <i class='bx bx-check-circle text-2xl'></i>
          Finalizar Venda
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Clientes -->
<div id="modal-clientes" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
    <div class="flex justify-between items-center p-5 border-b border-gray-200">
      <h2 class="text-xl font-bold text-gray-800">Selecionar Cliente</h2>
      <button onclick="fecharModalClientes()" class="text-gray-400 hover:text-gray-600 text-2xl">
        <i class='bx bx-x'></i>
      </button>
    </div>
    <div class="p-5">
      <div class="mb-4">
        <div class="relative">
          <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
          <input type="text" id="pesquisa-cliente"
            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Pesquisar por nome ou NIF" onkeyup="pesquisarCliente()">
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
          <tbody id="lista-clientes">
            <tr onclick="selecionarCliente('5000795054', 'NEXCORP - Comércio e Prestação')"
              class="border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors duration-150">
              <td class="px-4 py-3 text-gray-700">5000795054</td>
              <td class="px-4 py-3 text-gray-700">NEXCORP - Comércio e Prestação</td>
            </tr>
            <tr onclick="selecionarCliente('5001925571', 'TECNO EXCELÊNCIA (SU), LDA')"
              class="border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors duration-150">
              <td class="px-4 py-3 text-gray-700">5001925571</td>
              <td class="px-4 py-3 text-gray-700">TECNO EXCELÊNCIA (SU), LDA</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  // Mostrar data atual
        document.getElementById('data-atual').textContent = new Date().toLocaleDateString('pt-AO');

        // Toggle Natureza
        function toggleNatureza(tipo) {
            const btnProduto = document.getElementById('btn-produto');
            const btnServico = document.getElementById('btn-servico');

            if (tipo === 'produto') {
                btnProduto.className = 'flex-1 py-2.5 rounded-lg text-white bg-blue-500 font-medium transition-colors duration-200';
                btnServico.className = 'flex-1 py-2.5 rounded-lg text-gray-700 bg-gray-100 font-medium hover:bg-gray-200 transition-colors duration-200';
            } else {
                btnServico.className = 'flex-1 py-2.5 rounded-lg text-white bg-blue-500 font-medium transition-colors duration-200';
                btnProduto.className = 'flex-1 py-2.5 rounded-lg text-gray-700 bg-gray-100 font-medium hover:bg-gray-200 transition-colors duration-200';
            }
        }

        // Modal de Clientes
        function abrirModalClientes() {
            document.getElementById('modal-clientes').classList.remove('hidden');
        }

        function fecharModalClientes() {
            document.getElementById('modal-clientes').classList.add('hidden');
        }

        function selecionarCliente(nif, nome) {
            document.getElementById('cliente-selecionado').textContent = `${nome} - ${nif}`;
            fecharModalClientes();
        }

        // Alterar quantidade
        function alterarQuantidade(btn, valor) {
            const input = btn.parentElement.querySelector('input');
            const novaQuantidade = Math.max(1, parseInt(input.value) + valor);
            input.value = novaQuantidade;
            calcularTotal();
        }

        // Remover produto
        function removerProduto(btn) {
            if (confirm('Deseja remover este produto?')) {
                btn.closest('li').remove();
                calcularTotal();
            }
        }

        // Calcular totais
        function calcularTotal() {
            const produtos = document.querySelectorAll('#lista-produtos li');
            let subtotal = 0;

            produtos.forEach(produto => {
                const preco = parseFloat(produto.querySelector('.text-gray-600').textContent.replace(/[^\d,]/g, '').replace(',', '.'));
                const quantidade = parseInt(produto.querySelector('input[type="number"]').value);
                subtotal += preco * quantidade;
            });

            const incidencia = subtotal;
            const iva = subtotal * 0.14;
            const total = subtotal + iva;

            document.getElementById('subtotal').textContent = subtotal.toLocaleString('pt-AO', {minimumFractionDigits: 2}) + ' KZ';
            document.getElementById('incidencia').textContent = incidencia.toLocaleString('pt-AO', {minimumFractionDigits: 2}) + ' KZ';
            document.getElementById('iva').textContent = iva.toLocaleString('pt-AO', {minimumFractionDigits: 2}) + ' KZ';
            document.getElementById('total').textContent = total.toLocaleString('pt-AO', {minimumFractionDigits: 2}) + ' KZ';
        }

        // Calcular troco
        function calcularTroco() {
            const totalText = document.getElementById('total').textContent.replace(/[^\d,]/g, '').replace(',', '.');
            const total = parseFloat(totalText);
            const recebidoText = document.getElementById('total-recebido').value.replace(/[^\d,]/g, '').replace(',', '.');
            const recebido = parseFloat(recebidoText) || 0;

            const troco = Math.max(0, recebido - total);
            document.getElementById('troco').textContent = troco.toLocaleString('pt-AO', {minimumFractionDigits: 2}) + ' KZ';
        }

        // Pesquisar produto
        function pesquisarProduto() {
            const termo = document.getElementById('pesquisa-produto').value.toLowerCase();
            const produtos = document.querySelectorAll('#lista-produtos li');

            produtos.forEach(produto => {
                const nome = produto.querySelector('.font-semibold').textContent.toLowerCase();
                produto.style.display = nome.includes(termo) ? '' : 'none';
            });
        }

        // Pesquisar cliente
        function pesquisarCliente() {
            const termo = document.getElementById('pesquisa-cliente').value.toLowerCase();
            const clientes = document.querySelectorAll('#lista-clientes tr');

            clientes.forEach(cliente => {
                const texto = cliente.textContent.toLowerCase();
                cliente.style.display = texto.includes(termo) ? '' : 'none';
            });
        }

        // Finalizar venda
        function finalizarVenda() {
            const cliente = document.getElementById('cliente-selecionado').textContent;
            const total = document.getElementById('total').textContent;

            if (cliente === 'Nenhum cliente selecionado') {
                alert('Por favor, selecione um cliente antes de finalizar a venda.');
                return;
            }

            if (confirm(`Confirmar venda de ${total}?`)) {
                alert('Venda finalizada com sucesso!');
                // Aqui você pode adicionar a lógica para salvar a venda
            }
        }

        // Calcular total inicial
        calcularTotal();
</script>
