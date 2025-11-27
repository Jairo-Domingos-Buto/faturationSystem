<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{{ $dados['tipo_label'] }} {{ $dados['numero_nota_credito'] }}</title>
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

    .invoice-title .original {
      font-size: 8pt;
      color: #555;
    }

    /* Retificação Box (NOVO) */
    .rectification-box {
      width: 100%;
      background-color: #fcf8e3;
      /* Amarelo suave */
      border: 1px solid #faebcc;
      color: #8a6d3b;
      padding: 8px 10px;
      margin-bottom: 5mm;
      font-size: 8pt;
      border-radius: 4px;
    }

    /* Items Table */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5mm;
      min-height: 300px;
      /* Reduzi um pouco para caber assinaturas */
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

    /* Assinaturas (NOVO) */
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

    .text-center {
      text-align: center;
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

    <!-- HEADER (Idêntico à Fatura) -->
    <div class="header clearfix">
      <div class="header-left">
        <div class="logo-text">OMINIFINANCE</div>
        <div class="company-info">
          <div class="company-name">{{ $dados['empresa']['nome'] }}</div>
          <div>NIF: {{ $dados['empresa']['nif'] }}</div>
          <div>{{ $dados['empresa']['endereco'] }} {{ $dados['empresa']['edificio'] }}</div>
          <div>{{ $dados['empresa']['cidade'] }} | Telef. {{ $dados['empresa']['telefone'] }}</div>
          <div>{{ $dados['empresa']['email'] }}</div>
        </div>
      </div>

      <div class="header-right client-section">
        <div class="client-label">Exmo.(s) Sr.(s)</div>
        <div class="client-name">{{ $dados['cliente']['nome'] }}</div>
        <div>NIF: {{ $dados['cliente']['nif'] }}</div>
        <div>{{ $dados['cliente']['localizacao'] }}</div>
        <div>{{ $dados['cliente']['cidade'] }} {{ $dados['cliente']['provincia'] }}</div>
        @if($dados['cliente']['telefone'])
        <div>Tel: {{ $dados['cliente']['telefone'] }}</div>
        @endif
      </div>
    </div>

    <!-- TÍTULO DO DOCUMENTO -->
    <div class="invoice-title">
      <h1>{{ $dados['tipo_label'] }} {{ $dados['numero_nota_credito'] }}</h1>
      <div class="original">Documento de Retificação</div>
    </div>

    <!-- INFO DA RETIFICAÇÃO (Estilo Destaque) -->
    <div class="rectification-box clearfix">
      <div style="float:left; width: 60%;">
        <strong>REFERENTE AO DOCUMENTO:</strong> {{ $dados['documento_anulado']['tipo'] }} {{
        $dados['documento_anulado']['numero'] }}
        <br>
        <strong>DATA ORIGINAL:</strong> {{ $dados['documento_anulado']['data_emissao'] }}
      </div>
      <div style="float:right; width: 40%; text-align: right;">
        <strong>MOTIVO:</strong> {{ $dados['retificacao']['motivo'] }}
      </div>
    </div>

    <!-- TABELA DE PRODUTOS (Dados do Documento Anulado/Origem) -->
    <table class="items-table">
      <thead>
        <tr>
          <th style="width: 15%;text-align: center;">Código</th>
          <th style="width: 35%;text-align: start;">Descrição</th>
          <th class="" style="width: 3%;text-align: center;">Qtd.</th>
          <th class="" style="width: 6%;text-align: center;">Pr. Unit.</th>
          <th class="text-right" style="width: 4%;text-align: center;">IVA</th>
          <th class="text-right" style="width: 15%;text-align: center;">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dados['documento_anulado']['produtos'] as $produto)
        <tr>
          <td>{{ $produto['codigo'] ?? '-' }}</td>
          <td>
            {{ $produto['descricao'] }}
            @if(isset($produto['taxa_iva']) && $produto['taxa_iva'] == 0)
            <br><small style="color:#777; font-size:6pt;">(Isento)</small>
            @endif
          </td>
          <td class="text-right">{{ number_format($produto['quantidade'], 1, ',', '.') }}</td>
          <td class="text-right">{{ number_format($produto['preco_unitario'], 2, ',', '.') }}</td>
          <td class="text-right">{{ number_format($produto['taxa_iva'], 0) }}%</td>
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
              <h3>Resumo de Impostos (Incidência)</h3>
              <table class="tax-table">
                <thead>
                  <tr>
                    <th>Taxa / Descrição</th>
                    <th class="text-right">Incidência</th>
                    <th class="text-right">Valor Imposto</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($dados['documento_anulado']['resumo_impostos'] as $imposto)
                  <tr>
                    <td>{{ $imposto['descricao'] }}</td>
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

          {{-- TOTAIS --}}
          <td class="summary-right">
            <div class="totals-box">
              <div class="total-line clearfix">
                <span class="label">Total Ilíquido</span>
                <span class="value">{{ number_format($dados['documento_anulado']['subtotal'], 2, ',', '.') }}</span>
              </div>
              <div class="total-line clearfix">
                <span class="label">Total Impostos</span>
                <span class="value">{{ number_format($dados['documento_anulado']['total_impostos'], 2, ',', '.')
                  }}</span>
              </div>
              <div class="total-line grand-total clearfix">
                <span class="label">TOTAL A CREDITAR</span>
                <span class="value">{{ number_format($dados['documento_anulado']['total'], 2, ',', '.') }} AOA</span>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </div>

    <!-- ASSINATURAS -->
    <div class="signatures clearfix">
      <div class="sig-box">
        <br><br><br>
        <div class="sig-line"></div>
        <div class="sig-label">O Cliente</div>
      </div>
      <div class="sig-box right">
        <br><br><br>
        <div class="sig-line"></div>
        <div class="sig-label">A Empresa</div>
      </div>
    </div>

    <!-- RODAPÉ -->
    <div class="footer">
      {{ $dados['tipo_label'] }} processado por Computador.<br>
      Operador: {{ $dados['retificacao']['usuario'] }} | Data de Emissão: {{ date('d/m/Y H:i') }}
    </div>
  </div>
</body>

</html>