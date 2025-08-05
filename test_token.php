<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PersonalAccessToken;

// Testar com o novo token
$newToken = '68910814e8591adba0022556|FN2N5M76pnAiBcwc78vRsRo0ikVfNMYN1ZwEm1cA';

echo "Testando novo token: $newToken\n";

$tokenModel = PersonalAccessToken::findToken($newToken);

if ($tokenModel) {
    echo "Token encontrado!\n";
    echo "ID: " . $tokenModel->_id . "\n";
    echo "Name: " . $tokenModel->name . "\n";
    echo "Tokenable ID: " . $tokenModel->tokenable_id . "\n";
} else {
    echo "Token não encontrado!\n";
    
    // Debug detalhado
    [$id, $token] = explode('|', $newToken, 2);
    echo "ID extraído: $id\n";
    echo "Token extraído: $token\n";
    
    $instance = PersonalAccessToken::find($id);
    echo "Instância encontrada: " . ($instance ? 'SIM' : 'NÃO') . "\n";
    
    if ($instance) {
        $expectedHash = hash('sha256', $token);
        echo "Hash esperado: " . substr($expectedHash, 0, 20) . "...\n";
        echo "Hash armazenado: " . substr($instance->token, 0, 20) . "...\n";
        echo "Hashes iguais: " . (hash_equals($instance->token, $expectedHash) ? 'SIM' : 'NÃO') . "\n";
    }
} 