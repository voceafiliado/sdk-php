<?php namespace VCA\Sdk;

use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

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
     * @param VcaClient $client
     * @param array $config
     */
    public function __construct(VcaClient $client, $config = [])
    {
        $this->client = $client;

        $this->baseEndPoint = Arr::get($config, 'endpoint', $this->baseEndPoint);
    }

    /**
     * @param string $part
     * @return string
     */
    protected function uri($part = '')
    {
        $url = $this->baseEndPoint;

        if ($part != '') {
            $url .= '/' . $part;
        }

        return $url;
    }
}