<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        \Log::info('Tentando login com:', $credentials);
        
        $user = $this->userRepository->findByEmail($credentials['email']);
        \Log::info('Usuário encontrado:', ['found' => $user ? 'sim' : 'não']);

        if (!$user) {
            \Log::warning('Login falhou: usuário não encontrado', ['email' => $credentials['email']]);
            return response()->json(['message' => 'Email ou senha incorretos'], 401);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            \Log::warning('Login falhou: senha incorreta', ['email' => $credentials['email']]);
            return response()->json(['message' => 'Email ou senha incorretos'], 401);
        }

        \Log::info('Senha válida, criando token...');

        // Criar token simples
        $token = $this->createPersonalAccessToken($user);
        \Log::info('Token criado com sucesso', ['token_length' => strlen($token)]);

        $response = [
            'message' => 'Login realizado com sucesso',
            'user' => $user,
            'token' => $token,
        ];

        \Log::info('Login realizado com sucesso', [
            'user_id' => $user->_id,
            'user_name' => $user->fullName
        ]);

        return response()->json($response);
    }

    private function createPersonalAccessToken($user)
    {
        $token = new PersonalAccessToken();
        $token->name = 'auth-token';
        $token->token = hash('sha256', $plainTextToken = Str::random(40));
        $token->abilities = ['*'];
        $token->tokenable_type = get_class($user);
        // Garantir que o tokenable_id seja armazenado como string para compatibilidade
        $token->tokenable_id = (string) $user->_id;
        $token->save();

        // Retornar token no formato ID|TOKEN como o Laravel Sanctum espera
        return $token->_id . '|' . $plainTextToken;
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $this->revokeToken($token);
        }
        
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    private function revokeToken($token)
    {
        $tokenModel = PersonalAccessToken::findToken($token);
        if ($tokenModel) {
            $tokenModel->delete();
        }
    }
}
