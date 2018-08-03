<?php

return [
    /**
     * Tipo de ambiente para acessar a api.
     * Opcoes: "sandbox" ou "production"
     * Opcao padrao quando nao informaod eh a "production".
     */
    'environment' => 'sandbox',

    /**
     * Versao da api.
     * Quando informado a versao "latest" o sistema ira pegar a ultima versao disponivel.
     */
    'version'=> 'latest',
];