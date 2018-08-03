<?php

require __DIR__ . '/../vendor/autoload.php';

// Carregar config
$config = require __DIR__ . '/config.php';

// Carregar VCA Client
$vca = new \VCA\Sdk\VcaClient($config);

try {
    // Registrar usuario
    $r = $vca->user()->create('Nome', 'email@email.com', 'senha123');

    // Ativar novo usuario
    $r->active();
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
