<?php namespace VCA\Sdk\HidePotter;

use VCA\Sdk\Service;

class HidePotterService extends Service
{
    /**
     * Create new hotlink.
     *
     * @param string $description
     * @param string $urlTarget
     * @param null|string $fbPixel
     * @param string $status
     * @return HidePotterResponse
     */
    public function create($description, $urlTarget, $fbPixel = null, $status = 'inactived')
    {
        $data = [];
        $data['description'] = $description;
        $data['url_target']  = $urlTarget;
        $data['status']      = $status;

        if (!is_null($fbPixel) && ($fbPixel !== false)) {
            $data['fb_pixel_id'] = $fbPixel;
        }

        $response = $this->client->responseJson($this->client->request('post', $this->uri(), [
            'json' => $data,
        ]));

        return new HidePotterResponse($this->client, $response);
    }

    /**
     * Return info to hotlink.
     *
     * @param string $hpid HidePotterId
     * @return HidePotterResponse|null
     */
    public function find($hpid)
    {
        return new HidePotterResponse($this->client, $this->client->request('get', $this->uri($hpid)));
    }

    /**
     * Check if server info is cloack.
     *
     * @param $hpid
     * @param array $server
     * @param string $scriptVersion
     * @param bool $debug
     * @return HidePotterStatusResponse
     */
    public function check($hpid, array $server, $scriptVersion, $debug = false)
    {
        $response = $this->client->responseJson($this->client->request('get', sprintf('%s/check', $this->uri($hpid)), [
            'query' => [
                'server' => json_encode($server),
                'vcadebug' => $debug ? 'true' : 'false',
                'vscript' => $scriptVersion,
            ],
        ]));

        return new HidePotterStatusResponse($this->client, $response);
    }
}