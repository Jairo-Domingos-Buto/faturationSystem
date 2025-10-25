<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fatura</title>
  <style>
    @page {
      margin: 1.5cm;
    }

    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 12px;
      color: #333;
      background: #fff;
    }

    h1,
    h2 {
      color: #222;
      margin-bottom: 8px;
    }

    h1 {
      text-align: center;
      text-transform: uppercase;
      border-bottom: 2px solid #444;
      padding-bottom: 5px;
      margin-bottom: 20px;
    }

    h2 {
      margin-top: 25px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 3px;
    }

    p {
      margin: 4px 0;
      line-height: 1.4;
    }

    .section {
      margin-bottom: 15px;
    }

    .info {
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
    }

    th,
    td {
      padding: 6px 8px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
      font-weight: bold;
    }

    .right {
      text-align: right;
    }

    .totais {
      margin-top: 20px;
      width: 60%;
      float: right;
      border: 1px solid #ccc;
    }

    .totais td {
      padding: 6px 10px;
    }

    .totais tr:nth-child(even) {
      background: #f9f9f9;
    }

    .totais tr:last-child {
      font-weight: bold;
      background: #e9e9e9;
    }

    .footer {
      clear: both;
      text-align: center;
      font-size: 11px;
      margin-top: 40px;
      color: #777;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }
  </style>
</head>

<body>
  <h1>Fatura</h1>

  <div class="info">
    <p><strong>Cliente:</strong> {{ $cliente['nome'] }}</p>
    <p><strong>Data:</strong> {{ $financeiro['data'] ?? date('d/m/Y') }}</p>
    <p><strong>Número da Fatura:</strong> {{ $financeiro['numero'] ?? 'N/D' }}</p>
  </div>

  <h2>Produtos</h2>
  <table>
    <thead>
      <tr>
        <th>Descrição</th>
        <th>Quantidade</th>
        <th>Preço Unitário</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($produtos as $produto)
      <tr>
        <td>{{ $produto['descricao'] }}</td>
        <td class="right">{{ $produto['quantidade'] }}</td>
        <td class="right">{{ number_format($produto['preco_venda'], 2, ',', '.') }}</td>
        <td class="right">{{ number_format($produto['quantidade'] * $produto['preco_venda'], 2, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table class="totais">
    <tr>
      <td>Subtotal</td>
      <td class="right">{{ number_format($financeiro['subtotal'], 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td>IVA</td>
      <td class="right">{{ number_format($financeiro['iva'], 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td>Desconto</td>
      <td class="right">{{ number_format($financeiro['desconto'], 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td>Total</td>
      <td class="right">{{ number_format($financeiro['total'], 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td>Total Recebido</td>
      <td class="right">{{ number_format($financeiro['total_recebido'], 2, ',', '.') }}</td>
    </tr>
    <tr>
      <td>Troco</td>
      <td class="right">{{ number_format($financeiro['troco'], 2, ',', '.') }}</td>
    </tr>
  </table>

  <div class="footer">
    Obrigado pela sua preferência!<br>
    Documento gerado automaticamente em {{ date('d/m/Y H:i') }}.
  </div>
</body>

</html>