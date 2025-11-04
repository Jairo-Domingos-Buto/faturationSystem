<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Factura FT FA.2025/6</title>
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

    /* Header */
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

    /* Invoice title */
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

    /* Meta info */
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
    }

    .meta-label {
      color: #555;
    }

    .meta-value {
      font-weight: 600;
    }

    /* Table */
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

    /* Summary */
    .summary-section {
      margin-top: 10mm;
      border-top: 1px solid #e0e0e0;
      padding-top: 5mm;
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
      width: 50%;
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
      width: 40%;
      border-radius: 6px;
      padding: 15px;
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

    /* Print */
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

    <div class="header">
      <div>
        <div class="logo-text">Ne<span class="x">X</span>corp</div>
        <div class="company-info">
          <div class="company-name">NEXCORP - COMÉRCIO E PRESTAÇÃO DE SERVIÇOS, LDA</div>
          <div>Contribuinte N.º: 5001925573</div>
          <div>Bairro Luanda Sul, Rua Embondeiro 50</div>
          <div>Luanda | Telef. 244 383732676</div>
          <div>apoio.nexcorp@gmail.com</div>
        </div>
      </div>

      <div class="client-section">
        <div class="client-label">Exmo.(s) Sr.(s)</div>
        <div class="client-name">TECNO EXCELÊNCIA (SU), LDA</div>
        <div>Viana, Edifício Viana Shopping, 1º Andar</div>
      </div>
    </div>

    <div class="invoice-title">
      <h1>Factura FT FA.2025/6</h1>
      <div class="original">Original</div>
    </div>

    <div class="invoice-meta-grid">
      <div>
        <div class="meta-item"><span class="meta-label">Moeda</span><span class="meta-value">AKZ</span></div>
        <div class="meta-item"><span class="meta-label">Vencimento</span><span class="meta-value">2025-05-07</span>
        </div>
      </div>
      <div>
        <div class="meta-item"><span class="meta-label">Data</span><span class="meta-value">2025-05-07</span></div>
        <div class="meta-item"><span class="meta-label">Condição Pagamento</span><span class="meta-value">Pronto
            Pagamento</span></div>
      </div>
    </div>

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
        <tr>
          <td>001</td>
          <td>Computador - Core i7, 16gb Ram<br><span class="item-code">(90)</span></td>
          <td class="text-right">6,00</td>
          <td>UN</td>
          <td class="text-right">812.522,00</td>
          <td class="text-right">0,00</td>
          <td class="text-right">0,00</td>
          <td class="text-right">4.875.132,00</td>
        </tr>
        <tr>
          <td>005</td>
          <td>Controles de jogos (joysticks)<br><span class="item-code">(90)</span></td>
          <td class="text-right">2,00</td>
          <td>UN</td>
          <td class="text-right">128.740,00</td>
          <td class="text-right">0,00</td>
          <td class="text-right">0,00</td>
          <td class="text-right">257.480,00</td>
        </tr>
        <tr>
          <td>006</td>
          <td>Smart TV 55 Polegadas<br><span class="item-code">(90)</span></td>
          <td class="text-right">1,00</td>
          <td>UN</td>
          <td class="text-right">223.810,00</td>
          <td class="text-right">0,00</td>
          <td class="text-right">0,00</td>
          <td class="text-right">223.810,00</td>
        </tr>
      </tbody>
    </table>

    <div class="summary-section">
      <div class="summary-grid">
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
                <td>IVA (0,00)</td>
                <td class="text-right">10.066.905,00</td>
                <td class="text-right">0,00</td>
                <td>(90) IVA - Regime de Exclusão</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="totals-box">
          <div class="total-line"><span>Mercadoria/Serviços</span><span>10.066.905,00</span></div>
          <div class="total-line"><span>IVA</span><span>0,00</span></div>
          <div class="total-line"><span>Descontos Comerciais</span><span>0,00</span></div>
          <div class="total-line grand-total"><span>Total (AKZ)</span><span>10.066.905,00</span></div>
        </div>
      </div>
    </div>

    <div class="bank-info">
      <strong>BANCO SOL</strong><br>
      <strong>IBAN:</strong> AO06 0044 0000.2328.1391.1010.3<br>
      <strong>Nº Conta:</strong> 22328139110001
    </div>

    <div class="footer">
      DwuV - Processado por programa validado n.º 41/AGT/2019 |
      Os bens e/ou serviços foram colocados à disposição na data 2025-05-07 / © PRIMAVERA BSS
    </div>
  </div>
</body>

</html>