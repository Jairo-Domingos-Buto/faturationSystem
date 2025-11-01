<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  tELA DE USUARIOS COMUNS
{{ auth()->user()->typeUser}}
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="text-red-600">Sair</button>
  </form>
</body>
</html>