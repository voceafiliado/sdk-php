<?php namespace VCA\Sdk\Subscription;

use VCA\Sdk\Service;
use VCA\Sdk\Collection;

class SubscriptionService extends Service
{
    /**
     * @return Collection
     */
    public function index()
    {
        $array = $this->client->responseJson($this->client->request('get', $this->uri()));

        return new Collection($array, function ($key, $value) {
            return new SubscriptionResponse($this->client, $value);
        });
    }

    /**
     * Find a subscription.
     *
     * @param string $sid Subscription Id
     * @return SubscriptionResponse|null
     */
    public function find($sid)
    {
        return new SubscriptionResponse($this->client, $this->client->request('get', $this->uri($sid)));
    }

    /**
     * Get count licenses.
     *
     * @param $licenses
     * @return LicensesResponse|null
     */
    public function licenses($licenses)
    {
        $licenses = is_array($licenses) ? implode(',', $licenses) : $licenses;

        $ret = $this->client->responseJson($this->client->request('get', $this->uri('licenses'), [
            'query' => [
                'product_key' => $licenses,
            ],
        ]));

        return new LicensesResponse($ret);
    }
}