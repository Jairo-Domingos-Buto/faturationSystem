<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\DadosEmpresa;
use App\Models\Fatura;
use App\Models\FaturaItem;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\ReciboItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pov extends Component
{
    // Propriedades do documento
    public $tipoDocumento = 'fatura';

    public $natureza = 'produto';

    public $metodoPagamento;

    // ✅ NOVO: Modo retificação
    public $modoRetificacao = false;

    public $documentoOriginalId = null;

    public $documentoOriginalNumero = null;

    public $motivoRetificacao = '';

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

    public $resumoImpostos = [];

    public function mount()
    {
        $this->carregarClientes();
        $this->carregarProdutos();

        // ✅ Verificar se veio ID para retificar
        $retificar_id = request()->get('retificar_id');
        $tipo = request()->get('tipo');

        if ($retificar_id && $tipo) {
            $this->carregarDocumentoParaRetificacao($retificar_id, $tipo);
        } else {
            $this->calcularTotais();
        }
    }

    /**
     * ✅ NOVO: Carregar documento para retificação
     */
    public function carregarDocumentoParaRetificacao($id, $tipo)
    {
        try {
            if ($tipo === 'fatura') {
                $documento = Fatura::with(['cliente', 'items.produto'])->findOrFail($id);

                if (! $documento->pode_ser_retificada) {
                    session()->flash('error', 'Esta fatura não pode ser retificada.');

                    return redirect()->route('admin.notas-credito');
                }

                $this->tipoDocumento = 'fatura';

            } elseif ($tipo === 'recibo') {
                $documento = Recibo::with(['cliente', 'items.produto'])->findOrFail($id);

                if (! $documento->pode_ser_retificado) {
                    session()->flash('error', 'Este recibo não pode ser retificado.');

                    return redirect()->route('admin.notas-credito');
                }

                $this->tipoDocumento = 'recibo';
            } else {
                session()->flash('error', 'Tipo de documento inválido.');

                return redirect()->back();
            }

            // Ativa modo retificação
            $this->modoRetificacao = true;
            $this->documentoOriginalId = $documento->id;
            $this->documentoOriginalNumero = $documento->numero;

            // Carrega cliente
            $this->selecionarCliente($documento->cliente_id);

            // Carrega produtos no carrinho
            foreach ($documento->items as $item) {
                $produto = $item->produto;

                $this->produtosCarrinho[] = [
                    'id' => $produto->id,
                    'descricao' => $produto->descricao,
                    'codigo_barras' => $produto->codigo_barras,
                    'preco_venda' => (float) $item->preco_unitario,
                    'quantidade' => $item->quantidade,
                    'estoque_disponivel' => $produto->estoque + $item->quantidade,
                ];
            }

            $this->calcularTotais();

            session()->flash('info', "Modo Retificação: {$documento->numero}");

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao carregar documento: '.$e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * ✅ NOVO: Cancelar retificação
     */
    public function cancelarRetificacao()
    {
        $this->reset([
            'modoRetificacao',
            'documentoOriginalId',
            'documentoOriginalNumero',
            'motivoRetificacao',
            'produtosCarrinho',
            'clienteSelecionado',
            'clienteNome',
        ]);

        $this->clienteNome = 'Nenhum cliente selecionado';
        $this->calcularTotais();

        session()->flash('info', 'Retificação cancelada.');
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
            $estoqueDisponivel = $this->produtosCarrinho[$index]['estoque_disponivel'];

            if ($quantidadeAtual < $estoqueDisponivel) {
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
        $this->totalImpostos = 0;
        $this->resumoImpostos = [];

        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
            $subtotalItem = $item['preco_venda'] * $item['quantidade'];
            $this->subtotal += $subtotalItem;

            if ($produto->motivoIsencao) {
                $taxa = 0;
                $motivoIsencao = $produto->motivoIsencao->descricao;
                $descricaoImposto = 'Isento';
                $codigoMotivo = $produto->motivoIsencao->codigo ?? null;
            } elseif ($produto->imposto) {
                $taxa = (float) $produto->imposto->taxa;
                $motivoIsencao = null;
                $descricaoImposto = $produto->imposto->descricao;
                $codigoMotivo = null;
            } else {
                $taxa = 14;
                $motivoIsencao = null;
                $descricaoImposto = 'IVA';
                $codigoMotivo = null;
            }

            $chaveResumo = $taxa.'|'.$descricaoImposto;

            if (! isset($this->resumoImpostos[$chaveResumo])) {
                $this->resumoImpostos[$chaveResumo] = [
                    'taxa' => $taxa,
                    'descricao' => $descricaoImposto,
                    'motivo_isencao' => $motivoIsencao,
                    'codigo_motivo' => $codigoMotivo,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }

            $this->resumoImpostos[$chaveResumo]['incidencia'] += $subtotalItem;

            if ($taxa > 0) {
                $valorImposto = $subtotalItem * ($taxa / 100);
                $this->resumoImpostos[$chaveResumo]['valor_imposto'] += $valorImposto;
                $this->totalImpostos += $valorImposto;
            }
        }

        $this->incidencia = $this->subtotal;
        $this->iva = $this->totalImpostos;
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

    /**
     * ✅ ATUALIZADO: Finalizar venda ou retificação
     */
    public function finalizarVenda()
    {
        if (! $this->clienteSelecionado) {
            session()->flash('error', 'Por favor, selecione um cliente antes de finalizar.');

            return;
        }

        if (count($this->produtosCarrinho) == 0) {
            session()->flash('error', 'Adicione pelo menos um produto ao carrinho.');

            return;
        }

        if ($this->modoRetificacao && empty($this->motivoRetificacao)) {
            session()->flash('error', 'Por favor, informe o motivo da retificação.');

            return;
        }

        try {
            DB::beginTransaction();

            if ($this->modoRetificacao) {
                $this->processarRetificacao();
            } else {
                $this->processarVendaNormal();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao finalizar: '.$e->getMessage());
        }
    }

    /**
     * ✅ NOVO: Processar venda normal
     */
    private function processarVendaNormal()
    {
        $dadosComuns = [
            'cliente_id' => $this->clienteSelecionado,
            'user_id' => auth()->id(),
            'data_emissao' => now(),
            'observacoes' => null,
        ];

        if ($this->tipoDocumento === 'fatura') {
            $documento = Fatura::create(array_merge($dadosComuns, [
                'numero' => 'FT-'.date('Ymd').'-'.str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT),
                'estado' => 'emitida',
                'subtotal' => $this->subtotal,
                'total_impostos' => $this->iva,
                'total' => $this->total,
            ]));

            $this->salvarItens($documento, FaturaItem::class, 'fatura_id');
            $mensagem = "Fatura nº {$documento->numero} gerada com sucesso!";

        } else {
            $documento = Recibo::create(array_merge($dadosComuns, [
                'numero' => 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT),
                'valor' => $this->total,
                'metodo_pagamento' => 'dinheiro',
            ]));

            $this->salvarItens($documento, ReciboItem::class, 'recibo_id');
            $mensagem = "Recibo nº {$documento->numero} gerado com sucesso!";
        }

        $this->atualizarEstoque('decrementar');

        session()->flash('success', $mensagem);
        $this->resetarFormulario();
    }

    /**
     * ✅ NOVO: Processar retificação
     */
    private function processarRetificacao()
    {
        if ($this->tipoDocumento === 'fatura') {
            $documentoOriginal = Fatura::with('items')->findOrFail($this->documentoOriginalId);

            // Devolve estoque da fatura original
            foreach ($documentoOriginal->items as $item) {
                $produto = Produto::find($item->produto_id);
                $produto->increment('estoque', $item->quantidade);
            }

            // Cria nova fatura
            $novoDocumento = Fatura::create([
                'numero' => 'FT-'.date('Ymd').'-'.str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT),
                'cliente_id' => $this->clienteSelecionado,
                'user_id' => auth()->id(),
                'data_emissao' => now(),
                'estado' => 'emitida',
                'subtotal' => $this->subtotal,
                'total_impostos' => $this->iva,
                'total' => $this->total,
                'fatura_original_id' => $this->documentoOriginalId,
            ]);

            $this->salvarItens($novoDocumento, FaturaItem::class, 'fatura_id');
            $documentoOriginal->marcarComoRetificada($novoDocumento->id, $this->motivoRetificacao);

            $mensagem = "Fatura {$documentoOriginal->numero} retificada! Nova fatura: {$novoDocumento->numero}";

        } else {
            $documentoOriginal = Recibo::with('items')->findOrFail($this->documentoOriginalId);

            foreach ($documentoOriginal->items as $item) {
                $produto = Produto::find($item->produto_id);
                $produto->increment('estoque', $item->quantidade);
            }

            $novoDocumento = Recibo::create([
                'numero' => 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT),
                'cliente_id' => $this->clienteSelecionado,
                'user_id' => auth()->id(),
                'data_emissao' => now(),
                'valor' => $this->total,
                'metodo_pagamento' => 'dinheiro',
                'recibo_original_id' => $this->documentoOriginalId,
            ]);

            $this->salvarItens($novoDocumento, ReciboItem::class, 'recibo_id');
            $documentoOriginal->marcarComoRetificado($novoDocumento->id, $this->motivoRetificacao);

            $mensagem = "Recibo {$documentoOriginal->numero} retificado! Novo recibo: {$novoDocumento->numero}";
        }

        $this->atualizarEstoque('decrementar');

        session()->flash('success', $mensagem);
        $this->resetarFormulario();
    }

    /**
     * ✅ NOVO: Salvar itens do documento
     */
    private function salvarItens($documento, $modelClass, $foreignKey)
    {
        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);

            if ($produto->motivoIsencao) {
                $taxaIva = 0;
                $impostoId = null;
                $motivoId = $produto->motivo_isencaos_id;
            } elseif ($produto->imposto) {
                $taxaIva = (float) $produto->imposto->taxa;
                $impostoId = $produto->imposto_id;
                $motivoId = null;
            } else {
                $taxaIva = 14;
                $impostoId = null;
                $motivoId = null;
            }

            $subtotal = $item['preco_venda'] * $item['quantidade'];
            $valorIva = $taxaIva > 0 ? ($subtotal * ($taxaIva / 100)) : 0;

            $modelClass::create([
                $foreignKey => $documento->id,
                'produto_id' => $produto->id,
                'descricao' => $produto->descricao,
                'codigo_barras' => $produto->codigo_barras,
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco_venda'],
                'subtotal' => $subtotal,
                'taxa_iva' => $taxaIva,
                'valor_iva' => $valorIva,
                'total' => $subtotal + $valorIva,
                'imposto_id' => $impostoId,
                'motivo_isencaos_id' => $motivoId,
            ]);
        }
    }

    /**
     * ✅ NOVO: Atualizar estoque
     */
    private function atualizarEstoque($acao = 'decrementar')
    {
        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::find($item['id']);

            if ($acao === 'decrementar') {
                $produto->decrement('estoque', $item['quantidade']);
            } else {
                $produto->increment('estoque', $item['quantidade']);
            }
        }
    }

    /**
     * ✅ NOVO: Resetar formulário
     */
    private function resetarFormulario()
    {
        $this->reset([
            'produtosCarrinho',
            'clienteSelecionado',
            'clienteNome',
            'totalRecebido',
            'desconto',
            'modoRetificacao',
            'documentoOriginalId',
            'documentoOriginalNumero',
            'motivoRetificacao',
        ]);

        $this->clienteNome = 'Nenhum cliente selecionado';
        $this->calcularTotais();
        $this->carregarProdutos();
    }

    public function exportarDadosFatura()
    {
        if (! $this->clienteSelecionado || empty($this->produtosCarrinho)) {
            session()->flash('error', 'Selecione um cliente e adicione produtos antes de exportar.');

            return;
        }

        $cliente = Cliente::find($this->clienteSelecionado);
        $empresa = DadosEmpresa::first();

        if ($this->tipoDocumento === 'fatura') {
            $numeroDocumento = 'FT-'.date('Ymd').'-'.str_pad(Fatura::count() + 1, 4, '0', STR_PAD_LEFT);
            $tipoLabel = 'Factura';
        } else {
            $numeroDocumento = 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT);
            $tipoLabel = 'Recibo';
        }

        $produtosDetalhados = [];
        foreach ($this->produtosCarrinho as $item) {
            $produto = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);

            $precoUnitario = (float) $item['preco_venda'];
            $quantidade = $item['quantidade'];
            $subtotalProduto = $precoUnitario * $quantidade;

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

        session()->put('dados_fatura', $dados_fatura);
        session()->save();

        return redirect()->route('admin.fatura.download');
    }

    public function render()
    {
        return view('livewire.pov');
    }
}
