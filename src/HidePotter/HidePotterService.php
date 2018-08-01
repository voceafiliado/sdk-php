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

        $response = $this->client->request('post', $this->getUri(''), [
            'json' => $data,
        ]);

        return null;
    }

    /**
     * @param string $hpid HidePotterId
     * @return mixed|null|object
     */
    public function info($hpid)
    {
        return new HidePotterResponse($this->client, $this->client->request('get', $this->getUri($hpid)));
    }

    /**
     * @param $hpid
     * @param array $server
     * @param bool $debug
     * @return HidePotterStatusResponse
     */
    public function check($hpid, array $server, $debug = false)
    {
        $response = $this->client->responseJson($this->client->request('get', sprintf('%s/check', $this->getUri($hpid)), [
            'query' => [
                'server' => json_encode($server),
                'vcadebug' => $debug ? 'true' : 'false',
            ],
        ]));

        return new HidePotterStatusResponse($this->client, $response);
    }
}