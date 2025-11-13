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
    public $totalImpostos = 0;
    public $troco = 0;

    // Resumo de impostos
    public $resumoImpostos = [];
    public $subtotalItems = 0;

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

        if (!$produto) {
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
        if (!isset($this->produtosCarrinho[$index])) {
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

    /**
     * ✅ MÉTODO CALCULAR TOTAIS CORRIGIDO
     * Aplica lógica correta de priorização: Isenção > Imposto > Padrão
     */
    public function calcularTotais()
    {
        // Reset das variáveis
        $this->subtotal = 0;
        $this->totalImpostos = 0;
        $this->resumoImpostos = [];

        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
            $subtotalItem = $item['preco_venda'] * $item['quantidade'];
            $this->subtotal += $subtotalItem;

            // ✅ LÓGICA CORRIGIDA - Prioridade: Isenção > Imposto Específico > Padrão
            if ($produto->motivoIsencao) {
                // 🟢 CASO 1: Produto com ISENÇÃO (ex: Arroz)
                $taxa = 0;
                $motivoIsencao = $produto->motivoIsencao->descricao;
                $descricaoImposto = 'Isento';
                $codigoMotivo = $produto->motivoIsencao->codigo ?? null;

            } elseif ($produto->imposto) {
                // 🟡 CASO 2: Produto com IMPOSTO específico (ex: AGUA)
                $taxa = (float) $produto->imposto->taxa;
                $motivoIsencao = null;
                $descricaoImposto = $produto->imposto->descricao;
                $codigoMotivo = null;

            } else {
                // 🔵 CASO 3: Produto PADRÃO (IVA 14%)
                $taxa = 14;
                $motivoIsencao = null;
                $descricaoImposto = 'IVA';
                $codigoMotivo = null;
            }

            // ✅ CHAVE DE AGRUPAMENTO: taxa + descrição
            $chaveResumo = $taxa . '|' . $descricaoImposto;

            // Inicializa grupo se não existir
            if (!isset($this->resumoImpostos[$chaveResumo])) {
                $this->resumoImpostos[$chaveResumo] = [
                    'taxa' => $taxa,
                    'descricao' => $descricaoImposto,
                    'motivo_isencao' => $motivoIsencao,
                    'codigo_motivo' => $codigoMotivo,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }

            // Acumula incidência
            $this->resumoImpostos[$chaveResumo]['incidencia'] += $subtotalItem;

            // Calcula imposto apenas se taxa > 0
            if ($taxa > 0) {
                $valorImposto = $subtotalItem * ($taxa / 100);
                $this->resumoImpostos[$chaveResumo]['valor_imposto'] += $valorImposto;
                $this->totalImpostos += $valorImposto;
            }
        }

        // Totais gerais
        $this->incidencia = $this->subtotal;
        $this->iva = $this->totalImpostos;
        $this->total = round($this->subtotal + $this->iva - $this->desconto, 2);
        $this->calcularTroco();

        // 🔍 DEBUG (remova em produção)
        if (app()->environment('local')) {
            \Log::info('=== RESUMO IMPOSTOS ===', [
                'resumo' => $this->resumoImpostos,
                'total_impostos' => $this->totalImpostos,
            ]);
        }
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

            $dadosComuns = [
                'cliente_id' => $this->clienteSelecionado,
                'user_id' => auth()->id(),
                'data_emissao' => now(),
                'observacoes' => null,
            ];

            if ($this->tipoDocumento === 'fatura') {
                $fatura = Fatura::create(array_merge($dadosComuns, [
                    'numero' => 'FT-'.date('Ymd').'-'.str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT),
                    'estado' => 'emitida',
                    'subtotal' => $this->subtotal,
                    'total_impostos' => $this->iva,
                    'total' => $this->total,
                ]));

                foreach ($this->produtosCarrinho as $item) {
                    $produto = Produto::find($item['id']);
                    $produto->decrement('estoque', $item['quantidade']);
                }

                $mensagem = "Fatura nº {$fatura->numero} gerada.";
            } elseif ($this->tipoDocumento === 'recibo') {
                $recibo = Recibo::create(array_merge($dadosComuns, [
                    'numero' => 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT),
                    'valor' => $this->total,
                    'metodo_pagamento' => 'dinheiro',
                    'fatura_id' => null,
                ]));

                $mensagem = "Recibo nº {$recibo->numero} gerado.";
            }

            DB::commit();

            session()->flash('success', 'Venda finalizada com sucesso! '.$mensagem);

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

    /**
     * ✅ MÉTODO EXPORTAR PDF CORRIGIDO
     * Garante que produtos e resumo tenham mesma lógica
     */
    public function exportarDadosFatura()
    {
        if (!$this->clienteSelecionado || empty($this->produtosCarrinho)) {
            session()->flash('error', 'Selecione um cliente e adicione produtos antes de exportar.');
            return;
        }

        $cliente = Cliente::find($this->clienteSelecionado);
        $empresa = DadosEmpresa::first();

        // Gera número do documento
        if ($this->tipoDocumento === 'fatura') {
            $numeroDocumento = 'FT-'.date('Ymd').'-'.str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT);
            $tipoLabel = 'Factura';
        } else {
            $numeroDocumento = 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT);
            $tipoLabel = 'Recibo';
        }

        // ✅ PREPARA PRODUTOS COM LÓGICA CORRIGIDA
        $produtosDetalhados = [];
        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);

            $precoUnitario = (float) $item['preco_venda'];
            $quantidade = $item['quantidade'];
            $subtotalProduto = $precoUnitario * $quantidade;

            // ✅ MESMA LÓGICA DO calcularTotais()
            if ($produto->motivoIsencao) {
                $taxaIva = 0;
                $motivoIsencao = $produto->motivoIsencao->descricao;
                $descricaoImposto = 'Isento';
            } elseif ($produto->imposto) {
                $taxaIva = (float) $produto->imposto->taxa;
                $motivoIsencao = null;
                $descricaoImposto = $produto->imposto->descricao;
            } else {
                $taxaIva = 14;
                $motivoIsencao = null;
                $descricaoImposto = 'IVA';
            }

            $ivaValor = $taxaIva > 0 ? ($subtotalProduto * ($taxaIva / 100)) : 0;

            $produtosDetalhados[] = [
                'id' => $produto->id,
                'codigo_barras' => $produto->codigo_barras,
                'descricao' => $produto->descricao,
                'quantidade' => $quantidade,
                'unidade' => 'UN',
                'preco_unitario' => $precoUnitario,
                'desconto' => 0,
                'taxa_iva' => $taxaIva,
                'iva_valor' => $ivaValor,
                'subtotal' => $subtotalProduto,
                'total' => $subtotalProduto + $ivaValor,
                'motivo_isencao' => $motivoIsencao,
                'descricao_imposto' => $descricaoImposto,
            ];
        }

        // ✅ PREPARA RESUMO DE IMPOSTOS (usa o já calculado)
        $resumoImpostosPDF = [];
        foreach ($this->resumoImpostos as $resumo) {
            $resumoImpostosPDF[] = [
                'descricao' => $resumo['descricao'],
                'taxa' => $resumo['taxa'],
                'incidencia' => $resumo['incidencia'],
                'valor_imposto' => $resumo['valor_imposto'],
                'motivo_isencao' => $resumo['motivo_isencao'],
                'codigo_motivo' => $resumo['codigo_motivo'] ?? null,
            ];
        }

        $dados_fatura = [
            'numero' => $numeroDocumento,
            'tipo_documento' => $this->tipoDocumento,
            'tipo_label' => $tipoLabel,
            'natureza' => $this->natureza,
            'data_emissao' => now()->format('Y-m-d'),
            'data_vencimento' => now()->addDays(30)->format('Y-m-d'),
            'moeda' => 'AKZ',
            'condicao_pagamento' => 'Pronto Pagamento',

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

            'cliente' => [
                'id' => $cliente->id,
                'nome' => $cliente->nome,
                'nif' => $cliente->nif,
                'telefone' => $cliente->telefone ?? '',
                'provincia' => $cliente->provincia ?? '',
                'cidade' => $cliente->cidade ?? '',
                'localizacao' => $cliente->localizacao ?? '',
            ],

            'produtos' => $produtosDetalhados,
            'resumo_impostos' => $resumoImpostosPDF,

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

        // 🔍 DEBUG (remova em produção)
        if (app()->environment('local')) {
            \Log::info('=== DADOS PARA PDF ===', $dados_fatura);
        }

        session()->put('dados_fatura', $dados_fatura);
        session()->save();

        return redirect()->route('admin.fatura.download');
    }

    public function render()
    {
        return view('livewire.pov');
    }
}