<?php namespace VCA\Sdk\HidePotter;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use VCA\Sdk\ResponseObject;
use VCA\Sdk\User\UserResponse;

/**
 * Class HidePotterResponse
 * @package VCA\Sdk\HidePotter
 *
 * @property string $id
 * @property UserResponse $user
 * @property string $description
 * @property string $url_target
 * @property string $status
 * @property string $fb_pixel_id
 * @property int $resume_user
 * @property int $resume_cloack
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 */
class HidePotterResponse extends ResponseObject
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
     * Update hotlink.
     *
     * @param array $values
     * @return mixed
     */
    public function update(array $values)
    {
        $data = Arr::except($values, ['user_id', 'resume_user', 'resume_cloack']);

        $ret = $this->client->responseJson($this->client->request('put', $this->client->uri('hidepotter', [$this->id]), [
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
     * Alias to change status to inactived.
     *
     * @return bool
     */
    public function inactive()
    {
        return $this->setStatus('inactived');
    }
}