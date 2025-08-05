<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function all() { return User::all(); }
    public function find($id) { 
        // Tentar buscar como ObjectId primeiro, depois como string
        $user = User::find($id);
        if (!$user && is_string($id)) {
            $user = User::where('_id', $id)->first();
        }
        return $user;
    }
    public function create(array $data) {
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
    public function update($id, array $data) {
        // Tentar buscar como ObjectId primeiro, depois como string
        $user = User::find($id);
        if (!$user && is_string($id)) {
            $user = User::where('_id', $id)->first();
        }
        if (!$user) return null;
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        $user->update($data);
        return $user;
    }
    public function delete($id) {
        // Tentar buscar como ObjectId primeiro, depois como string
        $user = User::find($id);
        if (!$user && is_string($id)) {
            $user = User::where('_id', $id)->first();
        }
        return $user ? $user->delete() : false;
    }
    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }
}
