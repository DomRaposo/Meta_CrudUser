<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

try {
    echo "Testando MongoDB...\n";
    
    $user = new User();
    $user->fullName = 'Teste Laravel';
    $user->email = 'laravel@test.com';
    $user->age = 30;
    $user->role = 'isAdmin';
    $user->save();
    
    echo "Usuário criado com ID: " . $user->_id . "\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
} 