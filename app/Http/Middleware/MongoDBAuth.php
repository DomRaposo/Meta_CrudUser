<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\User;

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

        $user = User::find($tokenModel->tokenable_id);
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

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        \Log::info('MongoDBAuth: Autenticação bem-sucedida', [
            'user_id' => $user->_id,
            'user_name' => $user->fullName,
            'route' => $request->route()->getName() ?? $request->path()
        ]);
        return $next($request);
    }
} 