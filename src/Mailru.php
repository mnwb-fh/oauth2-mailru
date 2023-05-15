<?php

namespace Jokerov\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Mailru extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://oauth.mail.ru/login';
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://oauth.mail.ru/token';
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://oauth.mail.ru/userinfo?access_token=' . $token->getToken();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (isset($data['error_code'])) {
            throw new IdentityProviderException(
                $data['error_description'],
                $data['error_code'],
                $response
            );
        } elseif (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error'],
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new MailruResourceOwner($response);
    }
}
