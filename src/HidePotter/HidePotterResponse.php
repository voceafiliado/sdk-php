<?php namespace VCA\Sdk\HidePotter;

use Carbon\Carbon;
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
}