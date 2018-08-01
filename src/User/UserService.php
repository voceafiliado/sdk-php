<?php namespace VCA\Sdk\User;

use VCA\Sdk\Service;

class UserService extends Service
{
    /**
     * @var array
     */
    protected $versions = ['1'];

    /**
     * Create new hotlink.
     *
     * @param string $name
     * @param string $email
     * @param $urlTarget
     * @param null $fbPixel
     * @param string $status
     * @return UserResponse
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
     * @return mixed|null|object
     */
    public function info($hpid)
    {
        return new HidePotterResponse($this->client, $this->client->request('get', $this->uri($hpid)));
    }

    /**
     * Check if server info is cloack.
     *
     * @param $hpid
     * @param array $server
     * @param bool $debug
     * @return HidePotterStatusResponse
     */
    public function check($hpid, array $server, $debug = false)
    {
        $response = $this->client->responseJson($this->client->request('get', sprintf('%s/check', $this->uri($hpid)), [
            'query' => [
                'server' => json_encode($server),
                'vcadebug' => $debug ? 'true' : 'false',
            ],
        ]));

        return new HidePotterStatusResponse($this->client, $response);
    }
}