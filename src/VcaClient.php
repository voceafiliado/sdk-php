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
            'endpoint' => sprintf('%s/{version}', $this->config('endpoint')),
            'version' => $this->config('version', 'latest'),
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
            'endpoint' => sprintf('%s/{version}/hidepotter', $this->config('endpoint')),
            'version' => $this->config('version', 'latest'),
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
}