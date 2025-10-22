<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Relatório Geral de Produtos</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 40px;
        background-color: #f8f9fa;
        color: #333;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #4b6ef5;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .empresa-info {
        text-align: right;
    }

    .empresa-info h2 {
        margin: 0;
        color: #2c3e50;
    }

    .empresa-info p {
        margin: 2px 0;
        font-size: 12px;
        color: #555;
    }

    h1 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .subtitulo {
        text-align: center;
        font-size: 12px;
        color: #555;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        background-color: #fff;
        box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 12px;
        text-align: center;
    }

    th {
        background-color: #4b6ef5;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .total-linha {
        font-weight: bold;
        color: #2c3e50;
    }

    .tabela-resumo {
        width: 300px;
        float: right;
        margin-top: 20px;
        border-collapse: collapse;
        background-color: #fff;
    }

    .tabela-resumo th,
    .tabela-resumo td {
        border: 1px solid #ccc;
        padding: 8px;
        font-size: 12px;
    }

    .tabela-resumo th {
        background-color: #4b6ef5;
        color: white;
        text-align: left;
    }

    footer {
        position: fixed;
        bottom: 10px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 10px;
        color: #777;
    }
    </style>
</head>

<body>
    <header>
        <div>
            <img src="{{ public_path('imagens/logo_empresa.png') }}" alt="Logotipo" class="logo">
        </div>
        <div class="empresa-info">
            <h2>MAINDSEAT</h2>
            <p>Sistema de Faturação e Gestão Comercial</p>
            <p>Luanda, Angola</p>
            <p>Emitido em {{ date('d/m/Y H:i') }}</p>
        </div>
    </header>

    <h1>Relatório Geral de Produtos</h1>
    <p class="subtitulo">Listagem completa de produtos disponíveis na loja</p>

    @if(empty($produtos))
    <p>Nenhum produto encontrado.</p>
    @else
    @php
    $totalGeral = 0;
    $totalQuantidade = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Estoque</th>
                <th>Preço de Compra (Kz)</th>
                <th>Preço de Venda (Kz)</th>
                <th>Preço Total (Kz)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produtos as $index => $produto)
            @php
            $estoque = $produto['estoque'] ?? 0;
            $precoVenda = $produto['preco_venda'] ?? 0;
            $totalProduto = $estoque * $precoVenda;
            $totalGeral += $totalProduto;
            $totalQuantidade += $estoque;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $produto['descricao'] ?? '—' }}</td>
                <td>{{ $produto['categoria']['nome'] ?? '—' }}</td>
                <td>{{ $produto['fornecedor']['nome'] ?? '—' }}</td>
                <td>{{ $estoque }}</td>
                <td>{{ number_format($produto['preco_compra'] ?? 0, 2, ',', '.') }}</td>
                <td>{{ number_format($precoVenda, 2, ',', '.') }}</td>
                <td class="total-linha">{{ number_format($totalProduto, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="tabela-resumo">
        <tr>
            <th>Total de Quantidades</th>
            <td>{{ $totalQuantidade }}</td>
        </tr>
        <tr>
            <th>Valor Total dos Produtos</th>
            <td>{{ number_format($totalGeral, 2, ',', '.') }} Kz</td>
        </tr>
    </table>
    @endif

    <footer>
        Relatório emitido automaticamente pelo Sistema Maindseat © {{ date('Y') }}
    </footer>
</body>

</html>