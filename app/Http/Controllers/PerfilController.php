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

        // Puxa o perfil associado ao usuário
        $profile = Perfil::where('user_id', $user->id)->first();

        // Se não houver perfil, cria objeto vazio (para não quebrar a view)
        if (!$profile) {
            $profile = new Perfil();
        }

        return view('Admin.perfil', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'telefone' => 'nullable|string|max:20',
            'bi' => 'nullable|string|max:50',
            'data_nascimento' => 'nullable|date',
            'genero' => 'nullable|in:Masculino,Feminino,Outro',
            'endereco' => 'nullable|string|max:255',
            'descricao' => 'nullable|string|max:600',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

         $profile = $user->profile ?? new Perfil(['user_id' => $user->id]);

    if ($request->hasFile('foto')) {
        // Define o caminho público
        $destinationPath = public_path('assets/img/avatars');

        // Cria pasta se não existir
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file = $request->file('foto');

        // Gera nome único
        $fileName = time().'_'.$file->getClientOriginalName();

        // Move o arquivo
        $file->move($destinationPath, $fileName);

        // Salva apenas o caminho relativo no banco
        $profile->foto = 'assets/img/avatars/'.$fileName;
    }
        // Atualiza os campos restantes
        $profile->telefone = $request->telefone;
        $profile->bi = $request->bi;
        $profile->data_nascimento = $request->data_nascimento;
        $profile->genero = $request->genero;
        $profile->endereco = $request->endereco;
        $profile->descricao = $request->descricao;

        $profile->save();

        return redirect()->back()->with('success', '✅ Perfil atualizado com sucesso!');
    }
}