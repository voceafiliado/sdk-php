<?php namespace VCA\Sdk\Events;

use VCA\Sdk\Service;

class EventsService extends Service
{
    /**
     * Registrar evento do gateway.
     *
     * @param $gatewayType
     * @param $event
     * @return bool
     */
    public function gateway($gatewayType, $event)
    {
        $data = [
            'gateway_type' => $gatewayType,
            'event' => $event,
        ];

        return ($this->client->responseJson($this->client->request('post', $this->uri('gateway'), [
            'json' => $data,
        ])) == true);
    }
}