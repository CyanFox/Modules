<?php

namespace Modules\Auth\Socialite;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class CustomOAuthProvider extends AbstractProvider
{
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(settings('auth.oauth.auth_url'), $state);
    }

    protected function getTokenUrl()
    {
        return settings('auth.oauth.token_url');
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(settings('auth.oauth.user_url'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user[settings('auth.oauth.id_field')],
            'name' => $user[settings('auth.oauth.username_field')],
            'email' => $user[settings('auth.oauth.email_field')],
        ]);
    }
}
