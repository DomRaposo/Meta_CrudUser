<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Repositories\UserRepository;

try {
    $userRepository = new UserRepository();
    $users = $userRepository->all();
    echo "Usuários encontrados: " . count($users) . "\n";
    foreach ($users as $user) {
        echo "- " . $user->fullName . " (" . $user->email . ")\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 