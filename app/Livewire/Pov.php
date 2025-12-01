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

    public $natureza = 'produto'; // produto ou servico

    // --- Dados de Pagamento e Datas ---
    public $metodoPagamento = 'dinheiro'; // Obrigatório para FR e RC

    public $dataVencimento;               // Obrigatório para FT e FP

    public $totalRecebido = 0;            // Valor entregue pelo cliente

    public $troco = 0;                    // Troco calculado

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

    // --- Produtos e Carrinho ---
    public $produtos = [];

    public $servicos = [];

    public $produtosCarrinho = [];

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
        $this->dataVencimento = Carbon::now()->addDays(30)->format('Y-m-d');
        $this->carregarClientes();
        $this->carregarProdutos();
        $this->carregarServicos();

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

    // Ao mudar o tipo de documento, ajusta vencimentos
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

    public function updatedNatureza()
    {
        // Recarrega a lista ao trocar de natureza
        if ($this->natureza === 'produto') {
            $this->carregarProdutos();
        } else {
            $this->carregarServicos();
        }
    }

    // =========================================================================
    // LÓGICA DO CARRINHO
    // =========================================================================

    public function calcularTotais()
    {
        $this->subtotal = 0;
        $this->totalImpostos = 0;
        $this->resumoImpostos = [];

        foreach ($this->produtosCarrinho as $item) {
            // Busca o produto ou serviço baseado na natureza
            if ($item['natureza'] === 'servico') {
                $entidade = Servico::with(['imposto', 'motivoIsencao'])->find($item['id']);
            } else {
                $entidade = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
            }

            if (! $entidade) {
                continue;
            }

            $subtotalItem = $item['preco_venda'] * $item['quantidade'];
            $this->subtotal += $subtotalItem;

            // Determinar taxa e isenção
            if ($entidade->motivoIsencao) {
                $taxa = 0;
                $descricaoImposto = 'Isento';
                $motivoIsencao = $entidade->motivoIsencao->descricao;
                $codigoMotivo = $entidade->motivoIsencao->codigo;
            } elseif ($entidade->imposto) {
                $taxa = (float) $entidade->imposto->taxa;
                $descricaoImposto = $entidade->imposto->descricao;
                $motivoIsencao = null;
                $codigoMotivo = null;
            } else {
                $taxa = 14;
                $descricaoImposto = 'IVA';
                $motivoIsencao = null;
                $codigoMotivo = null;
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

        // Troco só se aplica a Fatura-Recibo ou Recibo
        if (in_array($this->tipoDocumento, ['FR', 'RC']) && $recebido > 0) {
            $this->troco = max(0, $recebido - $this->total);
        } else {
            $this->troco = 0;
        }
    }

    public function adicionarProduto($produtoId)
    {
        $produto = Produto::find($produtoId);
        if (! $produto) {
            return;
        }

        // Se for PROFORMA, pode adicionar sem estoque. Se não, valida.
        $permiteSemEstoque = ($this->tipoDocumento === 'FP' || $this->natureza === 'servico');

        $index = collect($this->produtosCarrinho)->search(fn ($item) => $item['id'] == $produtoId);

        if ($index !== false) {
            $qtdAtual = $this->produtosCarrinho[$index]['quantidade'];
            $estoqueDisponivel = $this->produtosCarrinho[$index]['estoque_disponivel'];

            if ($permiteSemEstoque || $qtdAtual < $estoqueDisponivel) {
                $this->produtosCarrinho[$index]['quantidade']++;
            } else {
                session()->flash('error', 'Estoque insuficiente.');

                return;
            }
        } else {
            // Se for venda (FT/FR) e estoque zerado, bloqueia (exceto serviços)
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
                'natureza' => $this->natureza,
            ];
        }

        $this->calcularTotais();
    }

    // adicionar servico
    public function adicionarServico($servicoId)
    {
        $servico = Servico::find($servicoId);
        if (! $servico) {
            return;
        }

        $index = collect($this->produtosCarrinho)->search(fn ($item) => $item['id'] == $servicoId && $item['natureza'] === 'servico'
        );

        if ($index !== false) {
            $this->produtosCarrinho[$index]['quantidade']++;
        } else {
            $this->produtosCarrinho[] = [
                'id' => $servico->id,
                'descricao' => $servico->descricao,
                'codigo_barras' => '', // Serviços não têm código de barras
                'preco_venda' => (float) $servico->preco_venda,
                'quantidade' => 1,
                'estoque_disponivel' => $servico->estoque ?? 999999,
                'natureza' => 'servico',
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

        // Serviços sempre permitem
        $permiteSemEstoque = ($this->tipoDocumento === 'FP' || $item['natureza'] === 'servico');

        if ($nova > 0) {
            if ($permiteSemEstoque || $nova <= $est) {
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
    // FINALIZAÇÃO DE VENDA (FT, FR, FP, RC)
    // =========================================================================

    public function finalizarVenda()
    {
        // 1. Validações Básicas
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

        // 2. Validação de Pagamento para Fatura-Recibo
        if ($this->tipoDocumento === 'FR') {
            $recebido = floatval(str_replace(',', '.', str_replace(' ', '', $this->totalRecebido)));
            // Opcional: permitir fechar sem valor exato se for TPA, mas para Dinheiro geralmente valida
            if ($this->metodoPagamento === 'dinheiro' && $recebido < $this->total) {
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
        // Caso Especial: Recibo de Liquidação (RC) isolado
        if ($this->tipoDocumento === 'RC') {
            $this->gerarReciboIsolado();

            return;
        }

        // Fatura (FT), Fatura-Recibo (FR) ou Proforma (FP)
        $numeroGerado = Fatura::gerarProximoNumero($this->tipoDocumento);

        // Define estado inicial
        $estado = 'emitida';
        if ($this->tipoDocumento === 'FR') {
            $estado = 'paga';
        } // Nasce paga

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

        // Se NÃO for Proforma, movimenta estoque
        if ($this->tipoDocumento !== 'FP') {
            $this->atualizarEstoque('decrementar');
        }

        $nomeTipo = match ($this->tipoDocumento) {
            'FT' => 'Fatura',
            'FR' => 'Fatura-Recibo',
            'FP' => 'Proforma',
            default => 'Documento'
        };

        session()->flash('success', "$nomeTipo $numeroGerado emitido(a) com sucesso!");
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

        session()->flash('success', "Recibo $numero gerado!");
        $this->resetarFormulario();
    }

    // =========================================================================
    // RETIFICAÇÃO
    // =========================================================================

    private function processarRetificacao()
    {
        // Se for Recibo Legado
        if ($this->tipoDocumento === 'RC' || $this->tipoDocumento === 'recibo') {
            $original = Recibo::with('items')->findOrFail($this->documentoOriginalId);
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
            ]);
            $this->salvarItensRecibo($novo);
            $original->marcarComoRetificado($novo->id, $this->motivoRetificacao);

        } else {
            // Fatura, Fatura-Recibo ou Proforma
            $original = Fatura::with('items')->findOrFail($this->documentoOriginalId);

            // Devolve estoque antigo (se não for proforma)
            if ($original->tipo_documento !== 'FP') {
                $original->devolverEstoque();
            }

            // Gera novo número mantendo o tipo original
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
                'fatura_original_id' => $this->documentoOriginalId, // Link para original
                'observacoes' => 'Retificação: '.$this->motivoRetificacao,
            ]);

            $this->salvarItensFatura($novoDoc);

            // Baixa novo estoque (se não for proforma)
            if ($tipo !== 'FP') {
                $this->atualizarEstoque('decrementar');
            }

            // Marca a antiga
            $original->marcarComoRetificada($novoDoc->id, $this->motivoRetificacao);
        }

        session()->flash('success', 'Documento retificado com sucesso.');
        $this->resetarFormulario();
    }

    // =========================================================================
    // HELPERS DE BANCO DE DADOS
    // =========================================================================

    private function salvarItensFatura(Fatura $documento)
    {
        foreach ($this->produtosCarrinho as $item) {
            if ($item['natureza'] === 'servico') {
                $entidade = Servico::with(['imposto', 'motivoIsencao'])->find($item['id']);
            } else {
                $entidade = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
            }

            if ($entidade) {
                $this->criarItem($documento->id, $entidade, $item, FaturaItem::class, 'fatura_id');
            }
        }
    }

    private function salvarItensRecibo(Recibo $recibo)
    {
        foreach ($this->produtosCarrinho as $item) {
            if ($item['natureza'] === 'servico') {
                $entidade = Servico::with(['imposto', 'motivoIsencao'])->find($item['id']);
            } else {
                $entidade = Produto::with(['imposto', 'motivoIsencao'])->find($item['id']);
            }

            if ($entidade) {
                $this->criarItem($recibo->id, $entidade, $item, ReciboItem::class, 'recibo_id');
            }
        }
    }

    private function criarItem($docId, $produto, $itemCarrinho, $modelClass, $fk)
    {
        $taxa = 14;
        $impostoId = null;
        $motivoId = null;

        if ($produto->motivoIsencao) {
            $taxa = 0;
            $motivoId = $produto->motivo_isencaos_id;
        } elseif ($produto->imposto) {
            $taxa = (float) $produto->imposto->taxa;
            $impostoId = $produto->imposto_id;
        }

        $subtotal = $itemCarrinho['preco_venda'] * $itemCarrinho['quantidade'];
        $valorIva = $taxa > 0 ? ($subtotal * ($taxa / 100)) : 0;

        $modelClass::create([
            $fk => $docId,
            'produto_id' => $produto->id,
            'descricao' => $produto->descricao,
            'codigo_barras' => $produto->codigo_barras,
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

    private function atualizarEstoque($acao)
    {
        foreach ($this->produtosCarrinho as $item) {
            // Serviços não afetam estoque
            if ($item['natureza'] === 'servico') {
                continue;
            }

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
            $produto = Produto::find($item->produto_id);
            if ($produto) {
                $produto->increment('estoque', $item->quantidade);
            }
        }
    }

    // =========================================================================
    // UTILITÁRIOS (MODAIS, CARREGAMENTOS, RESET)
    // =========================================================================

    public function carregarDocumentoParaRetificacao($id, $tipo)
    {
        try {
            // Tenta carregar Fatura (FT, FR, FP)
            if (in_array($tipo, ['fatura', 'FT', 'FR', 'FP'])) {
                $doc = Fatura::with(['cliente', 'items.produto'])->findOrFail($id);
                if (! $doc->pode_ser_retificada) {
                    session()->flash('error', 'Documento não pode ser retificado.');

                    return redirect()->back();
                }
                $this->tipoDocumento = $doc->tipo_documento;
            } else {
                // Legado Recibo
                $doc = Recibo::with(['cliente', 'items.produto'])->findOrFail($id);
                $this->tipoDocumento = 'RC';
            }

            $this->modoRetificacao = true;
            $this->documentoOriginalId = $doc->id;
            $this->documentoOriginalNumero = $doc->numero;
            $this->selecionarCliente($doc->cliente_id);

            // Popula carrinho
            foreach ($doc->items as $item) {
                $p = $item->produto;
                $this->produtosCarrinho[] = [
                    'id' => $p->id,
                    'descricao' => $p->descricao,
                    'codigo_barras' => $p->codigo_barras,
                    'preco_venda' => (float) $item->preco_unitario,
                    'quantidade' => $item->quantidade,
                    'estoque_disponivel' => $p->estoque + $item->quantidade,
                    'natureza' => 'produto',
                ];
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
        // Redireciona para download da última fatura do usuário
        $ultima = Fatura::where('user_id', auth()->id())->latest()->first();
        if ($ultima) {
            return redirect()->route('admin.fatura.download', $ultima->id);
        }
    }

    public function carregarClientes()
    {
        $this->clientes = Cliente::when($this->searchClienteTerm, fn ($q) => $q->where('nome', 'like', '%'.$this->searchClienteTerm.'%')->orWhere('nif', 'like', '%'.$this->searchClienteTerm.'%'))->limit(10)->get();
    }

    public function carregarProdutos()
    {
        $this->produtos = Produto::with(['categoria'])->when($this->searchProdutoTerm, fn ($q) => $q->where('descricao', 'like', '%'.$this->searchProdutoTerm.'%')->orWhere('codigo_barras', 'like', '%'.$this->searchProdutoTerm.'%'))->limit(20)->get();
    }

    public function carregarServicos()
    {
        $this->servicos = Servico::when($this->searchProdutoTerm, fn ($q) => $q->where('descricao', 'like', '%'.$this->searchProdutoTerm.'%')
        )->limit(20)->get();
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
    }

    public function render()
    {
        return view('livewire.pov');
    }
}
