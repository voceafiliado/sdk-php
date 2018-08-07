<?php namespace VCA\Sdk\Status;

use VCA\Sdk\Service;

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
        return $this->client->responseJson($this->client->request('get', $this->uri('status/services')));
    }
}