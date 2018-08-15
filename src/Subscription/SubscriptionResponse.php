<?php namespace VCA\Sdk\Subscription;

use Carbon\Carbon;
use VCA\Sdk\ResponseObject;
use VCA\Sdk\User\UserResponse;

/**
 * Class SubscriptionResponse
 * @package VCA\Sdk\Subscription
 *
 * @property UserResponse $user
 * @property string $id
 * @property string $key
 * @property string $description
 * @property string $source_type
 * @property float $price
 * @property string $status
 * @property string $transaction
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $purchase_date
 * @property Carbon|null $maturity_at
 * @property Carbon|null $available_at
 */
class SubscriptionResponse extends ResponseObject
{
    /**
     * Retorna o usuario
     *
     * @return null|UserResponse
     */
    protected function user($value)
    {
        return new UserResponse($this->client, [], $this->client->uri('users', [$value]));
    }

    /**
     * @return null|Carbon
     */
    protected function getPurchaseDateAttr($value)
    {
        if (! is_null($value)) {
            return Carbon::createFromFormat(Carbon::ISO8601, $value);
        }

        return $value;
    }

    /**
     * @return null|Carbon
     */
    protected function getMaturityAtAttr($value)
    {
        if (! is_null($value)) {
            return Carbon::createFromFormat(Carbon::ISO8601, $value);
        }

        return $value;
    }

    /**
     * @return null|Carbon
     */
    protected function getAvailableAtAttr($value)
    {
        if (! is_null($value)) {
            return Carbon::createFromFormat(Carbon::ISO8601, $value);
        }

        return $value;
    }
}