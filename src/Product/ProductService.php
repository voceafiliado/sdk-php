<?php namespace VCA\Sdk\Product;

use VCA\Sdk\Service;
use VCA\Sdk\Collection;

class ProductService extends Service
{
    /**
     * Create new product.
     *
     * @param string $key
     * @param string $description
     * @return ProductResponse
     */
    public function create($key, $description)
    {
        $data = [];
        $data['key']         = $key;
        $data['description'] = $description;

        $response = $this->client->responseJson($this->client->request('post', $this->uri(), [
            'json' => $data,
        ]));

        return new ProductResponse($this->client, $response);
    }

    /**
     * @return Collection
     */
    public function index()
    {
        $array = $this->client->responseJson($this->client->request('get', $this->uri()));

        return new Collection($array, function ($key, $value) {
            return new ProductResponse($this->client, $value);
        });
    }

    /**
     * Find a product.
     *
     * @param string $pid Product Id
     * @return ProductResponse|null
     */
    public function find($pid)
    {
        return new ProductResponse($this->client, $this->client->request('get', $this->uri($pid)));
    }
}