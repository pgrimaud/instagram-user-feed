<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Cookie\CookieJar, Cookie\SetCookie, Handler\MockHandler, HandlerStack, Psr7\Response};

use Instagram\Api;
use Instagram\Auth\Session;
use Instagram\Exception\InstagramAuthException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class LoginWithCookiesTest extends TestCase
{
    public function testSucceededLoginWithCookie()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        // dummy cookie
        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(2621543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar = new CookieJar();
        $cookiesJar->setCookie($cookie);

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api(null, $client);
        $api->loginWithCookies($cookiesJar);

        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame(1518284433, $profile->getId());
    }

    public function testLoginWithCookiesWithExpiredSession()
    {
        $this->expectException(InstagramAuthException::class);
        $this->expectExceptionMessage('Session expired, Please login with instagram credentials.');

        // dummy cookie
        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456788');
        $cookie->setExpires(1521543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar = new CookieJar();
        $cookiesJar->setCookie($cookie);

        $api = new Api();
        $api->loginWithCookies($cookiesJar);
    }
}