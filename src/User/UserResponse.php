<?php namespace VCA\Sdk\User;

use Illuminate\Support\Arr;
use VCA\Sdk\ResponseObject;
use Nano7\Foundation\Support\Carbon;

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