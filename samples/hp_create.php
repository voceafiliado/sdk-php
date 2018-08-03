<?php

require __DIR__ . '/../vendor/autoload.php';

// Carregar config
$config = require __DIR__ . '/config.php';

// Carregar VCA Client
$vca = new \VCA\Sdk\VcaClient($config);

try {
    // Registrar HidePotter
    $r = $vca->hidepotter()->create('Descricao', 'url de venda', 'url da safe', 'pixel');

    // Ativar
    $r->active();

} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
