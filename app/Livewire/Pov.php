<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\DadosEmpresa;
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
    public $clienteLocalizacao = '';

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
            $this->clienteNome = $cliente->nome.' - '.$cliente->nif.' - '.$cliente->localizacao;
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
    if (!$this->clienteSelecionado || empty($this->produtosCarrinho)) {
        session()->flash('error', 'Selecione um cliente e adicione produtos antes de exportar.');
        return;
    }

    // Busca dados completos do cliente
    $cliente = Cliente::find($this->clienteSelecionado);
    
    // Busca dados da empresa
    $empresa = DadosEmpresa::first();

    // Gera número do documento baseado no tipo
    if ($this->tipoDocumento === 'fatura') {
        $numeroDocumento = 'FT-' . date('Ymd') . '-' . str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT);
        $tipoLabel = 'Factura';
    } else {
        $numeroDocumento = 'RC-' . date('Ymd') . '-' . str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT);
        $tipoLabel = 'Recibo';
    }

    // Prepara produtos com todos os detalhes
    $produtosDetalhados = [];
    foreach ($this->produtosCarrinho as $item) {
        $produto = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
        
        $precoUnitario = (float) $item['preco_venda'];
        $quantidade = $item['quantidade'];
        $subtotalProduto = $precoUnitario * $quantidade;
        
        // Calcula IVA do produto (se aplicável)
        $taxaIva = $produto->imposto ? $produto->imposto->taxa : 14;
        $ivaValor = $subtotalProduto * ($taxaIva / 100);
        
        $produtosDetalhados[] = [
            'id' => $produto->id,
            'codigo_barras' => $produto->codigo_barras,
            'descricao' => $produto->descricao,
            'quantidade' => $quantidade,
            'unidade' => 'UN', // Adicione campo unidade na tabela se necessário
            'preco_unitario' => $precoUnitario,
            'desconto' => 0, // Implemente desconto por produto se necessário
            'taxa_iva' => $taxaIva,
            'iva_valor' => $ivaValor,
            'subtotal' => $subtotalProduto,
            'total' => $subtotalProduto + $ivaValor,
            'motivo_isencao' => $produto->motivoIsencao ? $produto->motivoIsencao->descricao : null,
        ];
    }

    $dados_fatura = [
        // Informações do documento
        'numero' => $numeroDocumento,
        'tipo_documento' => $this->tipoDocumento,
        'natureza' => $this->natureza,
        'data_emissao' => now()->format('Y-m-d'),
        'data_vencimento' => now()->addDays(30)->format('Y-m-d'), // 30 dias após emissão
        'moeda' => 'AKZ',
        'condicao_pagamento' => 'Pronto Pagamento',
        
        // Dados da empresa
        'empresa' => [
            'nome' => $empresa->name ?? '',
            'nif' => $empresa->nif ?? '',
            'telefone' => $empresa->telefone ?? '',
            'email' => $empresa->email ?? '',
            'website' => $empresa->website ?? '',
            'rua' => $empresa->rua ?? '',
            'edificio' => $empresa->edificio ?? '',
            'cidade' => $empresa->cidade ?? '',
            'municipio' => $empresa->municipio ?? '',
            'localizacao' => $empresa->localizacao ?? '',
            'regime' => $empresa->regime ?? '',
            'banco' => $empresa->nomeDoBanco ?? '',
            'iban' => $empresa->iban ?? '',
        ],
        
        // Dados do cliente
        'cliente' => [
            'id' => $cliente->id,
            'nome' => $cliente->nome,
            'nif' => $cliente->nif,
            'telefone' => $cliente->telefone ?? '',
            'provincia' => $cliente->provincia ?? '',
            'cidade' => $cliente->cidade ?? '',
            'localizacao' => $cliente->localizacao ?? '',
        ],
        
        // Produtos
        'produtos' => $produtosDetalhados,
        
        // Resumo financeiro
        'financeiro' => [
            'subtotal' => $this->subtotal,
            'incidencia' => $this->incidencia,
            'iva' => $this->iva,
            'desconto' => $this->desconto,
            'total' => $this->total,
            'total_recebido' => $this->totalRecebido,
            'troco' => $this->troco,
        ],
    ];

    // Grava na sessão
    session()->put('dados_fatura', $dados_fatura);
    session()->save();

    return redirect()->route('admin.fatura.download');
}
    public function render()
    {
        return view('livewire.pov');
    }
}
