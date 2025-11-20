<?php

namespace App\Http\Controllers;

use App\Models\DadosEmpresa;
use App\Models\Fatura;
use App\Models\Recibo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnulacaoController extends Controller
{
    /**
     * ✅ ANULAR FATURA
     */
    public function anularFatura(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:10|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Buscar fatura com items
            $fatura = Fatura::with(['items.produto', 'cliente'])->findOrFail($id);

            // Validar se pode ser anulada
            if (! $fatura->pode_ser_anulada) {
                return redirect()->back()->with('error', 'Esta fatura não pode ser anulada.');
            }

            // Devolver estoque
            $fatura->devolverEstoque();

            // Marcar como anulada
            $fatura->marcarComoAnulada($request->motivo);

            DB::commit();

            return redirect()->route('admin.faturas')
                ->with('success', "Fatura {$fatura->numero} anulada com sucesso. Estoque devolvido e Nota de Crédito gerada.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Erro ao anular fatura: '.$e->getMessage());
        }
    }

    /**
     * ✅ ANULAR RECIBO
     */
    public function anularRecibo(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|min:10|max:500',
        ]);

        try {
            DB::beginTransaction();

            $recibo = Recibo::with(['items.produto', 'cliente'])->findOrFail($id);

            if (! $recibo->pode_ser_anulado) {
                return redirect()->back()->with('error', 'Este recibo não pode ser anulado.');
            }

            // Devolver estoque
            $recibo->devolverEstoque();

            // Marcar como anulado
            $recibo->marcarComoAnulado($request->motivo);

            DB::commit();

            return redirect()->route('admin.recibos')
                ->with('success', "Recibo {$recibo->numero} anulado com sucesso. Estoque devolvido e Nota de Crédito gerada.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Erro ao anular recibo: '.$e->getMessage());
        }
    }

    /**
     * ✅ VISUALIZAR NOTA DE CRÉDITO DE ANULAÇÃO
     */
    public function visualizarNotaCreditoAnulacao($tipo, $id)
    {
        if ($tipo === 'fatura') {
            $documento = Fatura::with([
                'cliente',
                'user',
                'anuladaPor',
                'items.produto.categoria',
                'items.imposto',
                'items.motivoIsencao',
            ])->findOrFail($id);

            if (! $documento->anulada) {
                return redirect()->back()->with('error', 'Esta fatura não está anulada.');
            }

        } elseif ($tipo === 'recibo') {
            $documento = Recibo::with([
                'cliente',
                'user',
                'anuladoPor',
                'items.produto.categoria',
                'items.imposto',
                'items.motivoIsencao',
            ])->findOrFail($id);

            if (! $documento->anulado) {
                return redirect()->back()->with('error', 'Este recibo não está anulado.');
            }
        } else {
            return redirect()->back()->with('error', 'Tipo de documento inválido.');
        }

        $empresa = DadosEmpresa::first();
        $dadosNotaCredito = $this->montarDadosNotaCreditoAnulacao($documento, $tipo, $empresa);

        return view('pdf.nota-credito-anulacao', [
            'dados' => $dadosNotaCredito,
        ]);
    }

    /**
     * ✅ MONTAR DADOS DA NOTA DE CRÉDITO DE ANULAÇÃO
     */
    private function montarDadosNotaCreditoAnulacao($documento, $tipo, $empresa)
    {
        $isRecibo = $tipo === 'recibo';

        return [
            // ========== TIPO DE NOTA ==========
            'tipo_nota' => 'ANULACAO',
            'tipo_documento' => $tipo,
            'numero_nota_credito' => 'NC-ANULACAO-'.$documento->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            // ========== EMPRESA ==========
            'empresa' => [
                'nome' => $empresa->name ?? '',
                'nif' => $empresa->nif ?? '',
                'telefone' => $empresa->telefone ?? '',
                'email' => $empresa->email ?? '',
                'endereco' => $empresa->rua ?? '',
                'cidade' => $empresa->cidade ?? '',
                'logo' => $empresa->logo ?? null,
            ],

            // ========== CLIENTE ==========
            'cliente' => [
                'nome' => $documento->cliente->nome,
                'nif' => $documento->cliente->nif,
                'telefone' => $documento->cliente->telefone ?? '',
                'endereco' => $documento->cliente->localizacao ?? '',
                'cidade' => $documento->cliente->cidade ?? '',
            ],

            // ========== DADOS DA ANULAÇÃO ==========
            'anulacao' => [
                'data' => ($isRecibo ? $documento->data_anulacao : $documento->data_anulacao)->format('d/m/Y H:i'),
                'motivo' => $isRecibo ? $documento->motivo_anulacao : $documento->motivo_anulacao,
                'usuario' => $isRecibo
                    ? ($documento->anuladoPor->name ?? 'Sistema')
                    : ($documento->anuladaPor->name ?? 'Sistema'),
            ],

            // ========== DOCUMENTO ANULADO ==========
            'documento_anulado' => [
                'numero' => $documento->numero,
                'data_emissao' => $documento->data_emissao->format('d/m/Y'),
                'subtotal' => $isRecibo ? (float) $documento->valor : (float) $documento->subtotal,
                'total_impostos' => $isRecibo ? 0 : (float) $documento->total_impostos,
                'total' => $isRecibo ? (float) $documento->valor : (float) $documento->total,
                'produtos' => $this->formatarProdutosAnulacao($documento->items),
                'resumo_impostos' => $isRecibo ? [] : $this->calcularResumoImpostos($documento->items),
            ],

            // ========== ESTOQUE DEVOLVIDO ==========
            'estoque_devolvido' => $this->calcularEstoqueDevolvido($documento->items),

            // ========== VALORES DEVOLVIDOS À EMPRESA ==========
            'valores_devolvidos' => [
                'subtotal' => $isRecibo ? (float) $documento->valor : (float) $documento->subtotal,
                'impostos' => $isRecibo ? 0 : (float) $documento->total_impostos,
                'total' => $isRecibo ? (float) $documento->valor : (float) $documento->total,
            ],
        ];
    }

    /**
     * ✅ FORMATAR PRODUTOS PARA NOTA DE ANULAÇÃO
     */
    private function formatarProdutosAnulacao($items)
    {
        $produtos = [];

        foreach ($items as $item) {
            $produto = $item->produto;

            $produtos[] = [
                'codigo' => $item->codigo_barras,
                'descricao' => $item->descricao,
                'categoria' => $produto->categoria->nome ?? 'Sem categoria',
                'quantidade' => $item->quantidade,
                'quantidade_devolvida' => $item->quantidade,
                'preco_unitario' => (float) $item->preco_unitario,
                'subtotal' => (float) $item->subtotal,
                'taxa_iva' => (float) $item->taxa_iva,
                'valor_iva' => (float) $item->valor_iva,
                'total' => (float) $item->total,
                'valor_devolvido' => (float) $item->total,
            ];
        }

        return $produtos;
    }

    /**
     * ✅ CALCULAR RESUMO DE IMPOSTOS
     */
    private function calcularResumoImpostos($items)
    {
        $resumo = [];

        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;

            if ($item->motivo_isencaos_id) {
                $descricao = 'Isento';
                $motivoIsencao = $item->motivoIsencao->descricao ?? 'N/A';
            } elseif ($item->imposto_id) {
                $descricao = $item->imposto->descricao ?? 'IVA';
                $motivoIsencao = null;
            } else {
                $descricao = 'IVA';
                $motivoIsencao = null;
            }

            $chave = $taxa.'|'.$descricao;

            if (! isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'taxa' => $taxa,
                    'descricao' => $descricao,
                    'motivo_isencao' => $motivoIsencao,
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }

            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        return array_values($resumo);
    }

    /**
     * ✅ CALCULAR ESTOQUE DEVOLVIDO
     */
    private function calcularEstoqueDevolvido($items)
    {
        $estoque = [];
        $totalQuantidade = 0;
        $totalValor = 0;

        foreach ($items as $item) {
            $estoque[] = [
                'produto' => $item->descricao,
                'quantidade' => $item->quantidade,
                'valor_unitario' => (float) $item->preco_unitario,
                'valor_total' => (float) $item->subtotal,
            ];

            $totalQuantidade += $item->quantidade;
            $totalValor += (float) $item->subtotal;
        }

        return [
            'items' => $estoque,
            'total_quantidade' => $totalQuantidade,
            'total_valor' => $totalValor,
        ];
    }

    /**
     * ✅ GERAR PDF DA NOTA DE CRÉDITO DE ANULAÇÃO
     */
    public function gerarPDFAnulacao($tipo, $id)
    {
        if ($tipo === 'fatura') {
            $documento = Fatura::with(['cliente', 'items.produto.categoria', 'anuladaPor'])->findOrFail($id);

            if (! $documento->anulada) {
                return redirect()->back()->with('error', 'Esta fatura não está anulada.');
            }
        } else {
            $documento = Recibo::with(['cliente', 'items.produto.categoria', 'anuladoPor'])->findOrFail($id);

            if (! $documento->anulado) {
                return redirect()->back()->with('error', 'Este recibo não está anulado.');
            }
        }

        $empresa = DadosEmpresa::first();
        $dados = $this->montarDadosNotaCreditoAnulacao($documento, $tipo, $empresa);

        $pdf = PDF::loadView('pdf.nota-credito-anulacao', ['dados' => $dados])
            ->setPaper('a4', 'portrait');

        return $pdf->download('nota-credito-anulacao-'.$documento->numero.'.pdf');
    }
}
