<?php namespace VCA\Sdk\Status;

use VCA\Sdk\ResponseObject;
use Nano7\Foundation\Support\Carbon;

/**
 * Class InfoResponse
 * @package VCA\Sdk\Status
 *
 * @property string $app
 * @property string $version
 * @property Carbon|null $date
 */
class StatusResponse extends ResponseObject
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