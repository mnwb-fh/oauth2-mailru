# Mail.ru OAuth2 client provider

[![Latest Version](https://img.shields.io/packagist/v/jokerov/oauth2-mailru.svg)](https://packagist.org/packages/jokerov/oauth2-mailru)
[![License](https://img.shields.io/packagist/l/jokerov/oauth2-mailru.svg)](https://packagist.org/packages/jokerov/oauth2-mailru)

This package provides [Mail.ru](https://oauth.mail.ru/) integration for [OAuth2 Client](https://github.com/thephpleague/oauth2-client) by the League.
For more information on Mail.Ru OAuth, please refer to its [documentation](https://oauth.mail.ru/docs).

## Installation

```sh
composer require jokerov/oauth2-mailru
```

## Usage

```php
$provider = new Jokerov\OAuth2\Client\Provider\Mailru([
    'clientId'     => 'de8ad9b26d4de22c3adc4d72b9bf31af',
    'clientSecret' => '380c6afa85df4a7f1d40525e6be42e01',
    'redirectUri'  => 'https://example.com/oauth2-endpoint',
]);
```
