<?php namespace VCA\Sdk;

use Illuminate\Support\Arr;

abstract class Service
{
    /**
     * @var VcaClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseEndPoint = '';

    /**
     * @var string
     */
    protected $version = '';

    /**
     * @param VcaClient $client
     * @param array $config
     */
    public function __construct(VcaClient $client, $config = [])
    {
        $this->client = $client;

        $this->baseEndPoint = Arr::get($config, 'endpoint', $this->baseEndPoint);
        $this->version = Arr::get($config, 'version', $this->version);

        $this->version = ($this->version == 'latest') ? $this->getLastVersion() : $this->version;
    }

    /**
     * @return string
     */
    protected abstract function getLastVersion();

    /**
     * @return string
     */
    protected function getUriBase()
    {
        $url = $this->baseEndPoint;

        // Verificar se deve adicionar a versao
        if (trim($this->version) != '') {
            $url .= '/'. $this->version;
        }

        return $url;
    }
}