<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $dados['tipo_label'] ?? 'Fatura' }} {{ $dados['numero'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            background: #fff;
            color: #111;
            line-height: 1.4;
        }

        .invoice {
            width: 90%;
            margin: 0 auto;
            padding: 12mm 10mm;
            background: #fff;
            position: relative;
        }

        .page-number {
            position: absolute;
            top: 10mm;
            right: 10mm;
            font-size: 8pt;
            color: #666;
        }

        /* Header usando float em vez de flex */
        .header {
            width: 100%;
            margin-bottom: 10mm;
            padding-bottom: 5mm;
            overflow: hidden;
        }

        .header-left {
            float: left;
            width: 55%;
        }

        .header-right {
            float: right;
            width: 40%;
        }

        .logo-text {
            font-size: 24pt;
            font-weight: 300;
            color: #00a8cc;
            margin-bottom: 3mm;
        }

        .company-info {
            font-size: 8pt;
            line-height: 1.5;
        }

        .company-name {
            font-weight: 700;
            margin-bottom: 2px;
            color: #000;
        }

        .client-section {
            text-align: right;
            font-size: 8pt;
            line-height: 1.5;
        }

        .client-label {
            font-weight: 600;
            margin-bottom: 2px;
            color: #333;
        }

        .client-name {
            font-size: 9pt;
            font-weight: 700;
            color: #000;
        }

        /* Invoice title */
        .invoice-title {
            text-align: right;
            margin: 3mm 0;
            clear: both;
        }

        .invoice-title h1 {
            font-size: 11pt;
            font-weight: 700;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .invoice-title .original {
            font-size: 8pt;
            color: #555;
        }

        /* Meta info usando table */
        .invoice-meta-grid {
            width: 100%;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            padding: 6mm 0;
            margin-bottom: 5mm;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-left {
            width: 45%;
            padding-right: 10px;
            margin-right: 10px;
        }

        .meta-right {
            width: 45%;
            padding-left: 10px;
        }

        .meta-item {
            margin-bottom: 3px;
            overflow: hidden;
        }

        .meta-label {
            color: #555;
            float: left;
            width: 60%;
        }

        .meta-value {
            font-weight: 600;
            float: right;
            text-align: right;
            width: 40%;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5mm;
            font-size: 8pt;
        }

        .items-table thead {
            background: #f3f4f6;
            border-top: 2px solid #333;
            border-bottom: 1px solid #333;
        }

        .items-table th {
            text-transform: uppercase;
            font-size: 7pt;
            font-weight: 700;
            text-align: left;
            padding: 6px;
            color: #333;
        }

        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #eaeaea;
            vertical-align: top;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #333;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .item-code {
            color: #777;
            font-size: 7pt;
            display: block;
            margin-top: 2px;
        }

        /* Summary usando table */
        .summary-section {
            margin-top: 10mm;
            border-top: 1px solid #e0e0e0;
            padding-top: 5mm;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            vertical-align: top;
            padding: 0;
        }

        .summary-left {
            width: 55%;
            padding-right: 10px;
        }

        .summary-right {
            width: 45%;
            padding-left: 10px;
        }

        .tax-summary h3 {
            font-size: 9pt;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .tax-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        .tax-table th,
        .tax-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }

        .tax-table th {
            background: #f3f4f6;
            font-weight: 600;
        }

        .totals-box {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            padding: 12px;
        }

        .total-line {
            width: 100%;
            overflow: hidden;
            padding: 4px 0;
            font-size: 9pt;
        }

        .total-line .label {
            float: left;
            width: 60%;
        }

        .total-line .value {
            float: right;
            text-align: right;
            width: 40%;
        }

        .total-line.grand-total {
            font-weight: 700;
            font-size: 11pt;
            border-top: 2px solid #333;
            margin-top: 6px;
            padding-top: 6px;
        }

        /* Bank info */
        .bank-info {
            margin-top: 10mm;
            background: #f8f9fa;
            border-left: 3px solid #00a8cc;
            padding: 10px 12px;
            font-size: 8.5pt;
            line-height: 1.6;
        }

        .bank-info strong {
            font-weight: 700;
            color: #000;
        }

        .page-break {
            page-break-after: always;
        }

        .items-table thead {
            display: table-header-group;
        }

        .items-table tfoot {
            display: table-footer-group;
        }

        @page {
            margin: 10mm;
        }



        /* Footer */
        .footer {
            margin-top: 6mm;
            padding-top: 3mm;
            border-top: 1px solid #e0e0e0;
            font-size: 6.5pt;
            color: #777;
            text-align: center;
        }

        /* Clear floats */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="invoice">

        <!-- HEADER -->
        <div class="header clearfix">
            <div class="header-left">
                <div class="logo-text">OmiFinance</div>
                <div class="company-info">
                    <div class="company-name">{{ $dados['empresa']['nome'] }}</div>
                    <div>Contribuinte N.º: {{ $dados['empresa']['nif'] }}</div>
                    <div>{{ $dados['empresa']['rua'] }}, {{ $dados['empresa']['edificio'] }}</div>
                    <div>{{ $dados['empresa']['cidade'] }} | Telef. {{ $dados['empresa']['telefone'] }}</div>
                    <div>{{ $dados['empresa']['email'] }}</div>
                </div>
            </div>

            <div class="header-right client-section">
                <div class="client-label">Exmo.(s) Sr.(s)</div>
                <div class="client-name">{{ $dados['cliente']['nome'] }}</div>
                <div>NIF: {{ $dados['cliente']['nif'] }}</div>
                <div>{{ $dados['cliente']['localizacao'] }}</div>
                <div>{{ $dados['cliente']['cidade'] }}, {{ $dados['cliente']['provincia'] }}</div>
                @if($dados['cliente']['telefone'])
                <div>Tel: {{ $dados['cliente']['telefone'] }}</div>
                @endif
            </div>
        </div>

        <!-- TÍTULO DO DOCUMENTO -->
        <div class="invoice-title">
            <h1>{{ $dados['tipo_label'] ?? 'FATURA' }} {{ $dados['numero'] }}</h1>
            <div class="original">Original</div>
        </div>

        <!-- META INFORMAÇÕES -->
        <div class="invoice-meta-grid">
            <table class="meta-table">
                <tr>
                    <td class="meta-left">
                        <div class="meta-item clearfix">
                            <span class="meta-label">Moeda</span>
                            <span class="meta-value">{{ $dados['moeda'] }}</span>
                        </div>
                        <div class="meta-item clearfix">
                            <span class="meta-label">Vencimento</span>
                            <span class="meta-value">{{ $dados['data_vencimento'] }}</span>
                        </div>
                    </td>
                    <td class="meta-right">
                        <div class="meta-item clearfix">
                            <span class="meta-label">Data</span>
                            <span class="meta-value">{{ $dados['data_emissao'] }}</span>
                        </div>
                        <div class="meta-item clearfix">
                            <span class="meta-label">Condição Pagamento</span>
                            <span class="meta-value">{{ $dados['condicao_pagamento'] }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TABELA DE PRODUTOS -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Artigo</th>
                    <th style="width: 30%;">Descrição</th>
                    <th class="text-right" style="width: 8%;">Qtd.</th>
                    <th style="width: 8%;">Un.</th>
                    <th class="text-right" style="width: 12%;">Pr. Unitário</th>
                    <th class="text-right" style="width: 10%;">Desc.</th>
                    <th class="text-right" style="width: 10%;">IVA</th>
                    <th class="text-right" style="width: 12%;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['produtos'] as $pagina => $produtosPagina)
            <tbody>
                @foreach ($produtosPagina as $produto)
                <tr>
                    <td>
                        {{ $produto['id'] }}
                        <span class="item-code">{{ $produto['codigo_barras'] }}</span>
                    </td>
                    <td>{{ $produto['descricao'] }}</td>
                    <td class="text-right">{{ number_format($produto['quantidade'], 0) }}</td>
                    <td>{{ $produto['unidade'] }}</td>
                    <td class="text-right">{{ number_format($produto['preco_unitario'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produto['desconto'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produto['iva_valor'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produto['total'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>

            @if($pagina < count($dados['produtos']) - 1) <div class="page-break">
    </div>
    @endif
    @endforeach

    </tbody>
    </table>

    <!-- RESUMO E TOTAIS -->
    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <td class="summary-left">
                    <!-- RESUMO DE IMPOSTOS -->
                    <div class="tax-summary">
                        <h3>Quadro Resumo de Impostos</h3>

                        <table class="tax-table">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th class="text-right">Taxa %</th>
                                    <th class="text-right">Incidência</th>
                                    <th class="text-right">Valor Imposto</th>
                                    <th>Motivo Isenção</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dados['resumo_impostos'] as $imposto)
                                <tr>
                                    <td>{{ $imposto['descricao'] }}</td>
                                    <td class="text-right">{{ number_format($imposto['taxa'], 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($imposto['incidencia'], 2, ',', '.') }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($imposto['valor_imposto'], 2, ',', '.') }}</td>
                                    <td>
                                        @if($imposto['taxa'] === 0 && $imposto['motivo_isencao'])
                                        {{ $imposto['motivo_isencao'] }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </td>

                {{-- quadro direito com os totais --}}
                <td class="summary-right">
                    <div class="totals-box">
                        <div class="total-line clearfix">
                            <span class="label">Mercadoria/Serviços</span>
                            <span class="value">{{ number_format($dados['financeiro']['subtotal'], 2, ',', '.')
                                }}</span>
                        </div>
                        <div class="total-line clearfix">
                            <span class="label">IVA</span>
                            <span class="value">{{ number_format($dados['financeiro']['iva'], 2, ',', '.') }}</span>
                        </div>
                        <div class="total-line clearfix">
                            <span class="label">Descontos Comerciais</span>
                            <span class="value">{{ number_format($dados['financeiro']['desconto'], 2, ',', '.')
                                }}</span>
                        </div>
                        <div class="total-line grand-total clearfix">
                            <span class="label">Total ({{ $dados['moeda'] }})</span>
                            <span class="value">{{ number_format($dados['financeiro']['total'], 2, ',', '.') }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFORMAÇÕES BANCÁRIAS -->
    @if($dados['empresa']['banco'] && $dados['empresa']['iban'])
    <div class="bank-info">
        <strong>{{ strtoupper($dados['empresa']['banco']) }}</strong><br>
        <strong>IBAN:</strong> {{ $dados['empresa']['iban'] }}
    </div>
    @endif

    <!-- RODAPÉ -->
    <div class="footer">
        Processado por programa certificado |
        Os bens e/ou serviços foram colocados à disposição na data {{ $dados['data_emissao'] }}
    </div>
    </div>
</body>

</html>

{{-- analisar os dados retornados --}}
{{-- {{ dd($dados) }} --}}