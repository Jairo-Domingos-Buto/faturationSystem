<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Factura {{ $dados['numero'] }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Arial", sans-serif;
      font-size: 9pt;
      background: #fff;
      color: #111;
      line-height: 1.4;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
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

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 10mm;
      padding-bottom: 5mm;
    }

    .logo-text {
      font-size: 28pt;
      font-weight: 300;
      color: #00a8cc;
    }

    .logo-text .x {
      font-weight: 700;
      color: #000;
    }

    .company-info {
      font-size: 8pt;
      line-height: 1.5;
    }

    .company-name {
      font-weight: 700;
      margin-bottom: 4px;
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

    .invoice-title {
      text-align: right;
      margin: 1mm 0 1mm;
    }

    .invoice-title h1 {
      font-size: 11pt;
      font-weight: 700;
    }

    .invoice-title .original {
      font-size: 8pt;
      color: #555;
    }

    .invoice-meta-grid {
      display: flex;
      justify-content: space-between;
      border-top: 1px solid #e0e0e0;
      border-bottom: 1px solid #e0e0e0;
      padding: 6mm 0;
      font-size: 8pt;
    }

    .meta-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 3px;
      min-width: 200px;
    }

    .meta-label {
      color: #555;
      margin-right: 20px;
    }

    .meta-value {
      font-weight: 600;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10mm;
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
    }

    .summary-section {
      margin-top: 10mm;
      border-top: 1px solid #e0e0e0;
      padding-top: 5mm;
      display: flex;
      justify-content: space-between;
    }

    .tax-summary {
      flex: 1;
      min-width: 260px;
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
      flex: 0 0 280px;
      background: #f8f9fa;
      border-radius: 6px;
      padding: 15px;
      margin-left: 20px;
    }

    .total-line {
      display: flex;
      justify-content: space-between;
      font-size: 9pt;
      padding: 4px 0;
    }

    .total-line.grand-total {
      font-weight: 700;
      font-size: 11pt;
      border-top: 2px solid #333;
      margin-top: 6px;
      padding-top: 6px;
    }

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

    .footer {
      margin-top: 6mm;
      padding-top: 3mm;
      border-top: 1px solid #e0e0e0;
      font-size: 6.5pt;
      color: #777;
      text-align: center;
    }

    @page {
      size: A4;
      margin: 10mm;
    }

    @media print {
      body {
        -webkit-print-color-adjust: exact;
      }

      .invoice {
        width: 100%;
        margin: 0;
        padding: 10mm;
      }
    }
  </style>
</head>

<body>
  <div class="invoice">
    <div class="page-number">Pág. 1/1</div>

    <!-- HEADER -->
    <div class="header">
      <div>
        <div class="logo-text">MINDSEAT</div>
        <div class="company-info">
          <div class="company-name">{{ $dados['empresa']['nome'] }}</div>
          <div>Contribuinte N.º: {{ $dados['empresa']['nif'] }}</div>
          <div>{{ $dados['empresa']['rua'] }}, {{ $dados['empresa']['edificio'] }}</div>
          <div>{{ $dados['empresa']['cidade'] }} | Telef. {{ $dados['empresa']['telefone'] }}</div>
          <div>{{ $dados['empresa']['email'] }}</div>
        </div>
      </div>

      <div class="client-section">
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

    <!-- TÍTULO DA FATURA -->
    <div class="invoice-title">

      <h1 style="text-transform: uppercase;">{{ $dados['tipo_documento'] }} {{ $dados['numero'] }}</h1>
      <div class="original">Original</div>
    </div>

    <!-- META INFORMAÇÕES -->
    <div class="invoice-meta-grid">
      <div>
        <div class="meta-item">
          <span class="meta-label">Moeda</span>
          <span class="meta-value">{{ $dados['moeda'] }}</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Vencimento</span>
          <span class="meta-value">{{ $dados['data_vencimento'] }}</span>
        </div>
      </div>
      <div>
        <div class="meta-item">
          <span class="meta-label">Data</span>
          <span class="meta-value">{{ $dados['data_emissao'] }}</span>
        </div>
        <div class="meta-item">
          <span class="meta-label">Condição Pagamento</span>
          <span class="meta-value">{{ $dados['condicao_pagamento'] }}</span>
        </div>
      </div>
    </div>

    <!-- TABELA DE PRODUTOS -->
    <table class="items-table">
      <thead>
        <tr>
          <th>Artigo</th>
          <th>Descrição</th>
          <th class="text-right">Qtd.</th>
          <th>Un.</th>
          <th class="text-right">Pr. Unitário</th>
          <th class="text-right">Desc.</th>
          <th class="text-right">IVA</th>
          <th class="text-right">Valor</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dados['produtos'] as $produto)
        <tr>
          <td>
            {{ $produto['id'] }}<br>
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
    </table>

    <!-- RESUMO E TOTAIS -->
    <div class="summary-section">
      <div class="tax-summary">
        <h3>Quadro Resumo de Impostos</h3>
        <table class="tax-table">
          <thead>
            <tr>
              <th>Taxa/Valor</th>
              <th class="text-right">Incid./Qtd.</th>
              <th class="text-right">Total</th>
              <th>Motivo Isenção</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>IVA (14%)</td>
              <td class="text-right">{{ number_format($dados['financeiro']['incidencia'], 2, ',', '.') }}</td>
              <td class="text-right">{{ number_format($dados['financeiro']['iva'], 2, ',', '.') }}</td>
              <td>{{ $dados['empresa']['regime'] }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="totals-box">
        <div class="total-line">
          <span>Mercadoria/Serviços</span>
          <span>{{ number_format($dados['financeiro']['subtotal'], 2, ',', '.') }}</span>
        </div>
        <div class="total-line">
          <span>IVA</span>
          <span>{{ number_format($dados['financeiro']['iva'], 2, ',', '.') }}</span>
        </div>
        <div class="total-line">
          <span>Descontos Comerciais</span>
          <span>{{ number_format($dados['financeiro']['desconto'], 2, ',', '.') }}</span>
        </div>
        <div class="total-line grand-total">
          <span>Total ({{ $dados['moeda'] }})</span>
          <span>{{ number_format($dados['financeiro']['total'], 2, ',', '.') }}</span>
        </div>
      </div>
    </div>

    <!-- INFORMAÇÕES BANCÁRIAS -->
    @if($dados['empresa']['banco'] && $dados['empresa']['iban'])
    <div class="bank-info">
      <strong>{{ strtoupper($dados['empresa']['banco']) }}</strong><br>
      <strong>IBAN:</strong> {{ $dados['empresa']['iban'] }}<br>
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