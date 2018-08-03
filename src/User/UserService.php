<?php namespace VCA\Sdk\User;

use VCA\Sdk\Service;

class UserService extends Service
{
    /**
     * Create new user.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param array $options
     * @return UserResponse
     */
    public function create($name, $email, $password, $options = [])
    {
        $data = [];
        $data['name']     = $name;
        $data['email']    = $email;
        $data['password'] = $password;

        $data = array_merge([], $options, $data);

        $response = $this->client->responseJson($this->client->request('post', $this->uri(), [
            'json' => $data,
        ]));

        return new UserResponse($this->client, $response);
    }

    /**
     * Find a user.
     *
     * @param string $uid UserId
     * @return UserResponse|null
     */
    public function find($uid)
    {
        return new UserResponse($this->client, $this->client->request('get', $this->uri($uid)));
    }
}