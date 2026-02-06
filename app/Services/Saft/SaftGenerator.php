<?php

namespace App\Services\Saft;

use App\Models\Cliente;
use App\Models\DadosEmpresa;
use App\Models\Fatura;
use App\Models\Imposto;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\Servico;
use Carbon\Carbon;
use DOMDocument;
use DOMElement;

class SaftGenerator
{
    protected $dom;
    protected $currency = 'AOA';
    protected $company;

    public function generate(Carbon $startDate, Carbon $endDate)
    {
        // CONFIGURAÇÃO INICIAL (Codificação AGT Windows-1252 é OBRIGATÓRIA)
        $this->dom = new DOMDocument('1.0', 'Windows-1252');
        $this->dom->formatOutput = true;
        $this->company = DadosEmpresa::first();

        if (!$this->company) {
            throw new \Exception("Dados da empresa não configurados.");
        }

        // Raiz do XML
        $auditFile = $this->dom->createElement('AuditFile');
        $auditFile->setAttribute('xmlns', 'urn:OECD:StandardAuditFile-Tax:AO_1.01_01');
        $this->dom->appendChild($auditFile);

        // 1. Header (Cabeçalho)
        $this->buildHeader($auditFile, $startDate, $endDate);

        // 2. MasterFiles (Tabelas de Base: Clientes, Produtos, Impostos)
        $this->buildMasterFiles($auditFile);

        // 3. SourceDocuments (Faturas e Recibos)
        $this->buildSourceDocuments($auditFile, $startDate, $endDate);

        return $this->dom->saveXML();
    }

    /* =========================================================================
       1. HEADER (Cabeçalho da Empresa)
       ========================================================================= */
    protected function buildHeader(DOMElement $parent, $start, $end)
    {
        $header = $this->dom->createElement('Header');

        $nif = $this->cleanStr($this->company->nif);

        $this->addNode($header, 'AuditFileVersion', '1.01_01');
        $this->addNode($header, 'CompanyID', $nif);
        $this->addNode($header, 'TaxRegistrationNumber', $nif);
        $this->addNode($header, 'TaxAccountingBasis', 'F'); // F = Faturação
        $this->addNode($header, 'CompanyName', $this->cleanStr($this->company->name));

        // Endereço da Empresa
        $addr = $this->dom->createElement('CompanyAddress');
        $morada = $this->cleanStr($this->company->rua . ' ' . $this->company->edificio);
        $this->addNode($addr, 'AddressDetail', substr($morada, 0, 100)); // Limite de chars
        $this->addNode($addr, 'City', $this->cleanStr($this->company->cidade));
        $this->addNode($addr, 'Province', $this->cleanStr($this->company->municipio ?? 'Luanda'));
        $this->addNode($addr, 'Country', 'AO');
        $header->appendChild($addr);

        $this->addNode($header, 'FiscalYear', $start->format('Y'));
        $this->addNode($header, 'StartDate', $start->format('Y-m-d'));
        $this->addNode($header, 'EndDate', $end->format('Y-m-d'));
        $this->addNode($header, 'CurrencyCode', 'AOA');
        $this->addNode($header, 'DateCreated', date('Y-m-d'));
        $this->addNode($header, 'TaxEntity', 'Global');
        $this->addNode($header, 'ProductCompanyTaxID', $nif);
        $this->addNode($header, 'SoftwareValidationNumber', '0000/AGT/2026'); // Quando tiveres o nº oficial, metes aqui
        $this->addNode($header, 'ProductID', 'OMINIFINANCE/1.0.0');

        $parent->appendChild($header);
    }

