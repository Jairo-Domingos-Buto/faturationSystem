<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Produto;
use Livewire\Component;

class Pov extends Component
{
    // Propriedades do documento
    public $tipoDocumento = 'fatura';
    public $natureza = 'produto';

    // Propriedades do cliente
    public $clientes = [];
    public $clienteSelecionado = null;
    public $clienteNome = 'Nenhum cliente selecionado';
    public $showModal = false;
    public $searchClienteTerm = '';

    // Propriedades dos produtos
    public $produtos = [];
    public $produtosCarrinho = [];
    public $searchProdutoTerm = '';

    // Propriedades financeiras
    public $subtotal = 0;
    public $incidencia = 0;
    public $iva = 0;
    public $total = 0;
    public $totalRecebido = 0;
    public $desconto = 0;
    public $troco = 0;

    public function mount()
    {
        $this->carregarClientes();
        $this->carregarProdutos();
        $this->calcularTotais();
    }

    public function carregarClientes()
    {
        $this->clientes = Cliente::when($this->searchClienteTerm, function ($query) {
            $query->where('nome', 'like', '%' . $this->searchClienteTerm . '%')
                  ->orWhere('nif', 'like', '%' . $this->searchClienteTerm . '%');
        })->get();
    }

    public function carregarProdutos()
    {
        $this->produtos = Produto::with(['categoria', 'fornecedor'])
            ->when($this->searchProdutoTerm, function ($query) {
                $query->where('descricao', 'like', '%' . $this->searchProdutoTerm . '%')
                      ->orWhere('codigo_barras', 'like', '%' . $this->searchProdutoTerm . '%');
            })
            ->where('estoque', '>', 0) // Apenas produtos com estoque
            ->get();
    }

    public function alterarNatureza($tipo)
    {
        $this->natureza = $tipo;
        // Você pode adicionar filtros adicionais aqui se necessário
    }

    public function abrirModal()
    {
        $this->showModal = true;
    }

    public function fecharModal()
    {
        $this->showModal = false;
        $this->searchClienteTerm = '';
    }

    public function selecionarCliente($clienteId)
    {
        $cliente = Cliente::find($clienteId);
        if ($cliente) {
            $this->clienteSelecionado = $cliente->id;
            $this->clienteNome = $cliente->nome . ' - ' . $cliente->nif;
        }
        $this->fecharModal();
    }

    public function adicionarProduto($produtoId)
    {
        $produto = Produto::find($produtoId);

        if (!$produto) {
            session()->flash('error', 'Produto não encontrado.');
            return;
        }

        // Verifica se o produto já está no carrinho
        $index = collect($this->produtosCarrinho)->search(function ($item) use ($produtoId) {
            return $item['id'] == $produtoId;
        });

        if ($index !== false) {
            // Verifica se tem estoque disponível
            $quantidadeAtual = $this->produtosCarrinho[$index]['quantidade'];
            if ($quantidadeAtual < $produto->estoque) {
                $this->produtosCarrinho[$index]['quantidade']++;
            } else {
                session()->flash('error', 'Estoque insuficiente para este produto.');
                return;
            }
        } else {
            // Se não existe, adiciona novo
            $this->produtosCarrinho[] = [
                'id' => $produto->id,
                'descricao' => $produto->descricao,
                'codigo_barras' => $produto->codigo_barras,
                'preco_venda' => $produto->preco_venda,
                'quantidade' => 1,
                'estoque_disponivel' => $produto->estoque
            ];
        }

        $this->calcularTotais();
    }

    public function alterarQuantidade($index, $valor)
    {
        if (isset($this->produtosCarrinho[$index])) {
            $novaQuantidade = $this->produtosCarrinho[$index]['quantidade'] + $valor;
            $estoqueDisponivel = $this->produtosCarrinho[$index]['estoque_disponivel'];

            if ($novaQuantidade >= 1 && $novaQuantidade <= $estoqueDisponivel) {
                $this->produtosCarrinho[$index]['quantidade'] = $novaQuantidade;
                $this->calcularTotais();
            } elseif ($novaQuantidade > $estoqueDisponivel) {
                session()->flash('error', 'Estoque insuficiente. Disponível: ' . $estoqueDisponivel);
            }
        }
    }

    public function removerProduto($index)
    {
        if (isset($this->produtosCarrinho[$index])) {
            unset($this->produtosCarrinho[$index]);
            $this->produtosCarrinho = array_values($this->produtosCarrinho); // Reindexar array
            $this->calcularTotais();
        }
    }

    public function calcularTotais()
    {
        $this->subtotal = 0;

        foreach ($this->produtosCarrinho as $item) {
            $this->subtotal += $item['preco_venda'] * $item['quantidade'];
        }

        $this->incidencia = $this->subtotal;
        $this->iva = $this->subtotal * 0.14;
        $this->total = $this->subtotal + $this->iva - $this->desconto;

        $this->calcularTroco();
    }

    public function calcularTroco()
    {
        $recebido = floatval(str_replace(',', '.', str_replace(' ', '', $this->totalRecebido)));
        $this->troco = max(0, $recebido - $this->total);
    }

    public function updatedTotalRecebido()
    {
        $this->calcularTroco();
    }

    public function updatedSearchClienteTerm()
    {
        $this->carregarClientes();
    }

    public function updatedSearchProdutoTerm()
    {
        $this->carregarProdutos();
    }

    public function finalizarVenda()
    {
        if (!$this->clienteSelecionado) {
            session()->flash('error', 'Por favor, selecione um cliente antes de finalizar a venda.');
            return;
        }

        if (count($this->produtosCarrinho) == 0) {
            session()->flash('error', 'Adicione pelo menos um produto ao carrinho.');
            return;
        }

        // Verificar estoque antes de finalizar
        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::find($item['id']);
            if ($produto->estoque < $item['quantidade']) {
                session()->flash('error', "Estoque insuficiente para o produto: {$item['descricao']}");
                return;
            }
        }

        // Aqui você pode adicionar a lógica para salvar a venda no banco de dados
        // Exemplo:
        // DB::transaction(function () {
        //     $venda = Venda::create([
        //         'cliente_id' => $this->clienteSelecionado,
        //         'tipo_documento' => $this->tipoDocumento,
        //         'subtotal' => $this->subtotal,
        //         'iva' => $this->iva,
        //         'total' => $this->total,
        //         'desconto' => $this->desconto,
        //         'data_venda' => now(),
        //     ]);
        //
        //     foreach ($this->produtosCarrinho as $item) {
        //         // Criar item da venda
        //         $venda->itens()->create([
        //             'produto_id' => $item['id'],
        //             'quantidade' => $item['quantidade'],
        //             'preco_unitario' => $item['preco_venda'],
        //             'subtotal' => $item['preco_venda'] * $item['quantidade'],
        //         ]);
        //
        //         // Atualizar estoque
        //         $produto = Produto::find($item['id']);
        //         $produto->decrement('estoque', $item['quantidade']);
        //     }
        // });

        session()->flash('success', 'Venda finalizada com sucesso!');

        // Limpar o carrinho e resetar valores
        $this->reset(['produtosCarrinho', 'clienteSelecionado', 'clienteNome', 'totalRecebido', 'desconto']);
        $this->clienteNome = 'Nenhum cliente selecionado';
        $this->calcularTotais();
        $this->carregarProdutos(); // Recarregar produtos para atualizar estoque
    }

    public function render()
    {
        return view('livewire.pov');
    }
}