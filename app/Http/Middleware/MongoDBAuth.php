<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MongoDBAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        \Log::info('MongoDBAuth: Token recebido', ['token' => $token]);

        if (!$token) {
            \Log::warning('MongoDBAuth: Token não fornecido');
            return response()->json(['message' => 'Token não fornecido'], 401);
        }

        // Usar o método findToken do PersonalAccessToken que já está implementado corretamente
        $tokenModel = PersonalAccessToken::findToken($token);
        
        \Log::info('MongoDBAuth: Token encontrado', [
            'found' => $tokenModel ? 'sim' : 'não',
            'token_length' => strlen($token),
            'token_start' => substr($token, 0, 20)
        ]);

        if (!$tokenModel) {
            \Log::warning('MongoDBAuth: Token inválido', [
                'token_length' => strlen($token),
                'token_start' => substr($token, 0, 20)
            ]);
            return response()->json(['message' => 'Token inválido'], 401);
        }

        // Converter string para ObjectId se necessário
        $userId = $tokenModel->tokenable_id;
        if (is_string($userId) && !preg_match('/^[0-9a-fA-F]{24}$/', $userId)) {
            // Se não é um ObjectId válido, tentar buscar como string
            $user = User::where('_id', $userId)->first();
        } else {
            $user = User::find($userId);
        }
        
        \Log::info('MongoDBAuth: Usuário encontrado', [
            'found' => $user ? 'sim' : 'não',
            'user_id' => $tokenModel->tokenable_id,
            'user_name' => $user ? $user->fullName : 'N/A'
        ]);

        if (!$user) {
            \Log::warning('MongoDBAuth: Usuário não encontrado', [
                'tokenable_id' => $tokenModel->tokenable_id
            ]);
            return response()->json(['message' => 'Usuário não encontrado'], 401);
        }

        // Configurar o usuário no request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Configurar o usuário no Auth facade
        Auth::setUser($user);

        \Log::info('MongoDBAuth: Autenticação bem-sucedida', [
            'user_id' => $user->_id,
            'user_name' => $user->fullName,
            'route' => $request->route()->getName() ?? $request->path()
        ]);
        return $next($request);
    }
} 