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
     * @var string
     */
    protected $version = '';

    /**
     * @var array
     */
    protected $versions = [];

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
        if (! in_array($this->version, $this->versions)) {
            throw new \Exception(sprintf("Invalid service version %s (%s)", $this->version, get_called_class()));
        }
    }

    /**
     * @return string
     */
    protected function getLastVersion()
    {
        if (count($this->versions) == 0) {
            return '';
        }

        return Arr::last($this->versions);
    }

    /**
     * @return string
     */
    protected function getUriBase()
    {
        $url = $this->baseEndPoint;

        $url = str_replace('{version}', $this->version, $url);

        return $url;
    }

    /**
     * @param string $part
     * @return string
     */
    protected function getUri($part = '')
    {
        $url = $this->getUriBase();

        if ($part != '') {
            $url .= '/' . $part;
        }

        return $url;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed|null|object
     */
    protected function responseJson(ResponseInterface $response)
    {
        $json = json_decode($response->getBody());
        if (is_null($json)) {
            return null;
        }

        return $json;
    }
}