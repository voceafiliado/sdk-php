<?php namespace VCA\Sdk\Plan;

use VCA\Sdk\Service;
use VCA\Sdk\Collection;

class PlanService extends Service
{
    /**
     * Create new plan.
     *
     * @param $description
     * @param $price
     * @param $items
     * @param $refs
     * @param string $status
     * @return PlanResponse
     */
    public function create($description, $price, $items, $refs, $status = 'actived')
    {
        $data = [];
        $data['description'] = $description;
        $data['price']       = $price;
        $data['items']       = $items;
        $data['refs']        = $refs;
        $data['status']      = $status;

        $response = $this->client->responseJson($this->client->request('post', $this->uri(), [
            'json' => $data,
        ]));

        return new PlanResponse($this->client, $response);
    }

    /**
     * @return Collection
     */
    public function index()
    {
        $array = $this->client->responseJson($this->client->request('get', $this->uri()));

        return new Collection($array, function ($key, $value) {
            return new PlanResponse($this->client, $value);
        });
    }

    /**
     * Find a plan.
     *
     * @param string $pid Plan id
     * @return PlanResponse|null
     */
    public function find($pid)
    {
        return new PlanResponse($this->client, $this->client->request('get', $this->uri($pid)));
    }
}