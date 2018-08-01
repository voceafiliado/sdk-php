<?php namespace VCA\Sdk;

use Illuminate\Support\Arr;

class ResponseObject
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = (array) $data;
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        Arr::set($this->data, $key, $value);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}