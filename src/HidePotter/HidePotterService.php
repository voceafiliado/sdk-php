<?php namespace VCA\Sdk\HidePotter;

use VCA\Sdk\Service;
use VCA\Sdk\Collection;

class HidePotterService extends Service
{
    /**
     * Create new hotlink.
     *
     * @param string $description
     * @param string $urlSale
     * @param string $urlSafe
     * @param null|string $fbPixel
     * @param string $status
     * @return HidePotterResponse
     */
    public function create($description, $urlSale, $urlSafe, $fbPixel = null, $status = 'inactived')
    {
        $data = [];
        $data['description'] = $description;
        $data['url_sale_page']  = $urlSale;
        $data['url_safe_page']  = $urlSafe;
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
     * @return Collection
     */
    public function index()
    {
        $array = $this->client->responseJson($this->client->request('get', $this->uri()));

        return new Collection($array, function ($key, $value) {
            return new HidePotterResponse($this->client, $value);
        });
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

    /**
     * Get resumes of hidepotters from user.
     *
     * @return HidePotterResumesResponse
     */
    public function resumes()
    {
        $response = $this->client->responseJson($this->client->request('get', $this->uri('resumes')));

        return new HidePotterResumesResponse($this->client, $response);
    }
}