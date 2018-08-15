<?php namespace VCA\Sdk;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use VCA\Sdk\Plan\PlanService;
use VCA\Sdk\User\UserService;
use VCA\Sdk\Auth\AuthService;
use VCA\Sdk\User\UserResponse;
use GuzzleHttp\Cookie\SetCookie;
use VCA\Sdk\Events\EventsService;
use VCA\Sdk\Status\StatusService;
use VCA\Sdk\Product\ProductService;
use Psr\Http\Message\ResponseInterface;
use VCA\Sdk\HidePotter\HidePotterService;
use VCA\Sdk\Subscription\SubscriptionService;

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
     * @var AuthService
     */
    protected $auth;

    /**
     * @var StatusService
     */
    protected $status;

    /**
     * @var UserResponse
     */
    protected $user;

    /**
     * @var ProductService
     */
    protected $product;

    /**
     * @var PlanService
     */
    protected $plan;

    /**
     * @var SubscriptionService
     */
    protected $subscription;

    /**
     * @var EventsService
     */
    protected $events;

    /**
     * @var HidePotterService
     */
    protected $hidepotter;

    /**
     * @var array
     */
    protected $endpoints = [
        'production' => 'http://api.voceafiliado.com/{version}',
        'sandbox' => 'http://api.sandbox.voceafiliado.com/{version}',
    ];

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;

        $config_http = Arr::get($config, 'http', []);

        $this->client = new Client($config_http);

        // Guardar access token original
        //$accessToken = $this->config('access_token', false);
        //if ($accessToken !== false) {
        //    $this->config(['access_token_original' => $accessToken]);
        //}
    }

    /**
     * @return AuthService
     */
    public function auth()
    {
        if (! is_null($this->auth)) {
            return $this->auth;
        }

        return $this->auth = new AuthService($this, [
            'endpoint' => $this->uri('auth'),
        ]);
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
     * @return UserService
     */
    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        return $this->user = new UserService($this, [
            'endpoint' => $this->uri('users'),
        ]);
    }

    /**
     * @return ProductService
     */
    public function product()
    {
        if (! is_null($this->product)) {
            return $this->product;
        }

        return $this->product = new ProductService($this, [
            'endpoint' => $this->uri('products'),
        ]);
    }

    /**
     * @return PlanService
     */
    public function plan()
    {
        if (! is_null($this->plan)) {
            return $this->plan;
        }

        return $this->plan = new PlanService($this, [
            'endpoint' => $this->uri('plans'),
        ]);
    }

    /**
     * @return SubscriptionService
     */
    public function subscription()
    {
        if (! is_null($this->subscription)) {
            return $this->subscription;
        }

        return $this->subscription = new SubscriptionService($this, [
            'endpoint' => $this->uri('subscriptions'),
        ]);
    }

    /**
     * @return EventsService
     */
    public function events()
    {
        if (! is_null($this->events)) {
            return $this->events;
        }

        return $this->events = new EventsService($this, [
            'endpoint' => $this->uri('events'),
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
    public function config($key, $default = null)
    {
        if (is_array($key) && is_null($default)) {
            $this->config = array_merge($this->config, $key);
            return true;
        }

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

        $url = sprintf($resource, $this->getEndPoint(), $part, $params);
        $url = str_replace('{version}', $this->getVersion(), $url);

        return $url;
    }

    /**
     * Retorna o endpoint pelo ambiente.
     *
     * @return string
     * @throws \Exception
     */
    protected function getEndPoint()
    {
        // Verificar se foi informado o endpoitn explicito
        $url = $this->config('endpoint');
        if (! is_null($url)) {
            return $url;
        }

        $env = $this->config('environment', 'production');
        if (! array_key_exists($env, $this->endpoints)) {
            throw new \Exception("Environment api client invalid [$env]");
        }

        return $this->endpoints[$env];
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $options
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function requestAsync($method, $uri = '', array $options = [])
    {
        $this->prepareOptions($method, $options);

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
        $this->prepareOptions($method, $options);

        $response = $this->client->request($method, $uri, $options);

        $this->testResponseError($response);

        return $response;
    }

    /**
     * @param $options
     */
    protected function prepareOptions($method, &$options)
    {
        // Send XDebug
        $xdebug = $this->config('xdebug', false);
        if ($xdebug !== false) {
            if (! isset($options['query'])) {
                $options['query'] = [];
            }
            $options['query']['XDEBUG_SESSION_START'] = $xdebug;
        }

        // AccessToken
        $accessToken = $this->config('access_token', false);
        if ($accessToken !== false) {
            if (! isset($options['query'])) {
                $options['query'] = [];
            }
            $options['query']['access_token'] = $accessToken;
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

        // Verificar se tem erros de atributos
        if (isset($json->error->errors)) {
            $info = '';
            foreach ((array) $json->error->errors as $attr => $msgs) {
                $info .= " - $attr:\r\n";
                foreach ($msgs as $msg) {
                    $info .= "   - $msg\r\n";
                }
            }

            $message = sprintf("%s\r\n%s", $message, $info);
        }

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