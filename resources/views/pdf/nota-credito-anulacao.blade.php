{{-- resources/views/pdf/nota-credito-anulacao.blade.php --}}

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nota de Crédito - Anulação - {{ $dados['numero_nota_credito'] }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 10pt;
      color: #333;
    }

    .container {
      padding: 20px;
    }

    .header {
      border-bottom: 3px solid #333;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }

    .header-top {
      display: table;
      width: 100%;
      margin-bottom: 10px;
    }

    .header-left {
      display: table-cell;
      width: 60%;
      vertical-align: top;
    }

    .header-right {
      display: table-cell;
      width: 40%;
      vertical-align: top;
      text-align: right;
    }

    .logo {
      max-height: 60px;
      margin-bottom: 10px;
    }

    .company-name {
      font-size: 14pt;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .company-info {
      font-size: 9pt;
      line-height: 1.4;
    }

    .doc-type {
      background: #333;
      color: white;
      padding: 10px;
      text-align: center;
      margin-bottom: 10px;
    }

    .doc-type h1 {
      font-size: 18pt;
      margin-bottom: 5px;
    }

    .doc-type p {
      font-size: 10pt;
    }

    .doc-number {
      font-size: 9pt;
      margin-top: 5px;
    }

    .section {
      margin-bottom: 15px;
    }

    .section-title {
      background: #f0f0f0;
      padding: 8px;
      font-weight: bold;
      margin-bottom: 8px;
      border-left: 4px solid #333;
    }

    .info-box {
      background: #f9f9f9;
      padding: 10px;
      border: 1px solid #ddd;
    }

    .info-box p {
      line-height: 1.6;
      margin-bottom: 3px;
    }

    .alert-box {
      background: #fff3cd;
      border: 2px solid #ff6b6b;
      padding: 10px;
      margin-bottom: 15px;
    }

    .alert-box h3 {
      color: #d32f2f;
      margin-bottom: 8px;
    }

    .alert-grid {
      display: table;
      width: 100%;
    }

    .alert-col {
      display: table-cell;
      width: 50%;
      padding: 5px;
    }

    .alert-full {
      display: block;
      padding: 5px;
      margin-top: 8px;
    }

    .alert-motivo {
      background: white;
      padding: 8px;
      border: 1px solid #ff9800;
      margin-top: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    thead {
      background: #f0f0f0;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      font-size: 9pt;
    }

    th {
      font-weight: bold;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .font-bold {
      font-weight: bold;
    }

    .stock-section {
      background: #e8f5e9;
      border: 2px solid #4caf50;
      padding: 10px;
      margin-bottom: 15px;
    }

    .stock-section h3 {
      color: #2e7d32;
      margin-bottom: 8px;
    }

    .totals {
      margin-top: 20px;
    }

    .totals-box {
      width: 50%;
      margin-left: auto;
    }

    .totals-row {
      display: table;
      width: 100%;
      margin-bottom: 8px;
    }

    .totals-label {
      display: table-cell;
      font-weight: bold;
    }

    .totals-value {
      display: table-cell;
      text-align: right;
    }

    .totals-final {
      border-top: 2px solid #333;
      padding-top: 10px;
      font-size: 12pt;
    }

    .totals-final .totals-value {
      color: #d32f2f;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      padding-top: 15px;
      border-top: 1px solid #ddd;
      font-size: 8pt;
      color: #666;
    }

    .footer p {
      margin-bottom: 3px;
    }
  </style>
</head>

<body>
  <div class="container">
    {{-- Cabeçalho --}}
    <div class="header">
      <div class="header-top">
        <div class="header-left">
          @if($dados['empresa']['logo'])
          <img src="{{ public_path('storage/' . $dados['empresa']['logo']) }}" alt="Logo" class="logo">
          @endif
          <div class="company-name">{{ $dados['empresa']['nome'] }}</div>
          <div class="company-info">
            <p><strong>NIF:</strong> {{ $dados['empresa']['nif'] }}</p>
            <p>{{ $dados['empresa']['endereco'] }}, {{ $dados['empresa']['cidade'] }}</p>
            <p><strong>Tel:</strong> {{ $dados['empresa']['telefone'] }} | <strong>Email:</strong> {{
              $dados['empresa']['email'] }}</p>
          </div>
        </div>
        <div class="header-right">
          <div class="doc-type">
            <h1>NOTA DE CRÉDITO</h1>
            <p>ANULAÇÃO DE {{ strtoupper($dados['tipo_documento']) }}</p>
          </div>
          <div class="doc-number">
            <p><strong>N.º:</strong> {{ $dados['numero_nota_credito'] }}</p>
            <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($dados['data_emissao_nota'])->format('d/m/Y') }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Dados do Cliente --}}
    <div class="section">
      <div class="section-title">CLIENTE</div>
      <div class="info-box">
        <p><strong>Nome:</strong> {{ $dados['cliente']['nome'] }}</p>
        <p><strong>NIF:</strong> {{ $dados['cliente']['nif'] }}</p>
        <p><strong>Endereço:</strong> {{ $dados['cliente']['endereco'] }}, {{ $dados['cliente']['cidade'] }}</p>
        @if($dados['cliente']['telefone'])
        <p><strong>Telefone:</strong> {{ $dados['cliente']['telefone'] }}</p>
        @endif
      </div>
    </div>

    {{-- Informações da Anulação --}}
    <div class="alert-box">
      <h3>⚠️ INFORMAÇÕES DA ANULAÇÃO</h3>
      <div class="alert-grid">
        <div class="alert-col">
          <p><strong>Documento Anulado:</strong></p>
          <p style="color: #d32f2f; font-size: 11pt; font-weight: bold;">{{ $dados['documento_anulado']['numero'] }}</p>
        </div>
        <div class="alert-col">
          <p><strong>Data Emissão Original:</strong></p>
          <p style="font-weight: bold;">{{ $dados['documento_anulado']['data_emissao'] }}</p>
        </div>
      </div>
      <div class="alert-grid">
        <div class="alert-col">
          <p><strong>Data da Anulação:</strong></p>
          <p style="font-weight: bold;">{{ $dados['anulacao']['data'] }}</p>
        </div>
        <div class="alert-col">
          <p><strong>Anulado por:</strong></p>
          <p style="font-weight: bold;">{{ $dados['anulacao']['usuario'] }}</p>
        </div>
      </div>
      <div class="alert-full">
        <p><strong>Motivo da Anulação:</strong></p>
        <div class="alert-motivo">{{ $dados['anulacao']['motivo'] }}</div>
      </div>
    </div>

    {{-- Produtos do Documento Anulado --}}
    <div class="section">
      <div class="section-title">PRODUTOS DO DOCUMENTO ANULADO</div>
      <table>
        <thead>
          <tr>
            <th>Código</th>
            <th>Descrição</th>
            <th class="text-center">Qtd</th>
            <th class="text-right">P. Unit.</th>
            <th class="text-right">Subtotal</th>
            <th class="text-right">IVA</th>
            <th class="text-right">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dados['documento_anulado']['produtos'] as $produto)
          <tr>
            <td>{{ $produto['codigo'] }}</td>
            <td>
              {{ $produto['descricao'] }}<br>
              <span style="font-size: 8pt; color: #666;">{{ $produto['categoria'] }}</span>
            </td>
            <td class="text-center">{{ $produto['quantidade'] }}</td>
            <td class="text-right">{{ number_format($produto['preco_unitario'], 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format($produto['subtotal'], 2, ',', '.') }}</td>
            <td class="text-right">
              {{ number_format($produto['valor_iva'], 2, ',', '.') }}<br>
              <span style="font-size: 8pt; color: #666;">({{ $produto['taxa_iva'] }}%)</span>
            </td>
            <td class="text-right font-bold">{{ number_format($produto['total'], 2, ',', '.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Resumo de Impostos --}}
    @if(!empty($dados['documento_anulado']['resumo_impostos']))
    <div class="section">
      <div class="section-title">RESUMO DE IMPOSTOS</div>
      <table>
        <thead>
          <tr>
            <th>Taxa</th>
            <th>Descrição</th>
            <th class="text-right">Incidência</th>
            <th class="text-right">Valor Imposto</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dados['documento_anulado']['resumo_impostos'] as $imposto)
          <tr>
            <td>{{ $imposto['taxa'] }}%</td>
            <td>{{ $imposto['descricao'] }}</td>
            <td class="text-right">{{ number_format($imposto['incidencia'], 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format($imposto['valor_imposto'], 2, ',', '.') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif

    {{-- Estoque Devolvido --}}
    <div class="stock-section">
      <h3>📦 ESTOQUE DEVOLVIDO À EMPRESA</h3>
      <table>
        <thead>
          <tr>
            <th>Produto</th>
            <th class="text-center">Quantidade</th>
            <th class="text-right">Valor Unit.</th>
            <th class="text-right">Valor Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dados['estoque_devolvido']['items'] as $item)
          <tr>
            <td>{{ $item['produto'] }}</td>
            <td class="text-center font-bold" style="color: #2e7d32;">+{{ $item['quantidade'] }}</td>
            <td class="text-right">{{ number_format($item['valor_unitario'], 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item['valor_total'], 2, ',', '.') }}</td>
          </tr>
          @endforeach
          <tr style="border-top: 2px solid #4caf50; font-weight: bold;">
            <td>TOTAL</td>
            <td class="text-center" style="color: #2e7d32;">{{ $dados['estoque_devolvido']['total_quantidade'] }}</td>
            <td></td>
            <td class="text-right">{{ number_format($dados['estoque_devolvido']['total_valor'], 2, ',', '.') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    {{-- Totais Devolvidos --}}
    <div class="totals">
      <div class="totals-box">
        <div class="totals-row">
          <div class="totals-label">Subtotal Devolvido:</div>
          <div class="totals-value">{{ number_format($dados['valores_devolvidos']['subtotal'], 2, ',', '.') }} KZ</div>
        </div>
        @if($dados['valores_devolvidos']['impostos'] > 0)
        <div class="totals-row">
          <div class="totals-label">IVA Devolvido:</div>
          <div class="totals-value">{{ number_format($dados['valores_devolvidos']['impostos'], 2, ',', '.') }} KZ</div>
        </div>
        @endif
        <div class="totals-row totals-final">
          <div class="totals-label">TOTAL DEVOLVIDO:</div>
          <div class="totals-value">{{ number_format($dados['valores_devolvidos']['total'], 2, ',', '.') }} KZ</div>
        </div>
      </div>
    </div>

    {{-- Rodapé --}}
    <div class="footer">
      <p><strong>Este documento anula o {{ strtoupper($dados['tipo_documento']) }} {{
          $dados['documento_anulado']['numero'] }}</strong></p>
      <p>Emitido em {{ \Carbon\Carbon::parse($dados['data_emissao_nota'])->format('d/m/Y') }}</p>
      <p style="margin-top: 5px;">Processado por {{ $dados['empresa']['nome'] }} - Sistema de Faturação</p>
    </div>
  </div>
</body>

</html>