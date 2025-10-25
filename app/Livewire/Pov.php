<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Produto;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class Pov extends Component
{
    // Propriedades do documento
    public $tipoDocumento = 'fatura';
    public $natureza = 'produto';
    public $Dados;

    // Cliente
    public $clientes = [];
    public $clienteSelecionado = null;
    public $clienteNome = 'Nenhum cliente selecionado';
    public $showModal = false;
    public $searchClienteTerm = '';

    // Produtos
    public $produtos = [];
    public $produtosCarrinho = [];
    public $searchProdutoTerm = '';

    // Financeiro
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
            $query->where('nome', 'like', '%'.$this->searchClienteTerm.'%')
                  ->orWhere('nif', 'like', '%'.$this->searchClienteTerm.'%');
        })->get();
    }

    public function carregarProdutos()
    {
        $this->produtos = Produto::with(['categoria', 'fornecedor'])
            ->when($this->searchProdutoTerm, function ($query) {
                $query->where('descricao', 'like', '%'.$this->searchProdutoTerm.'%')
                      ->orWhere('codigo_barras', 'like', '%'.$this->searchProdutoTerm.'%');
            })
            ->where('estoque', '>', 0)
            ->get();
    }

    public function alterarNatureza($tipo)
    {
        $this->natureza = $tipo;
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
            $this->clienteNome = $cliente->nome.' - '.$cliente->nif;
        }
        $this->fecharModal();
    }

    public function adicionarProduto($produtoId)
    {
        $produto = Produto::find($produtoId);

        if (! $produto) {
            session()->flash('error', 'Produto não encontrado.');
            return;
        }

        $index = collect($this->produtosCarrinho)->search(function ($item) use ($produtoId) {
            return $item['id'] == $produtoId;
        });

        if ($index !== false) {
            $quantidadeAtual = $this->produtosCarrinho[$index]['quantidade'];
            if ($quantidadeAtual < $produto->estoque) {
                $this->produtosCarrinho[$index]['quantidade']++;
            } else {
                session()->flash('error', 'Estoque insuficiente para este produto.');
                return;
            }
        } else {
            $this->produtosCarrinho[] = [
                'id' => $produto->id,
                'descricao' => $produto->descricao,
                'codigo_barras' => $produto->codigo_barras,
                'preco_venda' => (float) $produto->preco_venda,
                'quantidade' => 1,
                'estoque_disponivel' => $produto->estoque,
            ];
        }

        $this->calcularTotais();
    }

    public function alterarQuantidade($index, $valor)
    {
        if (! isset($this->produtosCarrinho[$index])) {
            return;
        }

        $novaQuantidade = $this->produtosCarrinho[$index]['quantidade'] + $valor;
        $estoqueDisponivel = $this->produtosCarrinho[$index]['estoque_disponivel'];

        if ($novaQuantidade >= 1 && $novaQuantidade <= $estoqueDisponivel) {
            $this->produtosCarrinho[$index]['quantidade'] = $novaQuantidade;
            $this->calcularTotais();
        } elseif ($novaQuantidade > $estoqueDisponivel) {
            session()->flash('error', 'Estoque insuficiente. Disponível: '.$estoqueDisponivel);
        }
    }

    public function removerProduto($index)
    {
        if (isset($this->produtosCarrinho[$index])) {
            unset($this->produtosCarrinho[$index]);
            $this->produtosCarrinho = array_values($this->produtosCarrinho);
            $this->calcularTotais();
        }
    }

    public function calcularTotais()
    {
        $this->subtotal = 0;
        foreach ($this->produtosCarrinho as $item) {
            $this->subtotal += ($item['preco_venda'] * $item['quantidade']);
        }

        $this->incidencia = $this->subtotal;
        $this->iva = round($this->subtotal * 0.14, 2);
        $this->total = round($this->subtotal + $this->iva - $this->desconto, 2);

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
        if (! $this->clienteSelecionado) {
            session()->flash('error', 'Por favor, selecione um cliente antes de finalizar a venda.');
            return;
        }

        if (count($this->produtosCarrinho) == 0) {
            session()->flash('error', 'Adicione pelo menos um produto ao carrinho.');
            return;
        }

        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::find($item['id']);
            if ($produto->estoque < $item['quantidade']) {
                session()->flash('error', "Estoque insuficiente para o produto: {$item['descricao']}");
                return;
            }
        }

        session()->flash('success', 'Venda finalizada com sucesso!');

        $this->reset(['produtosCarrinho', 'clienteSelecionado', 'clienteNome', 'totalRecebido', 'desconto']);
        $this->clienteNome = 'Nenhum cliente selecionado';
        $this->calcularTotais();
        $this->carregarProdutos();
    }

    /* exportar PDF usando dompdf */
    public function exportarDadosFatura()
{
    if (! $this->clienteSelecionado || empty($this->produtosCarrinho)) {
        session()->flash('error', 'Selecione um cliente e adicione produtos antes de exportar.');
        return;
    }

    $dados_fatura = [
        'tipo_documento' => $this->tipoDocumento,
        'natureza' => $this->natureza,
        'cliente' => [
            'id' => $this->clienteSelecionado,
            'nome' => $this->clienteNome,
        ],
        'produtos' => $this->produtosCarrinho,
        'financeiro' => [
            'subtotal' => $this->subtotal,
            'incidencia' => $this->incidencia,
            'iva' => $this->iva,
            'total' => $this->total,
            'desconto' => $this->desconto,
            'total_recebido' => $this->totalRecebido,
            'troco' => $this->troco,
        ],
    ];

    // Salva os dados da fatura na sessão para o controller usar
    session(['dados_fatura' => $dados_fatura]);
    // Em Livewire v2: use return redirect()->route(...)
    return redirect()->route('fatura.download');
}

    public function render()
    {
        return view('livewire.pov');
    }
}
