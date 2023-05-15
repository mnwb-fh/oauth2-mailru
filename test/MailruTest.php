<?php
namespace Jokerov\OAuth2\Client\Test\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Jokerov\OAuth2\Client\Provider\Mailru;
use Jokerov\OAuth2\Client\Provider\MailruResourceOwner;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class MailruTest extends TestCase
{
    /**
     * Sample JSON response
     *
     * @var array
     */
    protected $response;

    /**
     * Mail.ru instance provider
     *
     * @var Jokerov\OAuth2\Client\Provider\Mailru
     */
    protected $provider;

    /**
     * Setup this unit test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->response = '{"id":"123456679876543","email":"test@test.ru","name":"Test Testov"}';

        $this->provider = new Mailru([
            'clientId'     => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri'  => 'https://example.com/redirect',
        ]);

        parent::setUp();
    }

    /**
     * Tear down this unit test
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->provider = null;
        $this->response = null;
    }

    /**
     * Test base url
     *
     * @return void
     */
    public function testGetBaseAccessTokenUrl()
    {
        $this->assertEquals('https://oauth.mail.ru/token', $this->provider->getBaseAccessTokenUrl([]));
    }

    /**
     * Test authorization url request
     *
     * @return void
     */
    public function testGetAuthorizationUrl()
    {
        $uri = parse_url($this->provider->getAuthorizationUrl());
        parse_str($uri['query'], $query);

        $this->assertEquals('oauth.mail.ru', $uri['host']);
        $this->assertEquals('/login', $uri['path']);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertEquals('code', $query['response_type']);

        $this->assertNotNull($this->provider->getState());
    }

    /**
     * Test access token
     *
     * @return void
     */
    public function testGetAccessToken()
    {
        $mockHandler = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"mock_access_token","expires_in":3600}'),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);

        $client = new Client(['handler' => $handlerStack]);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertGreaterThan(time(), $token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    /**
     * Test resource owner request
     *
     * @return void
     */
    public function testGetResourceOwnerDetailsUrl()
    {
        $test_url = 'https://oauth.mail.ru/userinfo?access_token=mock_access_token';

        $token = $this->getMockBuilder('League\OAuth2\Client\Token\AccessToken')
                      ->disableOriginalConstructor()
                      ->getMock();

        $token->expects($this->once())
              ->method('getToken')
              ->willReturn('mock_access_token');

        $url = $this->provider->getResourceOwnerDetailsUrl($token);
        $this->assertEquals($test_url, $url);
    }

    /**
     * Test MailruResourceOwner
     *
     * @return void
     */
    public function testGetResourceOwner()
    {
        $response = json_decode($this->response, true);

        $token = $this->getMockBuilder('League\OAuth2\Client\Token\AccessToken')
                      ->disableOriginalConstructor()
                      ->getMock();

        $provider = $this->getMockBuilder(Mailru::class)
                         ->setMethods(array('fetchResourceOwnerDetails'))
                         ->getMock();

        $provider->expects($this->once())
                 ->method('fetchResourceOwnerDetails')
                 ->with($this->identicalTo($token))
                 ->willReturn($response);

        $resource = $provider->getResourceOwner($token);
        $this->assertInstanceOf(MailruResourceOwner::class, $resource);
        $this->assertEquals('123456679876543', $resource->getId());
        $this->assertEquals('test@test.ru', $resource->getEmail());
        $this->assertEquals('Test Testov', $resource->getName());
    }
}
