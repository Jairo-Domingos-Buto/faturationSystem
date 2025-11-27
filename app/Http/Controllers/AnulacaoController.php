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
     * ✅ ANULAR FATURA (Lógica de Negócio)
     */
    public function anularFatura(Request $request, $id)
    {
        $request->validate(['motivo' => 'required|string|min:10|max:500']);

        try {
            DB::beginTransaction();
            $fatura = Fatura::with(['items.produto'])->findOrFail($id);

            if (! $fatura->pode_ser_anulada) {
                return redirect()->back()->with('error', 'Esta fatura não pode ser anulada.');
            }

            $fatura->devolverEstoque();
            $fatura->marcarComoAnulada($request->motivo);

            DB::commit();

            return redirect()->route('admin.faturas')->with('success', "Fatura {$fatura->numero} anulada. Nota de Crédito gerada.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Erro: '.$e->getMessage());
        }
    }

    /**
     * ✅ ANULAR RECIBO (Lógica de Negócio)
     */
    public function anularRecibo(Request $request, $id)
    {
        $request->validate(['motivo' => 'required|string|min:10|max:500']);

        try {
            DB::beginTransaction();
            $recibo = Recibo::with(['items.produto'])->findOrFail($id);

            if (! $recibo->pode_ser_anulado) {
                return redirect()->back()->with('error', 'Este recibo não pode ser anulado.');
            }

            $recibo->devolverEstoque();
            $recibo->marcarComoAnulado($request->motivo);

            DB::commit();

            return redirect()->route('admin.recibos')->with('success', "Recibo {$recibo->numero} anulado. Nota de Crédito gerada.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Erro: '.$e->getMessage());
        }
    }

    // =========================================================================
    // VISUALIZAÇÃO E DOWNLOAD (PADRONIZADOS COM A VIEW PDF)
    // =========================================================================

    /**
     * ✅ VISUALIZAR (Stream no Navegador)
     */
    public function visualizarNotaCreditoAnulacao($tipo, $id)
    {
        try {
            $dados = $this->prepararDadosParaPdf($tipo, $id);

            // Reutiliza a MESMA view padronizada
            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->stream('NC-Anulacao-'.$dados['numero_nota_credito'].'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF: '.$e->getMessage());
        }
    }

    /**
     * ✅ DOWNLOAD (Baixar Arquivo)
     */
    public function downloadNotaCreditoAnulacao($tipo, $id)
    {
        try {
            $dados = $this->prepararDadosParaPdf($tipo, $id);

            $pdf = Pdf::loadView('pdf.notaCredito', ['dados' => $dados])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->download('NC-Anulacao-'.$dados['numero_nota_credito'].'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao baixar PDF: '.$e->getMessage());
        }
    }

    // =========================================================================
    // MÉTODOS DE MONTAGEM DE DADOS (PADRONIZADO)
    // =========================================================================

    private function prepararDadosParaPdf($tipo, $id)
    {
        $empresa = DadosEmpresa::first();

        if ($tipo === 'fatura') {
            $documento = Fatura::with([
                'cliente', 'user', 'anuladaPor',
                'items.produto.categoria', 'items.imposto', 'items.motivoIsencao',
            ])->findOrFail($id);

            if (! $documento->anulada) {
                throw new \Exception('Fatura não está anulada.');
            }

            return $this->montarDadosAnulacaoFatura($documento, $empresa);

        } elseif ($tipo === 'recibo') {
            $documento = Recibo::with([
                'cliente', 'user', 'anuladoPor',
                'items.produto.categoria', 'items.imposto', 'items.motivoIsencao',
            ])->findOrFail($id);

            if (! $documento->anulado) {
                throw new \Exception('Recibo não está anulado.');
            }

            return $this->montarDadosAnulacaoRecibo($documento, $empresa);
        } else {
            throw new \Exception('Tipo inválido.');
        }
    }

    private function montarDadosAnulacaoFatura($fatura, $empresa)
    {
        $produtos = $this->formatarProdutos($fatura->items);
        $resumoImpostos = $this->calcularResumoImpostos($fatura->items);

        return [
            // Controle da View
            'tipo_label' => 'NOTA DE CRÉDITO - ANULAÇÃO FATURA',
            'numero_nota_credito' => 'NC-'.$fatura->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            // Entidades Padronizadas
            'empresa' => $this->formatarEmpresa($empresa),
            'cliente' => $this->formatarCliente($fatura->cliente),

            // Onde a View busca o motivo (usamos 'retificacao' como chave genérica de evento)
            'retificacao' => [
                'motivo' => $fatura->motivo_anulacao ?? 'Anulação Total do Documento',
                'usuario' => $fatura->anuladaPor->name ?? 'Sistema',
                'data' => $fatura->data_anulacao ? $fatura->data_anulacao->format('d/m/Y H:i') : now()->format('d/m/Y'),
            ],

            // O Documento Original (Dados financeiros)
            'documento_anulado' => [
                'tipo' => 'Fatura Original',
                'numero' => $fatura->numero,
                'data_emissao' => $fatura->data_emissao->format('d/m/Y'),
                'subtotal' => (float) $fatura->subtotal,
                'total_impostos' => (float) $fatura->total_impostos,
                'total' => (float) $fatura->total,
                'produtos' => $produtos,
                'resumo_impostos' => $resumoImpostos,
            ],

            // Como é anulação total, não existe documento novo
            'documento_retificacao' => null,
        ];
    }

    private function montarDadosAnulacaoRecibo($recibo, $empresa)
    {
        $produtos = $this->formatarProdutos($recibo->items);
        $resumoImpostos = $this->calcularResumoImpostos($recibo->items);

        return [
            // Controle da View
            'tipo_label' => 'NOTA DE CRÉDITO - ANULAÇÃO RECIBO',
            'numero_nota_credito' => 'NC-'.$recibo->numero,
            'data_emissao_nota' => now()->format('Y-m-d'),

            // Entidades
            'empresa' => $this->formatarEmpresa($empresa),
            'cliente' => $this->formatarCliente($recibo->cliente),

            // Motivo
            'retificacao' => [
                'motivo' => $recibo->motivo_anulacao ?? 'Anulação Total do Documento',
                'usuario' => $recibo->anuladoPor->name ?? 'Sistema',
                'data' => $recibo->data_anulacao ? $recibo->data_anulacao->format('d/m/Y H:i') : now()->format('d/m/Y'),
            ],

            // Documento Original
            'documento_anulado' => [
                'tipo' => 'Recibo Original',
                'numero' => $recibo->numero,
                'data_emissao' => $recibo->data_emissao ? $recibo->data_emissao->format('d/m/Y') : null,
                'subtotal' => (float) ($recibo->subtotal ?? $recibo->valor), // Fallback se subtotal nulo
                'total_impostos' => (float) ($recibo->total_impostos ?? 0),
                'total' => (float) $recibo->valor,
                'produtos' => $produtos,
                'resumo_impostos' => $resumoImpostos,
            ],

            'documento_retificacao' => null,
        ];
    }

    // =========================================================================
    // HELPERS (IDÊNTICOS AO NOTACREDITOCONTROLLER)
    // =========================================================================

    private function formatarEmpresa($empresa)
    {
        return [
            'nome' => $empresa->name ?? '',
            'nif' => $empresa->nif ?? '',
            'telefone' => $empresa->telefone ?? '',
            'email' => $empresa->email ?? '',
            'endereco' => $empresa->rua ?? '',
            'edificio' => $empresa->edificio ?? '',
            'cidade' => $empresa->cidade ?? '',
            'banco' => $empresa->nomeDoBanco ?? $empresa->banco ?? '',
            'iban' => $empresa->iban ?? '',
        ];
    }

    private function formatarCliente($cliente)
    {
        if (! $cliente) {
            return [];
        }

        return [
            'nome' => $cliente->nome,
            'nif' => $cliente->nif,
            'telefone' => $cliente->telefone ?? '',
            'localizacao' => $cliente->localizacao ?? $cliente->endereco ?? '',
            'cidade' => $cliente->cidade ?? '',
            'provincia' => $cliente->provincia ?? '',
        ];
    }

    private function formatarProdutos($items)
    {
        $produtos = [];
        foreach ($items as $item) {
            $produto = $item->produto;

            if ($item->motivo_isencaos_id) {
                $taxa = 0;
            } elseif ($item->imposto_id) {
                $taxa = (float) $item->taxa_iva;
            } else {
                $taxa = 14; // Fallback
            }

            $produtos[] = [
                'codigo' => $item->codigo_barras ?? $produto->codigo_barras,
                'descricao' => $item->descricao ?? $produto->descricao,
                'quantidade' => $item->quantidade,
                'preco_unitario' => (float) $item->preco_unitario,
                'taxa_iva' => $taxa,
                'total' => (float) $item->total ?? ($item->subtotal + $item->valor_iva),
            ];
        }

        return $produtos;
    }

    private function calcularResumoImpostos($items)
    {
        $resumo = [];
        foreach ($items as $item) {
            $taxa = (float) $item->taxa_iva;
            $chave = (string) $taxa;

            if (! isset($resumo[$chave])) {
                $resumo[$chave] = [
                    'descricao' => $taxa == 0 ? 'Isento' : 'IVA '.number_format($taxa, 0).'%',
                    'incidencia' => 0,
                    'valor_imposto' => 0,
                ];
            }
            $resumo[$chave]['incidencia'] += (float) $item->subtotal;
            $resumo[$chave]['valor_imposto'] += (float) $item->valor_iva;
        }

        return array_values($resumo);
    }
}
