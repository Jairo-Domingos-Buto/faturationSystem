<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Autenticação"},
     *     summary="Cadastrar novo usuário",
     *     description="Permite o cadastro de um novo usuário no sistema, independentemente do nível de acesso.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome_completo", "nome_usuario", "email", "password"},
     *             @OA\Property(property="nome_completo", type="string", example="Inocêncio Bumba"),
     *             @OA\Property(property="nome_usuario", type="string", example="inocencio"),
     *             @OA\Property(property="telefone", type="string", example="923000000"),
     *             @OA\Property(property="funcao", type="string", example="Gestor de Faturação"),
     *             @OA\Property(property="email", type="string", example="inocencio@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="nivel_acesso", type="string", enum={"admin","gestor","usuario"}, example="usuario")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuário cadastrado com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nome_usuario'  => 'required|string|max:255|unique:usuarios',
            'telefone'      => 'nullable|string|max:20',
            'funcao'        => 'nullable|string|max:255',
            'email'         => 'required|email|unique:usuarios',
            'password'      => 'required|string|min:6',
            'nivel_acesso'  => 'nullable|in:admin,gestor,usuario',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autenticação"},
     *     summary="Login de usuário",
     *     description="Permite que o usuário entre no sistema e receba um token de autenticação.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="inocencio@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login efetuado com sucesso"),
     *     @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login efetuado com sucesso!',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/usuarios",
     *     tags={"Usuários"},
     *     summary="Listar todos os usuários cadastrados",
     *     description="Retorna a lista de todos os usuários do sistema. Apenas acessível com autenticação (token válido).",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Lista de usuários retornada com sucesso"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function index()
    {
        $usuarios = User::all();

        return response()->json([
            'total' => $usuarios->count(),
            'usuarios' => $usuarios
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Autenticação"},
     *     summary="Logout do usuário",
     *     description="Finaliza a sessão do usuário autenticado (invalida o token atual).",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Logout realizado com sucesso"),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }
}
