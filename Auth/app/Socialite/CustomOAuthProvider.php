<?php

namespace Modules\Auth\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use JsonException;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use LogicException;
use Modules\Auth\Models\OAuthProvider;

class CustomOAuthProvider extends AbstractProvider
{
    protected ?OAuthProvider $oauthProvider;

    protected $openidConfig = null;

    public function __construct(
        Request $request, $clientId, $clientSecret, $redirectUrl, $guzzle = [], ?OAuthProvider $oauthProvider = null
    ) {
        $this->oauthProvider = $oauthProvider;

        parent::__construct(
            $request,
            $oauthProvider?->client_id ?? $clientId,
            $oauthProvider?->client_secret ?? $clientSecret,
            $redirectUrl,
            $guzzle
        );
    }

    protected function getAuthUrl($state)
    {
        $config = $this->getOpenIdConfig();

        return $this->buildAuthUrlFromBase($this->getDiscoveryEndpoint($config, 'authorization_endpoint'), $state);
    }

    protected function getOpenIdConfig()
    {
        if ($this->openidConfig === null) {
            if (! $this->oauthProvider) {
                throw new LogicException('An OAuthProvider model is required.');
            }

            $response = $this->getHttpClient()->get($this->oauthProvider->discovery_url);
            try {
                $config = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw new LogicException('The OAuth discovery document contains invalid JSON.', previous: $exception);
            }

            if (! is_array($config)) {
                throw new LogicException('The OAuth discovery document must contain a JSON object.');
            }

            $this->openidConfig = $config;
        }

        return $this->openidConfig;
    }

    protected function getDiscoveryEndpoint(array $config, string $endpoint): string
    {
        if (! isset($config[$endpoint]) || ! is_string($config[$endpoint]) || $config[$endpoint] === '') {
            throw new LogicException(sprintf(
                'The OAuth discovery document does not contain a valid "%s".',
                $endpoint
            ));
        }

        return $config[$endpoint];
    }

    protected function getTokenUrl()
    {
        $config = $this->getOpenIdConfig();

        return $this->getDiscoveryEndpoint($config, 'token_endpoint');
    }

    protected function getUserByToken($token)
    {
        $config = $this->getOpenIdConfig();
        $response = $this->getHttpClient()->get($this->getDiscoveryEndpoint($config, 'userinfo_endpoint'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        if (! $this->oauthProvider) {
            throw new LogicException('An OAuthProvider model is required.');
        }

        $mappedUser = [];
        foreach ($this->oauthProvider->fields ?? [] as $field) {
            if (! isset($field['oauth'], $field['db']) || $field['oauth'] === '' || $field['db'] === '') {
                continue;
            }

            if ($field['db'] === 'id') {
                continue;
            }

            $mappedUser[$field['db']] = data_get($user, $field['oauth']);
        }

        $providerId = data_get($user, 'sub', data_get($user, 'id'));
        if ($providerId === null) {
            throw new LogicException('The OAuth response does not contain a provider ID.');
        }

        return (new User)->setRaw($user)->map(['id' => $providerId] + $mappedUser);
    }
}
