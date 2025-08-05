<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserResponse
{
    /**
     * Resposta de sucesso para listagem de usuários
     */
    public static function successList(array $users): JsonResponse
    {
        Log::info('UserResponse: Usuários listados com sucesso', ['count' => count($users)]);
        return response()->json($users);
    }

    /**
     * Resposta de sucesso para criação de usuário
     */
    public static function successCreate(array $result): JsonResponse
    {
        Log::info('UserResponse: Usuário criado com sucesso', ['user_id' => $result['user']->_id ?? null]);
        return response()->json($result, 201);
    }

    /**
     * Resposta de sucesso para busca de usuário
     */
    public static function successShow($user): JsonResponse
    {
        Log::info('UserResponse: Usuário encontrado', ['user_id' => $user->_id ?? null]);
        return response()->json($user);
    }

    /**
     * Resposta de sucesso para atualização de usuário
     */
    public static function successUpdate($user): JsonResponse
    {
        Log::info('UserResponse: Usuário atualizado com sucesso', ['user_id' => $user->_id ?? null]);
        return response()->json($user);
    }

    /**
     * Resposta de sucesso para exclusão de usuário
     */
    public static function successDelete(string $id): JsonResponse
    {
        Log::info('UserResponse: Usuário deletado com sucesso', ['user_id' => $id]);
        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }

    /**
     * Resposta de erro genérica
     */
    public static function error(string $message, int $statusCode = 500, array $context = []): JsonResponse
    {
        Log::error("UserResponse: {$message}", $context);
        return response()->json(['error' => $message], $statusCode);
    }

    /**
     * Resposta de erro de validação
     */
    public static function validationError(string $message, array $data = []): JsonResponse
    {
        Log::error('UserResponse: Erro de validação', ['error' => $message, 'data' => $data]);
        return response()->json(['error' => $message], 400);
    }

    /**
     * Resposta de usuário não encontrado
     */
    public static function notFound(string $id): JsonResponse
    {
        Log::warning('UserResponse: Usuário não encontrado', ['user_id' => $id]);
        return response()->json(['error' => 'Usuário não encontrado'], 404);
    }

    /**
     * Resposta de erro interno do servidor
     */
    public static function serverError(string $message, array $context = []): JsonResponse
    {
        Log::error('UserResponse: Erro interno do servidor', ['error' => $message, 'context' => $context]);
        return response()->json(['error' => 'Erro interno do servidor'], 500);
    }
} 