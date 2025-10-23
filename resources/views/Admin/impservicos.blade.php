<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Lista Geral de Serviços</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #555;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    th {
        background-color: #6C6FFF;
        color: white;
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
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Relatório Geral de Serviços</title>
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
            <p>Emitido em {{ date('d/m/Y H:i') }}</p>
        </div>
    </header>

    <h1>Relatório Geral de Serviços</h1>
    <p class="subtitulo">Listagem completa de serviços disponíveis na empresa</p>

    @if(empty($servicos))
    <p>Nenhum serviço encontrado.</p>
    @else
    @php
    $totalServicos = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Valor Unitário (Kz)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($servicos as $index => $servico)
            @php
            $valor = $servico['valor'] ?? 0;
            $totalServicos += $valor;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $servico['descricao'] ?? '—' }}</td>
                <td>{{ number_format($valor, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="tabela-resumo">
        <tr>
            <th>Total de Serviços</th>
            <td>{{ count($servicos) }}</td>
        </tr>
        <tr>
            <th>Valor Total</th>
            <td>{{ number_format($totalServicos, 2, ',', '.') }} Kz</td>
        </tr>
    </table>
    @endif

    <footer>
        Relatório emitido automaticamente pelo Sistema Maindseat © {{ date('Y') }}
    </footer>
</body>

</html>