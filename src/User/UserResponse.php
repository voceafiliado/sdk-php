<?php namespace VCA\Sdk\User;

use Carbon\Carbon;
use VCA\Sdk\ResponseObject;

/**
 * Class UserResponse
 * @package VCA\Sdk\User
 */
class UserResponse extends ResponseObject
{
    /**
     * Retorna a data e hora do servidor.
     *
     * @return null|Carbon
     */
    protected function getDateAttr($value)
    {
        if (! is_null($value)) {
            return Carbon::createFromFormat(Carbon::ISO8601, $value);
        }

        return $value;
    }
}