<?php namespace VCA\Sdk\Status;

use VCA\Sdk\Service;
use VCA\Sdk\ResponseObject;
use VCA\Sdk\User\UserResponse;

class StatusService extends Service
{
    /**
     * @return null|StatusResponse
     */
    public function version()
    {
        return new StatusResponse($this->client, $this->client->request('get', $this->uri()));
    }

    /**
     * @return array
     */
    public function services()
    {
        $array = $this->client->request('get', $this->uri('status/services'));

        foreach ($array as $i => $item) {
            $array[$i] = new ResponseObject($this->client, $item);
        }

        return $array;
    }

    /**
     * User logged.
     *
     * @return UserResponse
     */
    public function me()
    {
        return new UserResponse($this->client, $this->client->request('get', $this->uri('me')));
    }
}