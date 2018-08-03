<?php

require __DIR__ . '/../vendor/autoload.php';

// Carregar config
$config = require __DIR__ . '/config.php';

// Carregar VCA Client
$vca = new \VCA\Sdk\VcaClient($config);

try {
    // Logar no sistema
    $vca->auth()->login('teste@teste.com', '123456');

    // Carregar usuario logado
    $user = $vca->auth()->me();

    echo "Id: $user->id \r\n";
    echo "E-mail: $user->email \r\n";
    echo "E-mail: $user->email \r\n";
    echo "Status: $user->status \r\n";
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
