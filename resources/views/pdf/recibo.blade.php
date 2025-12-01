<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{{ $dados['tipo_label'] }}</title>
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
      width: 95%;
      margin: 0 auto;
      padding: 1mm;
      background: #fff;
      position: relative;
    }

    /* Header */
    .header {
      width: 100%;
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
      margin: 2mm 0;
      clear: both;
    }

    .invoice-title h1 {
      font-size: 11pt;
      font-weight: 700;
      margin-bottom: 2px;
      text-transform: uppercase;
    }

    .invoice-title .doc-number {
      font-size: 10pt;
      color: #333;
      margin-bottom: 2px;
    }

    .invoice-title .original {
      font-size: 8pt;
      color: #555;
    }

    /* Retificação Box */
    .rectification-box {
      width: 100%;
      background-color: #fcf8e3;
      border: 1px solid #faebcc;
      color: #8a6d3b;
      padding: 8px 10px;
      margin-bottom: 5mm;
      font-size: 8pt;
      border-radius: 4px;
    }

    .rectification-box strong {
      display: block;
      margin-bottom: 3px;
    }

    /* Anulado Box */
    .anulado-box {
      width: 100%;
      background-color: #f2dede;
      border: 1px solid #ebccd1;
      color: #a94442;
      padding: 8px 10px;
      margin-bottom: 5mm;
      font-size: 9pt;
      font-weight: 700;
      text-align: center;
      border-radius: 4px;
    }

    /* Metadados do Documento */
    .document-metadata {
      width: 100%;
      overflow: hidden;
      margin-bottom: 5mm;
      font-size: 8pt;
      border-top: 1px solid #e0e0e0;
      padding-top: 3mm;
    }

    .metadata-left {
      float: left;
      width: 50%;
    }

    .metadata-right {
      float: right;
      width: 50%;
      text-align: right;
    }

    .metadata-line {
      margin-bottom: 2px;
    }

    .metadata-line strong {
      color: #333;
    }

    /* Items Table */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5mm;
      min-height: 500px;
      font-size: 8pt;
      border-top: 2px solid #333;
      border-bottom: 1px solid #333;
    }

    .items-table th {
      background: #f3f4f6;
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

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    /* Summary */
    .summary-section {
      margin-top: 5mm;
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

    /* Tax Table */
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

    /* Totals */
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

    /* Assinaturas */
    .signatures {
      margin-top: 15mm;
      width: 100%;
      overflow: hidden;
    }

    .sig-box {
      float: left;
      width: 45%;
      text-align: center;
    }

    .sig-box.right {
      float: right;
    }

    .sig-line {
      border-top: 1px solid #333;
      margin-bottom: 5px;
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }

    .sig-label {
      font-size: 8pt;
      color: #555;
    }

    /* Footer */
    .footer {
      margin-top: 3mm;
      padding-top: 3mm;
      border-top: 1px solid #e0e0e0;
      font-size: 6.5pt;
      color: #777;
      text-align: center;
    }

    .bank-info {
      margin-top: 5mm;
      background: #f8f9fa;
      border-left: 3px solid #00a8cc;
      padding: 8px 12px;
      font-size: 8pt;
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
        <div class="logo-text">OMINIFINANCE</div>
        <div class="company-info">
          <div class="company-name">{{ $dados['empresa']['nome'] }}</div>
          <div>NIF: {{ $dados['empresa']['nif'] }}</div>
          <div>{{ $dados['empresa']['endereco'] }}@if(!empty($dados['empresa']['edificio'])) {{
            $dados['empresa']['edificio'] }}@endif</div>
          <div>{{ $dados['empresa']['cidade'] }}@if(!empty($dados['empresa']['provincia'])) - {{
            $dados['empresa']['provincia'] }}@endif</div>
          @if(!empty($dados['empresa']['telefone']))
          <div>Telef. {{ $dados['empresa']['telefone'] }}</div>
          @endif
          @if(!empty($dados['empresa']['email']))
          <div>{{ $dados['empresa']['email'] }}</div>
          @endif
        </div>
      </div>

      <div class="header-right client-section">
        <div class="client-label">Exmo.(s) Sr.(s)</div>
        <div class="client-name">{{ $dados['cliente']['nome'] }}</div>
        @if(!empty($dados['cliente']['nif']))
        <div>NIF: {{ $dados['cliente']['nif'] }}</div>
        @endif
        @if(!empty($dados['cliente']['localizacao']))
        <div>{{ $dados['cliente']['localizacao'] }}</div>
        @endif
        @if(!empty($dados['cliente']['cidade']))
        <div>{{ $dados['cliente']['cidade'] }}@if(!empty($dados['cliente']['provincia'])) - {{
          $dados['cliente']['provincia'] }}@endif</div>
        @endif
        @if(!empty($dados['cliente']['telefone']))
        <div>Tel: {{ $dados['cliente']['telefone'] }}</div>
        @endif
      </div>
    </div>

    <!-- TÍTULO DO DOCUMENTO -->
    <div class="invoice-title">
      <h1>{{ $dados['tipo_label'] }}</h1>
      <div class="doc-number">{{ $dados['numero'] }}</div>
      <div class="original">Documento Original</div>
    </div>

    <!-- BOX DE ANULADO (se aplicável) -->
    @if($dados['anulado'])
    <div class="anulado-box">
      ⚠ DOCUMENTO ANULADO ⚠
    </div>
    @endif

    <!-- BOX DE RETIFICAÇÃO (se aplicável) -->
    @if($dados['is_retificacao'])
    <div class="rectification-box">
      <strong>⚠ DOCUMENTO RETIFICATIVO</strong>
      Retifica o documento: <strong>{{ $dados['recibo_original_numero'] }}</strong><br>
      @if(!empty($dados['motivo_retificacao']))
      Motivo: {{ $dados['motivo_retificacao'] }}
      @endif
    </div>
    @endif

    {{--
    <!-- METADADOS DO DOCUMENTO -->
    <div class="document-metadata clearfix">
      <div class="metadata-left">
        <div class="metadata-line">
          <strong>Data de Emissão:</strong> {{ $dados['data_emissao'] }}
        </div>
        <div class="metadata-line">
          <strong>Método de Pagamento:</strong> {{ $dados['metodo_pagamento'] }}
        </div>
      </div>
      <div class="metadata-right">
        <div class="metadata-line">
          <strong>Moeda:</strong> {{ $dados['moeda'] }}
        </div>
        <div class="metadata-line">
          <strong>Condição:</strong> {{ $dados['condicao_pagamento'] }}
        </div>
      </div>
    </div> --}}

    <!-- TABELA DE ITENS -->
    <table class="items-table">
      <thead>
        <tr>

          <th style="width: 35%; text-align: start;">Descrição</th>
          <th style="width: 8%; text-align: center;">Qtd.</th>
          <th style="width: 6%; text-align: center;">Un.</th>
          <th style="width: 12%; text-align: center;">Pr. Unit.</th>
          <th style="width: 8%; text-align: center;">IVA</th>
          <th style="width: 16%; text-align: center;">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dados['produtos'] as $produto)
        <tr>

          <td>
            {{ $produto['descricao'] }}
            @if($produto['taxa_iva'] == 0)
            <br><small style="color:#777; font-size:6pt;">(Isento)</small>
            @endif
          </td>
          <td class="text-center">{{ number_format($produto['quantidade'], 1, ',', '.') }}</td>
          <td class="text-center">{{ $produto['unidade'] }}</td>
          <td class="text-right">{{ number_format($produto['preco_unitario'], 2, ',', '.') }}</td>
          <td class="text-center">{{ number_format($produto['taxa_iva'], 0) }}%</td>
          <td class="text-right"><strong>{{ number_format($produto['total'], 2, ',', '.') }}</strong></td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- RESUMO E TOTAIS -->
    <div class="summary-section">
      <table class="summary-table">
        <tr>
          <td class="summary-left">
            <!-- QUADRO DE IMPOSTOS -->
            <div class="tax-summary">
              <h3>Resumo de Impostos</h3>
              <table class="tax-table">
                <thead>
                  <tr>
                    <th>Taxa / Descrição</th>
                    <th class="text-right">Incidência</th>
                    <th class="text-right">Valor Imposto</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($dados['resumo_impostos'] as $imposto)
                  <tr>
                    <td>
                      {{ $imposto['descricao'] }}
                      @if($imposto['taxa'] > 0)
                      ({{ number_format($imposto['taxa'], 0) }}%)
                      @endif
                      @if(!empty($imposto['motivo_isencao']))
                      <br><small style="font-size:6.5pt; color:#666;">{{ $imposto['motivo_isencao'] }}</small>
                      @endif
                    </td>
                    <td class="text-right">{{ number_format($imposto['incidencia'], 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($imposto['valor_imposto'], 2, ',', '.') }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- INFO BANCÁRIA -->
            @if(!empty($dados['empresa']['banco']) && !empty($dados['empresa']['iban']))
            <div class="bank-info">
              <strong>{{ strtoupper($dados['empresa']['banco']) }}</strong><br>
              IBAN: {{ $dados['empresa']['iban'] }}
            </div>
            @endif
          </td>

          <!-- TOTAIS -->
          <td class="summary-right">
            <div class="totals-box">
              <div class="total-line clearfix">
                <span class="label">Total Ilíquido</span>
                <span class="value">{{ number_format($dados['financeiro']['subtotal'], 2, ',', '.') }}</span>
              </div>
              <div class="total-line clearfix">
                <span class="label">Total IVA</span>
                <span class="value">{{ number_format($dados['financeiro']['iva'], 2, ',', '.') }}</span>
              </div>
              @if($dados['financeiro']['desconto'] > 0)
              <div class="total-line clearfix">
                <span class="label">Desconto</span>
                <span class="value">{{ number_format($dados['financeiro']['desconto'], 2, ',', '.') }}</span>
              </div>
              @endif
              <div class="total-line grand-total clearfix">
                <span class="label">TOTAL PAGO</span>
                <span class="value">{{ number_format($dados['financeiro']['total'], 2, ',', '.') }} {{ $dados['moeda']
                  }}</span>
              </div>
              @if($dados['financeiro']['troco'] > 0)
              <div class="total-line clearfix" style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #ddd;">
                <span class="label">Troco</span>
                <span class="value">{{ number_format($dados['financeiro']['troco'], 2, ',', '.') }} {{ $dados['moeda']
                  }}</span>
              </div>
              @endif
            </div>
          </td>
        </tr>
      </table>
    </div>


    <!-- RODAPÉ -->
    <div class="footer">
      {{ $dados['tipo_label'] }} processado por Computador.<br>
      Data de Processamento: {{ date('d/m/Y H:i') }}
    </div>
  </div>
</body>

</html>