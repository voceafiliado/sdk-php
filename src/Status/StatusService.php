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
        return new InfoResponse($this->responseJson($this->client->request('get', $this->getUri())));
    }

    /**
     * @return array
     */
    public function services()
    {
        $array = $this->responseJson($this->client->request('get', $this->getUri('status/services')));

        foreach ($array as $i => $item) {
            $array[$i] = new ResponseObject($item);
        }

        return $array;
    }
}