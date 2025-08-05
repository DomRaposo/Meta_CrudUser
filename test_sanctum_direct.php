<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Laravel\Sanctum\Sanctum;

try {
    echo "Testando Sanctum diretamente com MongoDB...\n";
    
    // Configurar o Sanctum para usar nosso modelo
    Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);
    
    // Buscar usuário
    $user = User::where('email', 'teste@raposo.com')->first();
    
    if ($user) {
        echo "Usuário encontrado: " . $user->fullName . "\n";
        
        // Criar token diretamente
        $token = $user->createToken('test-token');
        
        echo "Token criado com sucesso!\n";
        echo "Token: " . $token->plainTextToken . "\n";
        
    } else {
        echo "Usuário não encontrado\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 