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
    protected function uri($part = '', $params = [])
    {
        $params = implode('/', $params);
        $params = ($params == '') ? '' : sprintf('/%s', $params);

        $resource = is_null($part) ? '%s%s' : '%s/%s%s';

        $url = sprintf($resource, $this->baseEndPoint, $part, $params);

        return $url;
    }
}