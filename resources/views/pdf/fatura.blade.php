<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $dados['tipo_label'] }} {{ $dados['numero'] }}</title>
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
            padding: 10mm 10mm;
            background: #fff;
            position: relative;
        }

        /* Header */
        .header {
            width: 100%;
            padding-bottom: 5mm;
            overflow: hidden;
            margin-bottom: 2mm;
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
            font-size: 20pt;
            font-weight: 700;
            color: #00a8cc;
            margin-bottom: 3mm;
        }

        .company-info {
            font-size: 8pt;
            line-height: 1.4;
            color: #333;
        }

        .company-name {
            font-weight: 700;
            font-size: 9pt;
            color: #000;
            margin-bottom: 2px;
        }

        .client-section {
            text-align: right;
            font-size: 8pt;
            line-height: 1.4;
        }

        .client-label {
            font-weight: 700;
            margin-bottom: 2px;
            color: #555;
            text-transform: uppercase;
            font-size: 7pt;
        }

        .client-name {
            font-size: 10pt;
            font-weight: 700;
            color: #000;
            margin-bottom: 2px;
        }

        /* Document Title */
        .doc-title-block {
            clear: both;
            margin-top: 5mm;
            margin-bottom: 3mm;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2mm;
        }

        .doc-title {
            font-size: 14pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #000;
        }

        .doc-original {
            font-size: 8pt;
            color: #666;
            float: right;
            margin-top: 5px;
        }

        /* Meta Data Grid */
        .meta-table {
            width: 100%;
            font-size: 8pt;
            margin-bottom: 5mm;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 4px;
            border: 1px solid #eee;
            vertical-align: middle;
        }

        .meta-label {
            font-weight: 700;
            color: #555;
            background: #f9f9f9;
            width: 25%;
        }

        .meta-value {
            font-weight: 400;
            width: 25%;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
            min-height: 350px;
            font-size: 8pt;
        }

        .items-table th {
            background: #e5e7eb;
            color: #000;
            font-weight: 700;
            text-transform: uppercase;
            padding: 6px;
            text-align: left;
            border-bottom: 2px solid #000;
            font-size: 7.5pt;
        }

        .items-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .items-table tr:last-child td {
            border-bottom: 1px solid #000;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totals & Tax */
        .summary-block {
            margin-top: 5mm;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .tax-column {
            float: left;
            width: 55%;
            font-size: 7.5pt;
        }

        .total-column {
            float: right;
            width: 40%;
            font-size: 9pt;
        }

        .tax-table {
            width: 95%;
            border-collapse: collapse;
        }

        .tax-table th {
            background: #f3f4f6;
            padding: 4px;
            border: 1px solid #ddd;
            text-align: left;
            font-weight: 700;
        }

        .tax-table td {
            padding: 4px;
            border: 1px solid #ddd;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .totals-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
        }

        .totals-table .total-row td {
            border-top: 2px solid #000;
            font-weight: 700;
            font-size: 11pt;
            padding: 8px 10px;
        }

        /* Banking & Notes */
        .footer-info {
            margin-top: 5mm;
            font-size: 8pt;
            color: #444;
            border-left: 3px solid #00a8cc;
            padding: 5px 10px;
            background: #f0fbff;
        }

        .proforma-warning {
            margin-top: 5mm;
            text-align: center;
            font-weight: 700;
            color: #555;
            border: 1px dashed #999;
            padding: 5px;
            font-size: 8pt;
            text-transform: uppercase;
        }

        /* Footer Fixed */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 6.5pt;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 3mm;
        }

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
                <div class="logo-text">OMINIFINANCE</div> <!-- Ou Logo img -->
                <div class="company-info">
                    <div class="company-name">{{ $dados['empresa']['nome'] }}</div>
                    <div>NIF: {{ $dados['empresa']['nif'] }}</div>
                    <div>{{ $dados['empresa']['rua'] }} {{ $dados['empresa']['edificio'] }}</div>
                    <div>{{ $dados['empresa']['cidade'] }} {{ $dados['empresa']['provincia'] }}</div>
                    <div>{{ $dados['empresa']['telefone'] }} | {{ $dados['empresa']['email'] }}</div>
                </div>
            </div>

            <div class="header-right client-section">
                <div class="client-label">Exmo.(s) Sr.(s)</div>
                <div class="client-name">{{ $dados['cliente']['nome'] }}</div>

                @if($dados['cliente']['nif'])
                <div>NIF: {{ $dados['cliente']['nif'] }}</div>
                @else
                <div>Consumidor Final</div>
                @endif

                @if($dados['cliente']['localizacao'])
                <div>{{ $dados['cliente']['localizacao'] }}</div>
                @endif
                <div>{{ $dados['cliente']['cidade'] }}</div>

                @if($dados['cliente']['telefone'])
                <div>Tel: {{ $dados['cliente']['telefone'] }}</div>
                @endif
            </div>
        </div>

        <!-- TÍTULO -->
        <div class="doc-title-block">
            <span class="doc-title">{{ $dados['tipo_label'] }} <span style="font-weight:400; margin-left:10px;">{{
                    $dados['numero'] }}</span></span>
            <span class="doc-original">Original</span>
        </div>

        <!-- INFO DA FATURA (META DADOS) -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">Data Emissão</td>
                <td class="meta-value">{{ date('d/m/Y', strtotime($dados['data_emissao'])) }}</td>

                <td class="meta-label">Vencimento</td>
                <td class="meta-value">{{ date('d/m/Y', strtotime($dados['data_vencimento'])) }}</td>
            </tr>
            <tr>
                <td class="meta-label">Moeda</td>
                <td class="meta-value">{{ $dados['moeda'] }}</td>

                <td class="meta-label">Condição Pagam.</td>
                <td class="meta-value">
                    {{ $dados['condicao_pagamento'] }}
                    @if($dados['metodo_pagamento']) ({{ ucfirst($dados['metodo_pagamento']) }}) @endif
                </td>
            </tr>
        </table>

        <!-- ITENS DA FATURA -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="12%">Código</th>
                    <th width="40%">Descrição</th>
                    <th width="8%" class="text-center">Qtd</th>
                    <th width="13%" class="text-right">Pr. Unit.</th>
                    <th width="10%" class="text-right">Desc.</th>
                    <th width="8%" class="text-right">IVA %</th>
                    <th width="9%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['produtos'] as $grupoPagina)
                @foreach ($grupoPagina as $produto)
                <tr>
                    <td>{{ $produto['codigo_barras'] ?? $produto['id'] }}</td>
                    <td>{{ $produto['descricao'] }}</td>
                    <td class="text-center">{{ number_format($produto['quantidade'], 1, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produto['preco_unitario'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produto['desconto'] ?? 0, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produto['taxa_iva'], 0) }}%</td>
                    <td class="text-right font-bold">{{ number_format($produto['total'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- RESUMO FINANCEIRO -->
        <div class="summary-block">

            <!-- Esquerda: Impostos -->
            <div class="tax-column">
                <strong>Quadro Resumo de Impostos</strong>
                <div style="margin-bottom: 5px;"></div>
                <table class="tax-table">
                    <thead>
                        <tr>
                            <th>Descrição / Taxa</th>
                            <th class="text-right">Incidência</th>
                            <th class="text-right">Valor Imposto</th>
                            <th>Motivo Isenção</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dados['resumo_impostos'] as $imposto)
                        <tr>
                            <td>{{ $imposto['descricao'] }}</td>
                            <td class="text-right">{{ number_format($imposto['incidencia'], 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($imposto['valor_imposto'], 2, ',', '.') }}</td>
                            <td style="font-size: 6.5pt; color:#555;">{{ $imposto['motivo_isencao'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Dados Bancários -->
                @if($dados['empresa']['iban'] && !$dados['is_proforma'])
                <div class="footer-info">
                    <strong>Coordenadas Bancárias:</strong><br>
                    Banco: {{ strtoupper($dados['empresa']['banco']) }}<br>
                    IBAN: {{ $dados['empresa']['iban'] }}
                </div>
                @endif
            </div>

            <!-- Direita: Totais -->
            <div class="total-column">
                <table class="totals-table">
                    <tr>
                        <td>Total Ilíquido</td>
                        <td class="text-right">{{ number_format($dados['financeiro']['subtotal'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Descontos</td>
                        <td class="text-right">{{ number_format($dados['financeiro']['desconto'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Impostos</td>
                        <td class="text-right">{{ number_format($dados['financeiro']['iva'], 2, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>TOTAL A PAGAR</td>
                        <td class="text-right">{{ number_format($dados['financeiro']['total'], 2, ',', '.') }} {{
                            $dados['moeda'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- AVISO DE PROFORMA -->
        @if(isset($dados['is_proforma']) && $dados['is_proforma'])
        <div class="proforma-warning">
            Este documento não serve de recibo e não confere direito à dedução do IVA.
        </div>
        @endif

        <!-- RODAPÉ -->
        <div class="footer">
            {{ $dados['tipo_label'] }} processado por Computador (Software Certificado). <br>
            Os bens/serviços foram colocados à disposição na data do documento.
        </div>

    </div>
</body>

</html>