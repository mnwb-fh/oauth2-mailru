<?php

namespace Jokerov\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class MailruResourceOwner implements ResourceOwnerInterface
{
    /**
     * Response
     *
     * @var array
     */
    private $response;

    /**
     * Class constructor
     * https://oauth.mail.ru/docs
     *
     * @param array $response
     *
     * @return void
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * User's ID from Mail.Ru
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->getResponseValue('id');
    }

    /**
     * User's email address
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getResponseValue('email');
    }

    /**
     * Application ClientID
     *
     * @return string|null
     */
    public function getApplicationClientId(): ?string
    {
        return $this->getResponseValue('client_id');
    }

    /**
     * User's gender
     * m - male, f - female
     *
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->getResponseValue('gender');
    }

    /**
     * User's first and last name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getResponseValue('name');
    }

    /**
     * User's nickname
     *
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->getResponseValue('nickname');
    }

    /**
     * User's first name
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->getResponseValue('first_name');
    }

    /**
     * User's last name
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->getResponseValue('last_name');
    }

    /**
     * User's locale
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->getResponseValue('locale');
    }

    /**
     * User's birthday
     *
     * @return string|null
     */
    public function getBirthday(): ?string
    {
        return $this->getResponseValue('birthday');
    }

    /**
     * User's avatar link
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->getResponseValue('image');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    private function getResponseValue($key)
    {
        return $this->response[$key] ?? null;
    }
}


