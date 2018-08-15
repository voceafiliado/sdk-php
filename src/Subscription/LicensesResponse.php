<?php namespace VCA\Sdk\Subscription;

use Illuminate\Support\Arr;

class LicensesResponse
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = (array) $data;
    }

    /**
     * Return if license exists.
     *
     * @param $license
     * @return mixed
     */
    public function has($license)
    {
        return Arr::has($this->data, $license);
    }

    /**
     * Return count total license.
     *
     * @param $license
     * @return mixed
     */
    public function total($license)
    {
        return Arr::get($this->data, sprintf('%s.total', $license), 0);
    }

    /**
     * Return count used license.
     *
     * @param $license
     * @return mixed
     */
    public function used($license)
    {
        return Arr::get($this->data, sprintf('%s.used', $license), 0);
    }

    /**
     * Return count available license.
     *
     * @param $license
     * @return mixed
     */
    public function available($license)
    {
        return Arr::get($this->data, sprintf('%s.available', $license), 0);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }
}