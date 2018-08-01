<?php namespace VCA\Sdk;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use GuzzleHttp\Cookie\SetCookie;
use VCA\Sdk\Status\StatusService;
use Psr\Http\Message\ResponseInterface;
use VCA\Sdk\HidePotter\HidePotterService;

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
    protected $versions = ['1'];

    /**
     * @var StatusService
     */
    protected $status;

    /**
     * @var HidePotterService
     */
    protected $hidepotter;

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
     * @return StatusService
     */
    public function status()
    {
        if (! is_null($this->status)) {
            return $this->status;
        }

        return $this->status = new StatusService($this, [
            'endpoint' => $this->uri(),
        ]);
    }

    /**
     * @return HidePotterService
     */
    public function hidepotter()
    {
        if (! is_null($this->hidepotter)) {
            return $this->hidepotter;
        }

        return $this->hidepotter = new HidePotterService($this, [
            'endpoint' => $this->uri('hidepotter'),
        ]);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * @param null $part
     * @param array $params
     * @return string
     */
    public function uri($part = null, $params = [])
    {
        $params = implode('/', $params);
        $params = ($params == '') ? '' : sprintf('/%s', $params);

        $resource = is_null($part) ? '%s%s' : '%s/%s%s';

        $url = sprintf($resource, $this->config('endpoint'), $part, $params);
        $url = str_replace('{version}', $this->getVersion(), $url);

        return $url;
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function requestAsync($method, $uri = '', array $options = [])
    {
        $this->prepareOptions($options);

        $response = $this->client->request($method, $uri, $options);

        $this->testResponseError($response);

        return $response;
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     * @return mixed
     */
    public function request($method, $uri = '', array $options = [])
    {
        $this->prepareOptions($options);

        $response = $this->client->request($method, $uri, $options);

        $this->testResponseError($response);

        return $response;
    }

    /**
     * @param $options
     */
    protected function prepareOptions(&$options)
    {
        // Send XDebug
        $xdebug = $this->config('xdebug', false);
        if ($xdebug !== false) {
            // Verificar se deve enviar como query
            if (isset($options['form_params'])) {
                $options['form_params']['XDEBUG_SESSION_START'] = $xdebug;
            } else{
                if (! isset($options['query'])) {
                    $options['query'] = [];
                }
                $options['query']['XDEBUG_SESSION_START'] = $xdebug;
            }
        }
    }

    /**
     * @param ResponseInterface $response
     * @return null|array
     */
    public function responseJson(ResponseInterface $response)
    {
        $json = json_decode($response->getBody(), true);
        if (is_null($json)) {
            $message = trim($response->getBody());
            throw new \Exception($message);
        }

        return $json;
    }

    /**
     * Test if error.
     *
     * @param ResponseInterface $response
     * @return bool
     * @throws \Exception
     */
    protected function testResponseError(ResponseInterface $response)
    {
        // Verificar error http
        if (! $response->getStatusCode() == 200) {
            throw new \Exception("Error response: " . $response->getStatusCode());
        }

        // Verificar error via json
        $json = json_decode($response->getBody());
        if (is_null($json)) {
            return true;
        }

        if (! isset($json->error)) {
            return true;
        }

        $message = isset($json->error->message) ? $json->error->message : '???';
        $code = isset($json->error->code) ? $json->error->code : 0;

        throw new \Exception($message, $code);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getVersion()
    {
        $version = $this->config('version', 'latest');
        $version = ($version == 'latest') ? $this->getLastVersion() : $version;

        if (! in_array($version, $this->versions)) {
            throw new \Exception(sprintf("Invalid api version %s (%s)", $version));
        }

        return $version;
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
}