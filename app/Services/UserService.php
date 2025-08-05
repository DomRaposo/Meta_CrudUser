<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
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

    public function getUser($id)
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data)
    {
        // Prepara os dados - força todo usuário a ser admin
        $data['role'] = 'isAdmin';
        
        // Cria o usuário
        $user = $this->userRepository->create($data);
        
        // Retorna a resposta completa
        return [
            'message' => 'Create with sucess',
            'user' => $user
        ];
    }
    
    public function updateUser($id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }
    
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
