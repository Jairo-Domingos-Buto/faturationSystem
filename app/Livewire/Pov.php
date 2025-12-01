<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Fatura;
use App\Models\FaturaItem;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\ReciboItem;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pov extends Component
{
    // --- Configuração do Documento ---
    public $tipoDocumento = 'FT'; // Opções: FT, FR, FP ou RC

    public $natureza = 'produto'; // 'produto' ou 'servico'

    // --- Dados de Pagamento e Datas ---
    public $metodoPagamento = 'dinheiro';

    public $dataVencimento;

    public $totalRecebido = 0;

    public $troco = 0;

    // --- Modo Retificação ---
    public $modoRetificacao = false;

    public $documentoOriginalId = null;

    public $documentoOriginalNumero = null;

    public $motivoRetificacao = '';

    // --- Cliente ---
    public $clientes = [];

    public $clienteSelecionado = null;

    public $clienteNome = 'Nenhum cliente selecionado';

    public $searchClienteTerm = '';

    public $showModal = false;

    // --- Listagens e Carrinho ---
    public $produtos = []; // Lista de produtos do DB

    public $servicos = []; // Lista de serviços do DB

    public $produtosCarrinho = []; // Itens no carrinho (Misturados)

    public $searchProdutoTerm = '';

    // --- Financeiro ---
    public $subtotal = 0;

    public $incidencia = 0;

    public $iva = 0;

    public $total = 0;

    public $desconto = 0;

    public $totalImpostos = 0;

    public $resumoImpostos = [];

    public function mount()
    {
        // Define vencimento padrão (30 dias)
        $this->dataVencimento = Carbon::now()->addDays(30)->format('Y-m-d');

        $this->carregarClientes();
        $this->carregarProdutos();
        $this->carregarServicos();

        // Verificar parâmetros de retificação via URL
        $retificar_id = request()->get('retificar_id');
        $tipo = request()->get('tipo');

        if ($retificar_id && $tipo) {
            $this->carregarDocumentoParaRetificacao($retificar_id, $tipo);
        } else {
            $this->calcularTotais();
        }
    }

    // =========================================================================
    // WATCHERS (ATUALIZAÇÃO EM TEMPO REAL)
    // =========================================================================

    public function updatedTipoDocumento($value)
    {
        if ($value === 'FR' || $value === 'RC') {
            // Pagamento imediato
            $this->dataVencimento = Carbon::now()->format('Y-m-d');
        } else {
            // Prazo padrão
            $this->dataVencimento = Carbon::now()->addDays(30)->format('Y-m-d');
        }
        $this->calcularTroco();
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
        if ($this->natureza === 'produto') {
            $this->carregarProdutos();
        } else {
            $this->carregarServicos();
        }
    }

    public function updatedNatureza()
    {
        $this->searchProdutoTerm = ''; // Limpa busca ao trocar aba
        if ($this->natureza === 'produto') {
            $this->carregarProdutos();
        } else {
            $this->carregarServicos();
        }
    }

    // =========================================================================
    // LÓGICA DO CARRINHO E TOTAIS
    // =========================================================================

    public function calcularTotais()
    {
        $this->subtotal = 0;
        $this->totalImpostos = 0;
        $this->resumoImpostos = [];

        foreach ($this->produtosCarrinho as $item) {
            $entidade = null;

            // Definição da Entidade Baseada na Natureza do Item do Carrinho
            if (isset($item['natureza']) && $item['natureza'] === 'servico') {
                $entidade = Servico::find($item['id']);
            } else {
                $entidade = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
            }

            // Se o item foi deletado do banco, ignoramos o calculo
            if (! $entidade) {
                continue;
            }

            $subtotalItem = $item['preco_venda'] * $item['quantidade'];
            $this->subtotal += $subtotalItem;

            // --- LÓGICA DE IMPOSTOS ---
            $taxa = 14;
            $descricaoImposto = 'IVA';
            $motivoIsencao = null;
            $codigoMotivo = null;

            if ($item['natureza'] === 'servico') {
                // SERVIÇO: Forçar isenção (conforme regra de negócio)
                $taxa = 0;
                $descricaoImposto = 'Isento';
                $motivoIsencao = 'Prestação de Serviços';
                $codigoMotivo = 'M02'; // Ajuste conforme tabela da AGT
            } else {
                // PRODUTO: Lógica normal do cadastro
                if ($entidade->motivoIsencao) {
                    $taxa = 0;
                    $descricaoImposto = 'Isento';
                    $motivoIsencao = $entidade->motivoIsencao->descricao;
                    $codigoMotivo = $entidade->motivoIsencao->codigo;
                } elseif ($entidade->imposto) {
                    $taxa = (float) $entidade->imposto->taxa;
                    $descricaoImposto = $entidade->imposto->descricao;
                }
            }

            // Agrupamento para resumo fiscal
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

        if (in_array($this->tipoDocumento, ['FR', 'RC']) && $recebido > 0) {
            $this->troco = max(0, $recebido - $this->total);
        } else {
            $this->troco = 0;
        }
    }

    // --- Adição de Itens ---

    public function adicionarProduto($produtoId)
    {
        $produto = Produto::find($produtoId);
        if (! $produto) {
            return;
        }

        $permiteSemEstoque = ($this->tipoDocumento === 'FP'); // Proforma aceita sem stock

        $index = collect($this->produtosCarrinho)->search(fn ($item) => $item['id'] == $produtoId && $item['natureza'] === 'produto'
        );

        if ($index !== false) {
            // Item já existe no carrinho, incrementa
            $qtdAtual = $this->produtosCarrinho[$index]['quantidade'];
            $estoqueDisponivel = $this->produtosCarrinho[$index]['estoque_disponivel'];

            if ($permiteSemEstoque || $qtdAtual < $estoqueDisponivel) {
                $this->produtosCarrinho[$index]['quantidade']++;
            } else {
                session()->flash('error', 'Estoque insuficiente.');

                return;
            }
        } else {
            // Item novo
            if (! $permiteSemEstoque && $produto->estoque <= 0) {
                session()->flash('error', 'Produto sem estoque.');

                return;
            }

            $this->produtosCarrinho[] = [
                'id' => $produto->id,
                'descricao' => $produto->descricao,
                'codigo_barras' => $produto->codigo_barras,
                'preco_venda' => (float) $produto->preco_venda,
                'quantidade' => 1,
                'estoque_disponivel' => $produto->estoque,
                'natureza' => 'produto', // IMPORTANTE
            ];
        }

        $this->calcularTotais();
    }

    public function adicionarServico($servicoId)
    {
        $servico = Servico::find($servicoId);
        if (! $servico) {
            return;
        }

        // Procura se já tem esse serviço no carrinho (baseado em ID E Natureza)
        $index = collect($this->produtosCarrinho)->search(fn ($item) => $item['id'] == $servicoId && $item['natureza'] === 'servico'
        );

        if ($index !== false) {
            $this->produtosCarrinho[$index]['quantidade']++;
        } else {
            $this->produtosCarrinho[] = [
                'id' => $servico->id,
                'descricao' => $servico->descricao,
                'codigo_barras' => '', // Serviços não têm EAN
                'preco_venda' => (float) ($servico->valor ?? $servico->preco_venda),
                'quantidade' => 1,
                'estoque_disponivel' => 999999, // Stock infinito
                'natureza' => 'servico', // IMPORTANTE
            ];
        }

        $this->calcularTotais();
    }

    public function alterarQuantidade($index, $valor)
    {
        if (! isset($this->produtosCarrinho[$index])) {
            return;
        }

        $item = $this->produtosCarrinho[$index];
        $nova = $item['quantidade'] + $valor;
        $est = $item['estoque_disponivel'];

        // Se for serviço ou proforma, ignora limite de stock
        $ignorarStock = ($this->tipoDocumento === 'FP' || $item['natureza'] === 'servico');

        if ($nova > 0) {
            if ($ignorarStock || $nova <= $est) {
                $this->produtosCarrinho[$index]['quantidade'] = $nova;
                $this->calcularTotais();
            } else {
                session()->flash('error', 'Quantidade excede o estoque disponível.');
            }
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

    // =========================================================================
    // FINALIZAÇÃO DE VENDA
    // =========================================================================

    public function finalizarVenda()
    {
        if (! $this->clienteSelecionado) {
            session()->flash('error', 'Selecione um cliente.');

            return;
        }
        if (empty($this->produtosCarrinho)) {
            session()->flash('error', 'Carrinho vazio.');

            return;
        }
        if ($this->modoRetificacao && empty($this->motivoRetificacao)) {
            session()->flash('error', 'Informe o motivo da retificação.');

            return;
        }

        // Validação Fatura-Recibo e Recibo (Dinheiro)
        if (in_array($this->tipoDocumento, ['FR', 'RC']) && $this->metodoPagamento === 'dinheiro') {
            $recebido = floatval(str_replace(',', '.', str_replace(' ', '', $this->totalRecebido)));
            if ($recebido < $this->total) {
                session()->flash('error', 'Valor recebido inferior ao total.');

                return;
            }
        }

        try {
            DB::beginTransaction();

            if ($this->modoRetificacao) {
                $this->processarRetificacao();
            } else {
                $this->processarNovoDocumento();
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Erro ao processar: '.$e->getMessage());
        }
    }

    private function processarNovoDocumento()
    {
        // 1. Caso Especial: Recibo Isolado (RC)
        if ($this->tipoDocumento === 'RC') {
            $this->gerarReciboIsolado();

            return;
        }

        // 2. Faturas (FT, FR, FP)
        $numeroGerado = Fatura::gerarProximoNumero($this->tipoDocumento);
        $estado = ($this->tipoDocumento === 'FR') ? 'paga' : 'emitida';

        $doc = Fatura::create([
            'numero' => $numeroGerado,
            'tipo_documento' => $this->tipoDocumento,
            'cliente_id' => $this->clienteSelecionado,
            'user_id' => auth()->id(),
            'data_emissao' => now(),
            'data_vencimento' => $this->dataVencimento ?: now(),
            'estado' => $estado,
            'metodo_pagamento' => ($this->tipoDocumento === 'FR') ? $this->metodoPagamento : null,
            'subtotal' => $this->subtotal,
            'total_impostos' => $this->iva,
            'total' => $this->total,
            'convertida' => false,
        ]);

        $this->salvarItensFatura($doc);

        // Movimentação de Estoque (Se não for Proforma e não for Serviço)
        if ($this->tipoDocumento !== 'FP') {
            $this->atualizarEstoque('decrementar');
        }

        session()->flash('success', "{$this->tipoDocumento} {$numeroGerado} emitido com sucesso!");
        $this->resetarFormulario();
    }

    private function gerarReciboIsolado()
    {
        $numero = 'RC-'.date('Ymd').'-'.str_pad(Recibo::count() + 1, 4, '0', STR_PAD_LEFT);

        $recibo = Recibo::create([
            'numero' => $numero,
            'cliente_id' => $this->clienteSelecionado,
            'user_id' => auth()->id(),
            'data_emissao' => now(),
            'valor' => $this->total,
            'metodo_pagamento' => $this->metodoPagamento,
        ]);

        $this->salvarItensRecibo($recibo);

        session()->flash('success', "Recibo {$numero} gerado!");
        $this->resetarFormulario();
    }

    // =========================================================================
    // RETIFICAÇÃO (Lógica Mista Produto/Serviço)
    // =========================================================================

    private function processarRetificacao()
    {
        if ($this->tipoDocumento === 'RC' || $this->tipoDocumento === 'recibo') {
            // --- RETIFICAR RECIBO ---
            $original = Recibo::with('items')->findOrFail($this->documentoOriginalId);

            // Devolve estoque apenas de PRODUTOS (Ignora serviços)
            $this->devolverEstoqueRecibo($original);

            $novoNumero = 'RC-RECT-'.date('Ymd').'-'.rand(1000, 9999);
            $novo = Recibo::create([
                'numero' => $novoNumero,
                'cliente_id' => $this->clienteSelecionado,
                'user_id' => auth()->id(),
                'data_emissao' => now(),
                'valor' => $this->total,
                'metodo_pagamento' => $this->metodoPagamento,
                'recibo_original_id' => $this->documentoOriginalId,
                'observacoes' => 'Retificação: '.$this->motivoRetificacao,
            ]);

            $this->salvarItensRecibo($novo);

            // Baixa novo estoque (se houver novos produtos físicos adicionados)
            $this->atualizarEstoque('decrementar');

            $original->marcarComoRetificado($novo->id, $this->motivoRetificacao);

        } else {
            // --- RETIFICAR FATURA (FT/FR/FP) ---
            $original = Fatura::with('items')->findOrFail($this->documentoOriginalId);

            // Devolve estoque antigo (Apenas Produtos físicos)
            if ($original->tipo_documento !== 'FP') {
                $original->devolverEstoque(); // Esta função no Model Fatura deve verificar se é produto
            }

            // Gera nova fatura
            $tipo = $original->tipo_documento;
            $novoNumero = Fatura::gerarProximoNumero($tipo);

            $novoDoc = Fatura::create([
                'numero' => $novoNumero,
                'tipo_documento' => $tipo,
                'cliente_id' => $this->clienteSelecionado,
                'user_id' => auth()->id(),
                'data_emissao' => now(),
                'data_vencimento' => $this->dataVencimento,
                'estado' => $original->tipo_documento === 'FR' ? 'paga' : 'emitida',
                'metodo_pagamento' => $original->tipo_documento === 'FR' ? $this->metodoPagamento : null,
                'subtotal' => $this->subtotal,
                'total_impostos' => $this->iva,
                'total' => $this->total,
                'fatura_original_id' => $this->documentoOriginalId,
                'observacoes' => 'Retificação: '.$this->motivoRetificacao,
            ]);

            $this->salvarItensFatura($novoDoc);

            // Baixa novo estoque (Apenas produtos fisicos)
            if ($tipo !== 'FP') {
                $this->atualizarEstoque('decrementar');
            }

            $original->marcarComoRetificada($novoDoc->id, $this->motivoRetificacao);
        }

        session()->flash('success', 'Documento retificado com sucesso.');
        $this->resetarFormulario();
    }

    // =========================================================================
    // SALVAMENTO NO BANCO (Itens com suporte a ID Misto)
    // =========================================================================

    private function salvarItensFatura(Fatura $documento)
    {
        foreach ($this->produtosCarrinho as $item) {
            $entidade = ($item['natureza'] === 'servico')
                ? Servico::find($item['id'])
                : Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);

            if ($entidade) {
                $this->criarItem($documento->id, $entidade, $item, FaturaItem::class, 'fatura_id');
            }
        }
    }

    private function salvarItensRecibo(Recibo $recibo)
    {
        foreach ($this->produtosCarrinho as $item) {
            $entidade = ($item['natureza'] === 'servico')
               ? Servico::find($item['id'])
               : Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);

            if ($entidade) {
                $this->criarItem($recibo->id, $entidade, $item, ReciboItem::class, 'recibo_id');
            }
        }
    }

    private function criarItem($docId, $entidade, $itemCarrinho, $modelClass, $fk)
    {
        // Padrão Serviço
        if ($itemCarrinho['natureza'] === 'servico') {
            $taxa = 0;
            $motivoId = 1; // Ajuste ID do motivo "Transmissão Isenta"
            $impostoId = null;
        } else {
            // Padrão Produto
            if ($entidade->motivoIsencao) {
                $taxa = 0;
                $motivoId = $entidade->motivo_isencaos_id;
                $impostoId = null;
            } elseif ($entidade->imposto) {
                $taxa = (float) $entidade->imposto->taxa;
                $impostoId = $entidade->imposto_id;
                $motivoId = null;
            } else {
                $taxa = 14;
                $impostoId = null;
                $motivoId = null;
            }
        }

        $subtotal = $itemCarrinho['preco_venda'] * $itemCarrinho['quantidade'];
        $valorIva = $taxa > 0 ? ($subtotal * ($taxa / 100)) : 0;

        $modelClass::create([
            $fk => $docId,
            // Preenche um e deixa o outro null
            'produto_id' => ($itemCarrinho['natureza'] === 'produto') ? $entidade->id : null,
            'servico_id' => ($itemCarrinho['natureza'] === 'servico') ? $entidade->id : null,
            'descricao' => $entidade->descricao,
            'codigo_barras' => $itemCarrinho['codigo_barras'],
            'quantidade' => $itemCarrinho['quantidade'],
            'preco_unitario' => $itemCarrinho['preco_venda'],
            'subtotal' => $subtotal,
            'taxa_iva' => $taxa,
            'valor_iva' => $valorIva,
            'total' => $subtotal + $valorIva,
            'imposto_id' => $impostoId,
            'motivo_isencaos_id' => $motivoId,
        ]);
    }

    // =========================================================================
    // CONTROLE DE ESTOQUE (SEGURO PARA SERVIÇOS)
    // =========================================================================

    private function atualizarEstoque($acao)
    {
        foreach ($this->produtosCarrinho as $item) {
            // Serviços não têm controle de estoque -> PULAR
            if (isset($item['natureza']) && $item['natureza'] === 'servico') {
                continue;
            }

            // Apenas Produtos
            $produto = Produto::find($item['id']);
            if ($produto) {
                if ($acao == 'decrementar') {
                    $produto->decrement('estoque', $item['quantidade']);
                } else {
                    $produto->increment('estoque', $item['quantidade']);
                }
            }
        }
    }

    private function devolverEstoqueRecibo($recibo)
    {
        foreach ($recibo->items as $item) {
            // Validação Crucial: Só devolve se tiver ID de Produto e NÃO de Serviço
            if ($item->produto_id) {
                $produto = Produto::find($item->produto_id);
                if ($produto) {
                    $produto->increment('estoque', $item->quantidade);
                }
            }
        }
    }

    // =========================================================================
    // UTILITÁRIOS: Carregamento para Retificação (Smart Loader)
    // =========================================================================

    public function carregarDocumentoParaRetificacao($id, $tipo)
    {
        try {
            $doc = null;

            if (in_array($tipo, ['fatura', 'FT', 'FR', 'FP'])) {
                // Carrega relações completas
                $doc = Fatura::with(['cliente', 'items.produto', 'items.servico'])->findOrFail($id);
                $this->tipoDocumento = $doc->tipo_documento;
            } else {
                $doc = Recibo::with(['cliente', 'items.produto', 'items.servico'])->findOrFail($id);
                $this->tipoDocumento = 'RC';
            }

            $this->modoRetificacao = true;
            $this->documentoOriginalId = $doc->id;
            $this->documentoOriginalNumero = $doc->numero;

            if ($doc->cliente_id) {
                $this->selecionarCliente($doc->cliente_id);
            }

            // Recriação do Carrinho Inteligente
            $this->produtosCarrinho = [];

            foreach ($doc->items as $item) {
                // Cenário A: Serviço
                if ($item->servico_id && $item->servico) {
                    $s = $item->servico;
                    $this->produtosCarrinho[] = [
                        'id' => $s->id,
                        'descricao' => $item->descricao,
                        'codigo_barras' => '',

                        'preco_venda' => (float) $item->preco_unitario,
                        'quantidade' => $item->quantidade,
                        'estoque_disponivel' => 999999,
                        'natureza' => 'servico',
                    ];
                }
                // Cenário B: Produto
                elseif ($item->produto_id && $item->produto) {
                    $p = $item->produto;
                    // Stock Virtual = Stock Atual na prateleira + o que o cliente está devolvendo
                    $stockVirtual = $p->estoque + $item->quantidade;

                    $this->produtosCarrinho[] = [
                        'id' => $p->id,
                        'descricao' => $item->descricao,
                        'codigo_barras' => $p->codigo_barras,
                        'preco_venda' => (float) $item->preco_unitario,
                        'quantidade' => $item->quantidade,
                        'estoque_disponivel' => $stockVirtual,
                        'natureza' => 'produto',
                    ];
                }
            }

            $this->calcularTotais();

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao carregar documento: '.$e->getMessage());
        }
    }

    private function resetarFormulario()
    {
        $this->reset([
            'produtosCarrinho', 'clienteSelecionado', 'clienteNome', 'totalRecebido', 'desconto',
            'troco', 'modoRetificacao', 'motivoRetificacao',
        ]);

        $this->clienteNome = 'Nenhum cliente selecionado';
        $this->dataVencimento = ($this->tipoDocumento == 'FR') ? Carbon::now()->format('Y-m-d') : Carbon::now()->addDays(30)->format('Y-m-d');

        $this->calcularTotais();
        $this->carregarProdutos();
        $this->carregarServicos();
    }

    public function exportarDadosFatura()
    {
        $ultima = Fatura::where('user_id', auth()->id())->latest()->first();
        if ($ultima) {
            return redirect()->route('admin.fatura.download', $ultima->id);
        }
    }

    public function carregarClientes()
    {
        $this->clientes = Cliente::when($this->searchClienteTerm, fn ($q) => $q->where('nome', 'like', '%'.$this->searchClienteTerm.'%')
            ->orWhere('nif', 'like', '%'.$this->searchClienteTerm.'%'))
            ->limit(10)->get();
    }

    public function carregarProdutos()
    {
        $this->produtos = Produto::with(['categoria'])->when($this->searchProdutoTerm, fn ($q) => $q->where('descricao', 'like', '%'.$this->searchProdutoTerm.'%')
            ->orWhere('codigo_barras', 'like', '%'.$this->searchProdutoTerm.'%'))
            ->limit(20)->get();

    }

    public function carregarServicos()
    {
        $this->servicos = Servico::when($this->searchProdutoTerm, fn ($q) => $q->where('descricao', 'like', '%'.$this->searchProdutoTerm.'%'))
            ->limit(20)->get();
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

    public function selecionarCliente($id)
    {
        $c = Cliente::find($id);
        if ($c) {
            $this->clienteSelecionado = $c->id;
            $this->clienteNome = $c->nome;
        }
        $this->fecharModal();
    }

    public function alterarNatureza($t)
    {
        $this->natureza = $t;
        // Limpar a busca ao trocar para não confundir
        $this->searchProdutoTerm = '';
        if ($t === 'produto') {
            $this->carregarProdutos();
        } else {
            $this->carregarServicos();
        }
    }

    public function render()
    {
        return view('livewire.pov');
    }
}
