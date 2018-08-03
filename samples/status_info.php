<?php

require __DIR__ . '/../vendor/autoload.php';

// Carregar config
$config = require __DIR__ . '/config.php';

// Carregar VCA Client
$vca = new \VCA\Sdk\VcaClient($config);

try {
    // Carregar version
    $info = $vca->status()->version();

    // Status services
    $list_services = $vca->status()->services();
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