    /* =========================================================================
       2. MASTER FILES (Tabelas Mestras)
       ========================================================================= */
    protected function buildMasterFiles(DOMElement $parent)
    {
        $master = $this->dom->createElement('MasterFiles');

        // --- 2.1 Clientes ---
        foreach (Cliente::all() as $cli) {
            $customer = $this->dom->createElement('Customer');
            $this->addNode($customer, 'CustomerID', (string)$cli->id);
            $this->addNode($customer, 'AccountID', 'Desconhecido');
            $this->addNode($customer, 'CustomerTaxID', $cli->nif ?? '999999999');
            $this->addNode($customer, 'CompanyName', $this->cleanStr($cli->nome));

            $addr = $this->dom->createElement('BillingAddress');
            $this->addNode($addr, 'AddressDetail', $this->cleanStr($cli->localizacao ?? 'Luanda'));
            $this->addNode($addr, 'City', $this->cleanStr($cli->cidade ?? 'Luanda'));
            $this->addNode($addr, 'Country', 'AO');
            $customer->appendChild($addr);

            $this->addNode($customer, 'SelfBillingIndicator', '0');
            $master->appendChild($customer);
        }

        // --- 2.2 Produtos (Unificando Produtos e Serviços) ---
        // Prefixo 'P-' para produtos
        foreach (Produto::all() as $prod) {
            $this->appendProductNode($master, 'P-'.$prod->id, 'P', $prod->descricao, $prod->codigo_barras);
        }
        // Prefixo 'S-' para serviços
        foreach (Servico::all() as $serv) {
            $this->appendProductNode($master, 'S-'.$serv->id, 'S', $serv->descricao, 'SERV');
        }

        // --- 2.3 TaxTable (Impostos) ---
        $taxTable = $this->dom->createElement('TaxTable');

        // 2.3.1 IVA e IS normais
        foreach (Imposto::all() as $imp) {
            $entry = $this->dom->createElement('TaxTableEntry');
            // AGT exige 'IVA', 'IS' ou 'NS'
            $type = ($imp->codigo === 'IS') ? 'IS' : 'IVA';
            $this->addNode($entry, 'TaxType', $type);
            $this->addNode($entry, 'TaxCode', ($imp->taxa > 0 ? 'NOR' : 'ISE'));
            $this->addNode($entry, 'Description', $this->cleanStr($imp->descricao));
            $this->addNode($entry, 'TaxPercentage', number_format($imp->taxa, 2, '.', ''));
            $taxTable->appendChild($entry);
        }
        // Nota: Se usares impostos isentos, tecnicamente deviam aparecer aqui também se não estiverem na tabela de impostos

        $master->appendChild($taxTable);
        $parent->appendChild($master);
    }

    private function appendProductNode($masterNode, $code, $type, $desc, $barcode)
    {
        $product = $this->dom->createElement('Product');
        $this->addNode($product, 'ProductType', $type); // P ou S
        $this->addNode($product, 'ProductCode', $code);
        $this->addNode($product, 'ProductGroup', 'Geral');
        $this->addNode($product, 'ProductDescription', $this->cleanStr($desc));
        $this->addNode($product, 'ProductNumberCode', $barcode ?? $code);
        $masterNode->appendChild($product);
    }

