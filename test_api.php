<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\UserService;
use App\Repositories\UserRepository;

try {
    echo "Testando API sem autenticação...\n";
    
    $repository = new UserRepository();
    $service = new UserService($repository);
    
    $data = [
        'fullName' => 'Teste API',
        'email' => 'api@test.com',
        'age' => 40,
        'password' => '123456'
    ];
    
    $result = $service->createUser($data);
    echo "Resultado: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 