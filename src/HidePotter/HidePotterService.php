<?php namespace VCA\Sdk\HidePotter;

use VCA\Sdk\Service;

class HidePotterService extends Service
{
    /**
     * @var array
     */
    protected $versions = ['1'];

    public function create($description, $urlTarget, $fbPixel = null)
    {
        $data = [];
        $data['description'] = $description;
        $data['url_target']  = $urlTarget;

        if (!is_null($fbPixel) && ($fbPixel !== false)) {
            $data['fb_pixel_id'] = $fbPixel;
        }

        $options = [
            'json' => $data,
        ];

        $response = $this->client->request('post', $this->getUri(''), $options);

        return null;
    }

    /**
     * @param string $hpid HidePotterId
     * @return mixed|null|object
     */
    public function info($hpid)
    {
        return $this->responseJson($this->client->request('get', $this->getUri($hpid)));
    }

    /**
     * @param $hpid
     * @param array $server
     * @param bool $debug
     * @return mixed|null|object
     */
    public function check($hpid, array $server, $debug = false)
    {
        $options = [
            'query' => [
                'server' => urlencode($server),
                'vcadebug' => $debug ? 'true' : 'false',
            ],
        ];

        $response = $this->responseJson($this->client->request('get', sprintf('%s/check', $this->getUri($hpid)), $options));

        return $response;
    }
}