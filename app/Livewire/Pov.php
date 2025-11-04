<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Fatura;
use App\Models\Produto;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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

        // Verifica estoque
        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::find($item['id']);
            if ($produto->estoque < $item['quantidade']) {
                session()->flash('error', "Estoque insuficiente para o produto: {$item['descricao']}");

                return;
            }
        }

        try {
            DB::beginTransaction();

            // Dados comuns
            $dadosComuns = [
                'cliente_id' => $this->clienteSelecionado,
                'user_id' => auth()->id(),
                'data_emissao' => now(),
                'observacoes' => null,
            ];

            // Se for Fatura
            if ($this->tipoDocumento === 'fatura') {
                $fatura = Fatura::create(array_merge($dadosComuns, [
                    'numero' => 'FT-'.date('Ymd').'-'.str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT),
                    'estado' => 'emitida',
                    'subtotal' => $this->subtotal,
                    'total_impostos' => $this->iva,
                    'total' => $this->total,
                ]));

                // Atualiza estoque dos produtos
                foreach ($this->produtosCarrinho as $item) {
                    $produto = Produto::find($item['id']);
                    $produto->decrement('estoque', $item['quantidade']);
                }

                $mensagem = "Fatura nº {$fatura->numero} gerada.";
            }
            // Se for Recibo
            elseif ($this->tipoDocumento === 'recibo') {
                $recibo = Recibo::create(array_merge($dadosComuns, [
                    'numero' => 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT),
                    'valor' => $this->total,
                    'metodo_pagamento' => 'dinheiro', // Você pode adicionar um campo para selecionar o método
                    'fatura_id' => null, // Se estiver vinculado a uma fatura específica
                ]));

                $mensagem = "Recibo nº {$recibo->numero} gerado.";
            }

            DB::commit();

            session()->flash('success', 'Venda finalizada com sucesso! '.$mensagem);

            // Limpa o carrinho e reseta os dados
            $this->reset([
                'produtosCarrinho',
                'clienteSelecionado',
                'clienteNome',
                'totalRecebido',
                'desconto',
            ]);

            $this->clienteNome = 'Nenhum cliente selecionado';
            $this->calcularTotais();
            $this->carregarProdutos();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao finalizar venda: '.$e->getMessage());
        }
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

        // redireciona para o endpoint de download (rota web)
        return redirect('admin/fatura/download');
    }

    public function render()
    {
        return view('livewire.pov');
    }
}
