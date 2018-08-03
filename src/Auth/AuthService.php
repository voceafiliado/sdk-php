<?php namespace VCA\Sdk\Auth;

use VCA\Sdk\Service;
use VCA\Sdk\User\UserResponse;

class AuthService extends Service
{
    /**
     * @return bool
     */
    public function login($email, $password)
    {
        $return = $this->client->responseJson($this->client->request('post', $this->uri('login'), [
            'form_params' => [
                'email' => $email,
                'password' => $password,
            ],
        ]));

        $this->client->config(['access_token' => $return['access_token']]);

        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $return = $this->client->responseJson($this->client->request('get', $this->uri('logout')));

        // Voltar para o token original se houver
        if ($return == true) {
            $this->client->config(['access_token' => $this->client->config('access_token_original')]);
        }

        return true;
    }

    /**
     * User logged.
     *
     * @return UserResponse
     */
    public function me()
    {
        return new UserResponse($this->client, $this->client->request('get', $this->uri('me')));
    }
}