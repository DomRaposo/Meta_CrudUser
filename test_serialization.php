<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\UserService;
use App\Repositories\UserRepository;

try {
    $userRepository = new UserRepository();
    $userService = new UserService($userRepository);
    $users = $userService->getAllUsers();
    
    echo "Testando serialização JSON...\n";
    $json = json_encode($users);
    if ($json === false) {
        echo "Erro na serialização JSON: " . json_last_error_msg() . "\n";
    } else {
        echo "Serialização JSON bem-sucedida!\n";
        echo "Tamanho: " . strlen($json) . " bytes\n";
        echo "Dados: " . substr($json, 0, 200) . "...\n";
    }
    
    echo "\nTestando serialização do Laravel...\n";
    $response = response()->json($users);
    echo "Resposta criada com sucesso!\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 