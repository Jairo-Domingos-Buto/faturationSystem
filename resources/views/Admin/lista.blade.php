@extends('layout.main')

@section('content')

<div class="container mt-4">

    <h2>Lista de Usuários</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo de Usuário</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->typeUser == 'admin')
                    <span class="badge bg-danger">Administrador</span>
                    @elseif($user->typeUser == 'atendente')
                    <span class="badge bg-primary">Atendente</span>
                    @else
                    <span class="badge bg-success">Balconista</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Nenhum usuário cadastrado ainda.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $users->links() }}
    </div>


</div>


@endsection