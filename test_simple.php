<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Testando conexão MongoDB...\n";
    
    // Testa a conexão diretamente
    $connection = DB::connection('mongodb');
    echo "Conexão MongoDB: " . ($connection ? "OK" : "ERRO") . "\n";
    
    // Testa inserir um documento diretamente usando a API correta
    $collection = $connection->getMongoDB()->selectCollection('users');
    $result = $collection->insertOne([
        'fullName' => 'Teste Direto',
        'email' => 'direto@test.com',
        'age' => 35,
        'role' => 'isAdmin',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "Documento inserido com ID: " . $result->getInsertedId() . "\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
} 