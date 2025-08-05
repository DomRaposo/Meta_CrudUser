<?php

require 'vendor/autoload.php';

try {
    $manager = new MongoDB\Driver\Manager('mongodb://127.0.0.1:27017');
    $database = 'to_do';
    $collection = 'personal_access_tokens';
    
    echo "Verificando collection: $collection\n";
    
    $cursor = $manager->executeQuery($database . '.' . $collection, new MongoDB\Driver\Query([]));
    $documents = iterator_to_array($cursor);
    
    echo "Documentos encontrados: " . count($documents) . "\n";
    
    if (count($documents) > 0) {
        echo "Primeiro documento:\n";
        print_r($documents[0]);
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
} 