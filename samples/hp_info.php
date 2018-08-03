<?php

require __DIR__ . '/../vendor/autoload.php';

// Carregar config
$config = require __DIR__ . '/config.php';

// Carregar VCA Client
$vca = new \VCA\Sdk\VcaClient($config);

try {
    // Carregar informacoes do HidePotter
    $r = $vca->hidepotter()->find('857645836548634');

    echo "Descricao: $r->description \r\n";
    echo "User.Name: " . $r->user->name . "\r\n";

} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
