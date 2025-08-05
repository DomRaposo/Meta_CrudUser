<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Testando MongoDB diretamente...\n";
    
    // Conectar diretamente ao MongoDB
    $connection = DB::connection('mongodb');
    $collection = $connection->getMongoDB()->selectCollection('users');
    
    // Buscar todos os documentos
    $cursor = $collection->find();
    
    echo "Usuários encontrados:\n";
    echo "===================\n";
    
    foreach ($cursor as $document) {
        echo "ID: " . $document->_id . "\n";
        echo "Nome: " . $document->fullName . "\n";
        echo "Email: " . $document->email . "\n";
        echo "Idade: " . $document->age . "\n";
        echo "Role: " . $document->role . "\n";
        echo "---\n";
    }
    
    echo "Teste concluído com sucesso!\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
} 