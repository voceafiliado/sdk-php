<?php namespace VCA\Sdk\Auth;

use VCA\Sdk\Service;
use VCA\Sdk\User\UserResponse;

class AuthService extends Service
{
    /**
     * @return string
     * Loga e retorna o access_token
     */
    public function login($email, $password)
    {
        $return = $this->client->responseJson($this->client->request('post', $this->uri('login'), [
            'form_params' => [
                'email' => $email,
                'password' => $password,
            ],
        ]));

        $this->client->config(['access_token' => $token = $return['access_token']]);

        return $token;
    }

    /**
     * Fazer login pelo token e retorn o usuario.
     *
     * @param $access_token
     * @return UserResponse
     */
    public function loginByToken($access_token)
    {
        $user = new UserResponse($this->client, $this->client->request('get', $this->uri('check', [$access_token])));

        $this->client->config(['access_token' => $access_token]);

        return $user;
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