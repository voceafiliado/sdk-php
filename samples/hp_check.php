<?php

require __DIR__ . '/../vendor/autoload.php';

// Carregar config
$config = require __DIR__ . '/config.php';

// Carregar VCA Client
$vca = new \VCA\Sdk\VcaClient($config);

try {
    // Carregar informacoes do HidePotter
    $r = $vca->hidepotter()->check('857645836548634', $_SERVER, 'script-v1.0');

    // Carregar cache da pagina
    $html = $r->getPageContent();

    if ($r->isSalesFunnel()) {
        echo "go sales funel";
    } else {
        echo "go safe page";
    }

} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
