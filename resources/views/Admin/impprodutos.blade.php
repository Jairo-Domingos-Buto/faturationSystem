<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Lista Geral de Produtos</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    h1 {
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f0f0f0;
    }
    </style>
</head>

<body>
    <h1>Lista Geral de Produtos</h1>

    @if(empty($produtos))
    <p>Nenhum produto encontrado.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Estoque</th>
                <th>Preço de Compra</th>
                <th>Preço de Venda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produtos as $index => $produto)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $produto['descricao'] ?? '—' }}</td>
                <td>{{ $produto['categoria']['nome'] ?? '—' }}</td>
                <td>{{ $produto['fornecedor']['nome'] ?? '—' }}</td>
                <td>{{ $produto['estoque'] ?? 0 }}</td>
                <td>{{ number_format($produto['preco_compra'] ?? 0, 2, ',', '.') }} Kz</td>
                <td>{{ number_format($produto['preco_venda'] ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>

</html>