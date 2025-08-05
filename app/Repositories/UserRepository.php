<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function all() { return User::all(); }
    public function find($id) { return User::find($id); }
    public function create(array $data) {
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
    public function update($id, array $data) {
        $user = User::find($id);
        if (!$user) return null;
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        $user->update($data);
        return $user;
    }
    public function delete($id) {
        $user = User::find($id);
        return $user ? $user->delete() : false;
    }
    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }
}
