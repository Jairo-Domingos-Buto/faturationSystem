<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class CadastraController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscar o perfil relacionado (se existir)
        $profile = $user->profile ?? new Perfil();

        return view('admin.cadastrar', compact('user', 'profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'typeUser'  => 'required|in:admin,atendente,balconista'
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'typeUser'  => $request->typeUser
        ]);

        return redirect()->back()->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function list()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);

        return view('Admin.lista', compact('users'));
    }
}