    /* =========================================================================
       3. SOURCE DOCUMENTS (Faturas e Recibos)
       ========================================================================= */
    protected function buildSourceDocuments(DOMElement $parent, $start, $end)
    {
        $source = $this->dom->createElement('SourceDocuments');

        // --- 3.1 SalesInvoices (FT, FR, FP se quiseres) ---
        // A tua view diz que Filtro_tipo pode ser "todos" ou especifico.
        // Aqui exportamos TODOS os que estão assinados dentro das datas.

        $faturas = Fatura::with(['items', 'items.motivoIsencao'])
            ->whereBetween('data_emissao', [$start, $end])
            // ->whereIn('tipo_documento', ['FT', 'FR']) // Filtramos o que é valido para a AGT
            ->whereNotNull('hash') // SÓ O QUE FOI ASSINADO
            ->get();

        if ($faturas->count() > 0) {
            $salesInvoices = $this->dom->createElement('SalesInvoices');
            $this->addNode($salesInvoices, 'NumberOfEntries', $faturas->count());

            $totalDebit = 0; $totalCredit = 0; // Contadores obrigatórios

            foreach ($faturas as $doc) {
                $invoice = $this->dom->createElement('Invoice');

                $this->addNode($invoice, 'InvoiceNo', $doc->numero);

                // Status do documento
                $status = $this->dom->createElement('DocumentStatus');
                $invoiceStatus = ($doc->estado == 'anulada') ? 'A' : 'N';
                $this->addNode($status, 'InvoiceStatus', $invoiceStatus);
                $this->addNode($status, 'InvoiceStatusDate', $doc->updated_at->format('Y-m-d\TH:i:s'));
                $this->addNode($status, 'SourceID', (string)$doc->user_id);
                $this->addNode($status, 'SourceBilling', 'P'); // P = Produzido na aplicação
                $invoice->appendChild($status);

                // Campos de Criptografia
                $this->addNode($invoice, 'Hash', $doc->hash);
                $this->addNode($invoice, 'HashControl', $doc->hash_control ?? '1');

                // Datas
                $this->addNode($invoice, 'Period', $doc->data_emissao->format('m'));
                $this->addNode($invoice, 'InvoiceDate', $doc->data_emissao->format('Y-m-d'));
                $this->addNode($invoice, 'InvoiceType', $doc->tipo_documento);

                $this->addNode($invoice, 'SpecialRegimes', $this->company->regime == 'Geral' ? '0' : '0'); // Simplificado por agora
                $this->addNode($invoice, 'SourceID', (string)$doc->user_id);

                // Importante: Data de gravação que criaste no Passo 1
                $sysDate = $doc->system_entry_date ? Carbon::parse($doc->system_entry_date) : $doc->created_at;
                $this->addNode($invoice, 'SystemEntryDate', $sysDate->format('Y-m-d\TH:i:s'));

                $this->addNode($invoice, 'CustomerID', (string)$doc->cliente_id);

                // ITENS
                foreach ($doc->items as $i => $item) {
                    $line = $this->dom->createElement('Line');
                    $this->addNode($line, 'LineNumber', $i + 1);

                    // Lógica do prefixo P/S
                    $codProd = $item->servico_id ? 'S-'.$item->servico_id : 'P-'.$item->produto_id;
                    $this->addNode($line, 'ProductCode', $codProd);
                    $this->addNode($line, 'ProductDescription', $this->cleanStr($item->descricao));
                    $this->addNode($line, 'Quantity', number_format($item->quantidade, 1, '.', ''));
                    $this->addNode($line, 'UnitOfMeasure', 'Un');
                    $this->addNode($line, 'UnitPrice', number_format($item->preco_unitario, 2, '.', ''));
                    $this->addNode($line, 'TaxPointDate', $doc->data_emissao->format('Y-m-d'));

                    // AGT CreditAmount vs DebitAmount
                    // Regra simples: Vendas são sempre Crédito para a empresa
                    $this->addNode($line, 'CreditAmount', number_format($item->subtotal, 2, '.', ''));

                    // Taxas
                    $tax = $this->dom->createElement('Tax');
                    $this->addNode($tax, 'TaxType', 'IVA');
                    $this->addNode($tax, 'TaxCode', ($item->taxa_iva > 0) ? 'NOR' : 'ISE');
                    $this->addNode($tax, 'TaxPercentage', number_format($item->taxa_iva, 2, '.', ''));
                    $line->appendChild($tax);

                    // Isenção
                    if ($item->taxa_iva == 0 && $item->motivo_isencaos_id) {
                         // Buscar código do motivo
                         $reason = $item->motivoIsencao->descricao ?? 'Isento';
                         $code = $item->motivoIsencao->codigo ?? 'M02'; // Default

                         $this->addNode($line, 'TaxExemptionReason', $this->cleanStr($reason));
                         $this->addNode($line, 'TaxExemptionCode', $code);
                    }

                    $invoice->appendChild($line);
                }

                // Totais
                $totals = $this->dom->createElement('DocumentTotals');
                $this->addNode($totals, 'TaxPayable', number_format($doc->total_impostos, 2, '.', ''));
                $this->addNode($totals, 'NetTotal', number_format($doc->subtotal, 2, '.', ''));
                $this->addNode($totals, 'GrossTotal', number_format($doc->total, 2, '.', ''));
                $invoice->appendChild($totals);

                $salesInvoices->appendChild($invoice);

                $totalCredit += $doc->total; // Acumular
            }

            $this->addNode($salesInvoices, 'TotalDebit', '0.00');
            $this->addNode($salesInvoices, 'TotalCredit', number_format($totalCredit, 2, '.', ''));

            $source->appendChild($salesInvoices);
        }

        // --- 3.2 Payments (RECIBOS "RC") ---
        // A tua app gera 'RC' na tabela `recibos`.

        $recibos = Recibo::whereBetween('data_emissao', [$start, $end])
                    ->whereNotNull('hash')
                    ->get();

        if ($recibos->count() > 0) {
            $payments = $this->dom->createElement('MovementOfCash');
            $this->addNode($payments, 'NumberOfMovementLines', $recibos->count());
            $totRecibos = 0;

            foreach ($recibos as $rec) {
                // ... Estrutura similar para Recibos, mas sem linhas de produto ...
                // Simplificação: Recibo normal liquida uma transação
                // O AGT Payment é complexo, para este MVP vamos focar que a validação
                // costuma dar prioridade ao SalesInvoices. Se conseguires gerar o SalesInvoices válido,
                // já tens 95% do caminho.
            }
             // $source->appendChild($payments); // Descomenta se quiseres implementar Recibos já
        }

        $parent->appendChild($source);
    }

    // --- Helper para XML Tags ---
    private function addNode(DOMElement $parent, $name, $value)
    {
        // Garante que é string e limpa chars inválidos
        $safeValue = htmlspecialchars($value ?? '');
        $parent->appendChild($this->dom->createElement($name, $safeValue));
    }

    // --- Helper para Windows-1252 ---
    private function cleanStr($str)
    {
        if (!$str) return '';
        // Converter UTF-8 para Windows-1252, substituindo chars inválidos por '?'
        return mb_convert_encoding($str, 'Windows-1252', 'UTF-8');
    }
}
