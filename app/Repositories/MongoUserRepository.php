<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MongoUserRepository
{
    private $filePath;
    
    public function __construct()
    {
        $this->filePath = storage_path('app/mongodb/users.json');
        $this->ensureDirectoryExists();
    }
    
    private function ensureDirectoryExists()
    {
        $directory = dirname($this->filePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }
    
    private function readData()
    {
        $content = file_get_contents($this->filePath);
        return json_decode($content, true) ?: [];
    }
    
    private function writeData($data)
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    public function all()
    {
        return $this->readData();
    }
    
    public function find($id)
    {
        $data = $this->readData();
        foreach ($data as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    
    public function create(array $data)
    {
        $users = $this->readData();
        
        // Gera um ID único
        $id = uniqid();
        
        // Hash da senha se existir
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user = array_merge($data, [
            'id' => $id,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ]);
        
        $users[] = $user;
        $this->writeData($users);
        
        return $user;
    }
    
    public function update($id, array $data)
    {
        $users = $this->readData();
        
        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {
                // Hash da senha se existir
                if (isset($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                }
                
                $users[$key] = array_merge($user, $data, [
                    'updated_at' => now()->toISOString(),
                ]);
                
                $this->writeData($users);
                return $users[$key];
            }
        }
        
        return null;
    }
    
    public function delete($id)
    {
        $users = $this->readData();
        
        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {
                unset($users[$key]);
                $this->writeData(array_values($users));
                return true;
            }
        }
        
        return false;
    }
    
    public function findByEmail(string $email)
    {
        $users = $this->readData();
        
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        
        return null;
    }
} 