<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Http\Responses\UserResponse;
use Illuminate\Http\JsonResponse;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retorna resposta HTTP para listar todos os usuários
     */
    public function getAllUsersResponse(): JsonResponse
    {
        try {
            $users = $this->getAllUsers();
            return UserResponse::successList($users);
        } catch (\Exception $e) {
            return UserResponse::error('Erro ao listar usuários', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Retorna resposta HTTP para criar usuário
     */
    public function createUserResponse(array $data): JsonResponse
    {
        try {
            $result = $this->createUser($data);
            return UserResponse::successCreate($result);
        } catch (\Exception $e) {
            return UserResponse::validationError('Erro ao criar usuário: ' . $e->getMessage(), $data);
        }
    }

    /**
     * Retorna resposta HTTP para buscar usuário específico
     */
    public function getUserResponse(string $id): JsonResponse
    {
        try {
            $user = $this->getUser($id);
            if (!$user) {
                return UserResponse::notFound($id);
            }
            return UserResponse::successShow($user);
        } catch (\Exception $e) {
            return UserResponse::error('Erro ao buscar usuário', 500, ['user_id' => $id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Retorna resposta HTTP para atualizar usuário
     */
    public function updateUserResponse(string $id, array $data): JsonResponse
    {
        try {
            $user = $this->updateUser($id, $data);
            if (!$user) {
                return UserResponse::notFound($id);
            }
            return UserResponse::successUpdate($user);
        } catch (\Exception $e) {
            return UserResponse::validationError('Erro ao atualizar usuário: ' . $e->getMessage(), $data);
        }
    }

    /**
     * Retorna resposta HTTP para deletar usuário
     */
    public function deleteUserResponse(string $id): JsonResponse
    {
        try {
            $result = $this->deleteUser($id);
            if (!$result) {
                return UserResponse::notFound($id);
            }
            return UserResponse::successDelete($id);
        } catch (\Exception $e) {
            return UserResponse::error('Erro ao deletar usuário', 500, ['user_id' => $id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Lógica de negócio: Lista todos os usuários
     */
    public function getAllUsers(): array
    {
        $users = $this->userRepository->all();
        
        // Converter para array simples para evitar problemas de serialização
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->_id,
                'fullName' => $user->fullName,
                'age' => $user->age,
                'email' => $user->email,
                'role' => $user->role,
            ];
        }
        
        return $usersArray;
    }

    /**
     * Lógica de negócio: Busca usuário específico
     */
    public function getUser(string $id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Lógica de negócio: Cria usuário
     */
    public function createUser(array $data): array
    {
        // Prepara os dados - força todo usuário a ser admin
        $data['role'] = 'isAdmin';
        
        // Cria o usuário
        $user = $this->userRepository->create($data);
        
        // Retorna a resposta completa
        return [
            'message' => 'Usuário criado com sucesso',
            'user' => $user
        ];
    }
    
    /**
     * Lógica de negócio: Atualiza usuário
     */
    public function updateUser(string $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }
    
    /**
     * Lógica de negócio: Deleta usuário
     */
    public function deleteUser(string $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
