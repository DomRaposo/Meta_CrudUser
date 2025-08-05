<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    echo "Testando autenticação simples com MongoDB...\n";
    
    // Buscar usuário diretamente
    $user = User::where('email', 'teste@raposo.com')->first();
    
    if ($user) {
        echo "Usuário encontrado: " . $user->fullName . "\n";
        echo "Email: " . $user->email . "\n";
        echo "Role: " . $user->role . "\n";
        
        // Verificar senha
        $password = '123456';
        if (Hash::check($password, $user->password)) {
            echo "Senha correta!\n";
            echo "Autenticação bem-sucedida!\n";
        } else {
            echo "Senha incorreta!\n";
        }
        
    } else {
        echo "Usuário não encontrado\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 