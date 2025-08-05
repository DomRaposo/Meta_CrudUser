<?php

require_once 'vendor/autoload.php';

// Inicializar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\PersonalAccessToken;

try {
    echo "=== Limpeza de Tokens Órfãos ===\n";
    
    // Obter todos os usuários válidos
    $users = User::all();
    $validUserIds = $users->pluck('_id')->toArray();
    
    echo "Usuários válidos: " . count($validUserIds) . "\n";
    
    // Obter todos os tokens
    $tokens = PersonalAccessToken::all();
    echo "Total de tokens: " . $tokens->count() . "\n";
    
    $orphanedTokens = [];
    $validTokens = [];
    
    foreach ($tokens as $token) {
        if (in_array($token->tokenable_id, $validUserIds)) {
            $validTokens[] = $token;
        } else {
            $orphanedTokens[] = $token;
        }
    }
    
    echo "Tokens válidos: " . count($validTokens) . "\n";
    echo "Tokens órfãos: " . count($orphanedTokens) . "\n";
    
    // Deletar tokens órfãos
    if (count($orphanedTokens) > 0) {
        echo "\nDeletando tokens órfãos...\n";
        foreach ($orphanedTokens as $token) {
            echo "Deletando token ID: " . $token->_id . " (referencia usuário inexistente: " . $token->tokenable_id . ")\n";
            $token->delete();
        }
        echo "Tokens órfãos deletados com sucesso!\n";
    } else {
        echo "\nNenhum token órfão encontrado.\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 