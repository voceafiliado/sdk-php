<?php namespace VCA\Sdk\Plan;

use VCA\Sdk\ResponseObject;
use Nano7\Foundation\Support\Carbon;

/**
 * Class PlanResponse
 * @package VCA\Sdk\Plan
 *
 * @property string $id
 * @property string $key
 * @property string $description
 * @property float $price
 * @property string $status
 * @property array $items
 * @property array $refs
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 */
class PlanResponse extends ResponseObject
{
    /**
     * Update product.
     *
     * @param array $values
     * @return bool
     */
    public function update(array $values)
    {
        $ret = $this->client->responseJson($this->client->request('put', $this->client->uri('products', [$this->id]), [
            'json' => $values,
        ]));

        if ($ret['success']) {
            $this->data = array_merge([], $this->data, $values);
        }

        return $ret['success'];
    }
}