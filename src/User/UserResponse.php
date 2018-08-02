<?php namespace VCA\Sdk\User;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use VCA\Sdk\ResponseObject;

/**
 * Class UserResponse
 * @package VCA\Sdk\User
 */
class UserResponse extends ResponseObject
{
    /**
     * Update user.
     *
     * @param array $values
     * @return bool
     */
    public function update(array $values)
    {
        $data = Arr::except($values, ['last_login', 'last_access']);

        $ret = $this->client->responseJson($this->client->request('put', $this->client->uri('users', [$this->id]), [
            'json' => $data,
        ]));

        if ($ret['success']) {
            $this->data = array_merge([], $this->data, $data);
        }

        return $ret['success'];
    }

    /**
     * Set new status
     * @param bool $value
     * @return bool
     */
    public function setStatus($value)
    {
        return $this->update([
            'status' => $value
        ]);
    }

    /**
     * Alias to change status to actived.
     *
     * @return bool
     */
    public function active()
    {
        return $this->setStatus('actived');
    }

    /**
     * Alias to change status to blocked.
     *
     * @return bool
     */
    public function block()
    {
        return $this->setStatus('blocked');
    }

    /**
     * Gerar nova api key.
     *
     * @return bool|mixed
     */
    public function generateApiKey()
    {
        $new_key = str_replace('.', '', uniqid('', true));

        if ($this->update(['api_token' => $new_key])) {
            return $new_key;
        }

        return false;
    }
}