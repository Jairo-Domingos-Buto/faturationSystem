<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nota de Crédito {{ $dados['numero_nota_credito'] }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 9pt;
      padding: 20px;
      background: white;
      color: #000;
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
      right: 20mm;
      font-size: 8pt;
      color: #666;
    }

    .logo {
      margin-bottom: 10px;
    }

    .logo svg {
      width: 60px;
      height: 60px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      padding-bottom: 10px;
    }

    .company-info {
      flex: 1;
      font-size: 8pt;
      line-height: 1.6;
    }

    .company-name {
      font-size: 14pt;
      font-weight: bold;
      margin-bottom: 8px;
      color: purple;
    }

    .client-section {
      flex: 1;
      text-align: right;
    }

    .client-label {
      font-size: 8pt;
      margin-bottom: 5px;
    }

    .client-name {
      font-size: 10pt;
      font-weight: 700;
    }

    .client-address {
      font-size: 8pt;
      line-height: 1.5;
    }

    .invoice-title {
      margin: 20px 0;
      text-align: right;
    }

    .invoice-title h2 {
      font-size: 18pt;
      font-weight: 700;
      color: #c00;
    }

    .original {
      font-size: 8pt;
      font-weight: 400;
    }

    .annulment {
      margin: 15px 0;
      padding: 10px;
      background: #fff0f0;
      border: 2px solid red;
      font-weight: bold;
      font-size: 8pt;
    }

    .invoice-meta-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      margin: 20px 0;
      padding: 15px 0;
      border-top: 1px solid #e0e0e0;
      border-bottom: 1px solid #e0e0e0;
      font-size: 8pt;
    }

    .meta-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .meta-label {
      color: #666;
    }

    .meta-value {
      font-weight: 600;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 8pt;
    }

    .items-table thead {
      background: #f8f9fa;
      border-top: 2px solid #333;
      border-bottom: 2px solid #333;
    }

    .items-table th {
      padding: 8px 6px;
      text-align: left;
      font-weight: 600;
      font-size: 7pt;
      color: #333;
      text-transform: uppercase;
    }

    .items-table td {
      padding: 10px 6px;
      border-bottom: 1px solid #e8e8e8;
      vertical-align: top;
    }

    /* Forçar alinhamento por coluna (melhor compatibilidade com DomPDF) */
    .items-table thead th:nth-child(1),
    .items-table tbody td:nth-child(1) {
      text-align: left;
    }

    .items-table thead th:nth-child(2),
    .items-table tbody td:nth-child(2) {
      text-align: left;
    }

    .items-table thead th:nth-child(3),
    .items-table tbody td:nth-child(3) {
      text-align: right;
    }

    .items-table thead th:nth-child(4),
    .items-table tbody td:nth-child(4) {
      text-align: right;
    }

    .items-table thead th:nth-child(5),
    .items-table tbody td:nth-child(5) {
      text-align: right;
    }

    .items-table thead th:nth-child(6),
    .items-table tbody td:nth-child(6) {
      text-align: center;
    }

    .items-table thead th:nth-child(7),
    .items-table tbody td:nth-child(7) {
      text-align: right;
    }

    .items-table tbody tr:last-child td {
      border-bottom: 2px solid #333;
    }

    < !DOCTYPE html><html lang="pt"><head><meta charset="UTF-8" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title> {
        {
        $dados['tipo_label'] ?? 'Recibo'
      }
    }

      {
        {
        $dados['numero'] ?? $dados['numero_nota_credito'] ?? ''
      }
    }

    </title><style>* {
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
      color: red;
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

    /* Bloco de assinatura final */
    .signature-block {
      margin-top: 18mm;
      overflow: hidden;
      page-break-inside: avoid;
    }

    .sig-column {
      float: left;
      width: 48%;
      text-align: left;
      min-height: 80px;
    }

    .sig-column.right {
      float: right;
      text-align: right;
    }

    .sig-line {
      display: inline-block;
      width: 75%;
      border-top: 1px solid #000;
      margin-top: 36px;
    }

    .sig-line.right {
      display: inline-block;
      width: 75%;
      border-top: 1px solid #000;
      margin-top: 36px;
    }

    .sig-label {
      font-size: 8pt;
      color: #333;
      margin-top: 6px;
    }
  </style>
</head>

<body>
  <div class="invoice">

    <!-- HEADER -->
    <div class="header clearfix">
      <div class="header-left">
        <div class="logo-text">{{ $dados['empresa']['nome'] ?? '' }}</div>
        <div class="company-info">
          <div class="company-name">{{ $dados['empresa']['nome'] ?? '' }}</div>
          <div>Contribuinte N.º: {{ $dados['empresa']['nif'] ?? ($dados['empresa']['nuit'] ?? '') }}</div>
          <div>{{ $dados['empresa']['rua'] ?? $dados['empresa']['endereco'] ?? '' }}, {{ $dados['empresa']['edificio']
            ?? '' }}</div>
          <div>{{ $dados['empresa']['cidade'] ?? '' }} | Telef. {{ $dados['empresa']['telefone'] ?? '' }}</div>
          <div>{{ $dados['empresa']['email'] ?? '' }}</div>
        </div>
      </div>

      <div class="header-right client-section">
        <div class="client-label">Exmo.(s) Sr.(s)</div>
        <div class="client-name">{{ $dados['cliente']['nome'] ?? ($dados['cliente']['razao_social'] ?? '') }}</div>
        <div>NIF: {{ $dados['cliente']['nif'] ?? '' }}</div>
        <div>{{ $dados['cliente']['localizacao'] ?? ($dados['cliente']['endereco'] ?? '') }}</div>
        <div>{{ $dados['cliente']['cidade'] ?? '' }}, {{ $dados['cliente']['provincia'] ?? '' }}</div>
        @if(!empty($dados['cliente']['telefone']))
        <div>Tel: {{ $dados['cliente']['telefone'] }}</div>
        @endif
      </div>
    </div>
    @php
    // Tipo legível (recibo / Recibo)
    $tipo_raw = data_get($dados, 'tipo_documento') ?? '';
    if (!empty(data_get($dados, 'tipo_label'))) {
    $tipo_label = data_get($dados, 'tipo_label');
    } elseif (stripos($tipo_raw, 'recibo') !== false) {
    $tipo_label = 'NOTA DE CRÉDITO - recibo';
    } elseif (stripos($tipo_raw, 'recibo') !== false) {
    $tipo_label = 'NOTA DE CRÉDITO - RECIBO';
    } else {
    $tipo_label = strtoupper(str_replace('_', ' ', $tipo_raw)) ?: 'NOTA DE CRÉDITO';
    }
    // Motivo com vários fallbacks (array/objeto)
    $motivo = data_get($dados, 'retificacao.motivo')
    ?? data_get($dados, 'documento_anulado.motivo_anulacao')
    ?? data_get($dados, 'recibo_original.motivo_anulacao')
    ?? data_get($dados, 'motivo_anulacao')
    ?? data_get($dados, 'valores_devolvidos.motivo')
    ?? '';

    // Número e data do documento anulado (recibo/recibo)
    $orig_numero = data_get($dados, 'documento_anulado.numero')
    ?? data_get($dados, 'recibo_original.numero')
    ?? data_get($dados, 'recibo_original.numero')
    ?? '-';
    $orig_data = data_get($dados, 'documento_anulado.data_emissao')
    ?? data_get($dados, 'recibo_original.data_emissao')
    ?? data_get($dados, 'recibo_original.data_emissao')
    ?? '';
    @endphp

    <div class="annulment">
      <strong>{{ $tipo_label }}</strong><br>
      Motivo: {{ $motivo ?: 'Sem motivo registado' }}<br>
      Documento de Origem: {{ $orig_numero }} @if($orig_data) - {{ $orig_data }}@endif
    </div>

    <!-- TÍTULO DO DOCUMENTO -->
    <div class="invoice-title">
      <h1>{{ $tipo_label ?? ($dados['tipo_label'] ?? 'recibo') }} {{ $dados['numero_nota_credito'] ?? $dados['numero']
        ?? $orig_numero ?? '' }}</h1>
      <div class="original">Original</div>
    </div>

    <!-- META INFORMAÇÕES -->
    <div class="invoice-meta-grid">
      <table class="meta-table">
        <tr>
          <td class="meta-left">
            <div class="meta-item clearfix">
              <span class="meta-label">Moeda</span>
              <span class="meta-value">{{ $dados['moeda'] ?? ($dados['empresa']['moeda'] ?? 'KZ') }}</span>
            </div>
            <div class="meta-item clearfix">
              <span class="meta-label">Vencimento</span>
              <span class="meta-value">{{ $dados['data_vencimento'] ?? ($dados['documento_anulado']['data_vencimento']
                ?? '-') }}</span>
            </div>
          </td>
          <td class="meta-right">
            <div class="meta-item clearfix">
              <span class="meta-label">Data</span>
              <span class="meta-value">{{ $dados['data_emissao'] ?? ($dados['data_emissao_nota'] ??
                ($dados['documento_anulado']['data_emissao'] ?? '')) }}</span>
            </div>
            <div class="meta-item clearfix">
              <span class="meta-label">Condição Pagamento</span>
              <span class="meta-value">{{ $dados['condicao_pagamento'] ?? ($dados['condicao'] ?? '-') }}</span>
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
          <th class="text-right" style="width: 12%;">Desc.</th>
          <th class="text-right" style="width: 10%;">IVA</th>
          <th class="text-right" style="width: 12%;">Valor</th>
        </tr>
      </thead>
      <tbody>
        @php
        $produtos = $dados['documento_anulado']['produtos'] ?? $dados['produtos'] ?? [];
        @endphp
        @foreach ($produtos as $produto)
        <tr>
          <td>
            {{ $produto['id'] ?? $produto['codigo'] ?? '-' }}
            @if(!empty($produto['codigo_barras']))
            <span class="item-code">{{ $produto['codigo_barras'] }}</span>
            @endif
          </td>
          <td>{{ $produto['descricao'] ?? $produto['nome'] ?? '-' }}</td>
          <td class="text-right">{{ number_format($produto['quantidade'] ?? ($produto['qtd'] ?? 0), 0, ',', '.') }}</td>
          <td>{{ $produto['unidade'] ?? ($produto['unidad'] ?? '-') }}</td>
          <td class="text-right">{{ number_format($produto['preco_unitario'] ?? ($produto['preco'] ?? 0), 2, ',', '.')
            }}</td>
          <td class="text-right">{{ number_format($produto['desconto'] ?? 0, 2, ',', '.') }}</td>
          <td class="text-right">{{ number_format($produto['iva_valor'] ?? ($produto['valor_iva'] ?? 0), 2, ',', '.') }}
          </td>
          <td class="text-right">{{ number_format($produto['total'] ?? ($produto['valor'] ?? 0), 2, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- RESUMO E TOTAIS -->
    <div class="summary-section">
      <table class="summary-table">

      </table>
    </div>

    <!-- INFORMAÇÕES BANCÁRIAS -->
    @if(!empty($dados['empresa']['banco']) && !empty($dados['empresa']['iban']))
    <div class="bank-info">
      <strong>{{ strtoupper($dados['empresa']['banco']) }}</strong><br>
      <strong>IBAN:</strong> {{ $dados['empresa']['iban'] }}
    </div>
    @endif
    <!-- BLOCO DE ASSINATURAS -->
    <div class="signature-block clearfix">
      <div class="sig-column">
        <div class="sig-line"></div>
        <div class="sig-label">Assinatura do Cliente</div>
      </div>

    </div>

    <!-- RODAPÉ -->
    <div class="footer">
      Processado por programa certificado |
      Os bens e/ou serviços foram colocados à disposição na data {{ $dados['data_emissao'] ??
      ($dados['data_emissao_nota'] ?? '') }}
    </div>
  </div>
</body>

</html>