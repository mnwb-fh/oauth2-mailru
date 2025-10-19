<?php

namespace mnwb\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class MailruUser implements ResourceOwnerInterface
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
        return $this->getResponseValue('uid');
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
     * User's gender
     *
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->getResponseValue('sex');
    }

    /**
     * User's first and last name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getResponseValue('first_name').(($this->getResponseValue('first_name') && $this->getResponseValue('last_name')) ? ' ' : '').$this->getResponseValue('last_name');
    }

    /**
     * User's nickname
     *
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->getResponseValue('nick');
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
     * Get locale.
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->getResponseValue('location')['city']['name'].(($this->getResponseValue('location')['city']['name'] && $this->getResponseValue('location')['country']['name']) ? ', ' : '').$this->getResponseValue('location')['country']['name'];
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
     * User's birthday dd.mm.YYYY
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
        return $this->getResponseValue('pic_big');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->response[0];
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    private function getResponseValue($key)
    {
        return $this->response[0][$key] ?? null;
    }
}
