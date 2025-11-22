<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Perfil;

class PerfilController extends Controller
{
    public function index()
    {
         $user = Auth::user();

    // Carrega o perfil existente, se houver
    $profile = $user->profile;

    return view('Admin.perfil', compact('user', 'profile'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'telefone' => 'nullable|string|max:20',
        'bi' => 'nullable|string|max:50',
        'data_nascimento' => 'nullable|date',
        'genero' => 'nullable|string|max:20',
        'endereco' => 'nullable|string|max:255',
        'descricao' => 'nullable|string',
        'foto' => 'nullable|image|max:2048',
    ]);

    // Se não existir perfil, cria e associa ao usuário
    $profile = $user->profile ?? new Perfil(['user_id' => $user->id]);

    if ($request->hasFile('foto')) {
        // Apaga foto antiga
        if ($profile->foto && Storage::disk('public')->exists($profile->foto)) {
            Storage::disk('public')->delete($profile->foto);
        }

        $path = $request->file('foto')->store('perfil', 'public');
        $profile->foto = $path;
    }

    $profile->telefone = $request->telefone;
    $profile->bi = $request->bi;
    $profile->data_nascimento = $request->data_nascimento;
    $profile->genero = $request->genero;
    $profile->endereco = $request->endereco;
    $profile->descricao = $request->descricao;

    $profile->save();

    return back()->with('success', 'Perfil atualizado com sucesso!');
}

}