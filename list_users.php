<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

try {
    echo "Listando todos os usuários:\n";
    echo "========================\n";
    
    $users = User::all();
    
    foreach ($users as $user) {
        echo "ID: " . $user->_id . "\n";
        echo "Nome: " . $user->fullName . "\n";
        echo "Email: " . $user->email . "\n";
        echo "Idade: " . $user->age . "\n";
        echo "Role: " . $user->role . "\n";
        
        // Verificar se created_at existe e não é um array
        if (isset($user->created_at) && !is_array($user->created_at)) {
            echo "Criado em: " . $user->created_at . "\n";
        }
        
        echo "---\n";
    }
    
    echo "Total de usuários: " . $users->count() . "\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
} 