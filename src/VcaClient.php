<?php namespace VCA\Sdk;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class VcaClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;

        $config_http = Arr::get($config, 'http', []);

        $this->client = new Client($config_http);
    }

    /**
     * @param $serviceName
     * @return Service
     * @throws \Exception
     */
    protected function getService($serviceName)
    {
        $serviceName = strtolower($serviceName);

        // Verificar se servico jah foi carregado
        if (array_key_exists($serviceName, $this->services)) {
            return $this->services[$serviceName];
        }

        // Carregar o servico
        $method = sprintf('getService%s', Str::studly($serviceName));
        if (! method_exists($this, $method)) {
            throw new \Exception("Service [$serviceName] not found");
        }

        return $this->services[$serviceName] = call_user_func_array([$this, $method], []);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getService($name);
    }
}