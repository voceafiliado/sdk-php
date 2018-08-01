<?php namespace VCA\Sdk\Status;

use VCA\Sdk\Service;
use VCA\Sdk\ResponseObject;

class StatusService extends Service
{
    /**
     * @var array
     */
    protected $versions = ['1'];

    /**
     * @return null|InfoResponse
     */
    public function version()
    {
        return new InfoResponse($this->client, $this->client->request('get', $this->uri()));
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
}