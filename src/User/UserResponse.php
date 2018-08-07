<?php namespace VCA\Sdk\User;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use VCA\Sdk\ResponseObject;

/**
 * Class UserResponse
 * @package VCA\Sdk\User
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $language
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $last_login
 * @property Carbon|null $last_access
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
    protected function setStatus($value)
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
        $ret = $this->client->responseJson($this->client->request('get', $this->client->uri('users', [$this->id, 'active'])));

        if ($ret === true) {
            $this->data = array_merge([], $this->data, ['status' => 'actived']);
        }

        return $ret;
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

    /**
     * @return null|Carbon
     */
    protected function getLastLoginAttr($value)
    {
        if (! is_null($value)) {
            return Carbon::createFromFormat(Carbon::ISO8601, $value);
        }

        return $value;
    }

    /**
     * @return null|Carbon
     */
    protected function getLastAccessAttr($value)
    {
        if (! is_null($value)) {
            return Carbon::createFromFormat(Carbon::ISO8601, $value);
        }

        return $value;
    }
}