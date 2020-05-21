<?php

namespace Instagram\Tests\Auth;

use GuzzleHttp\{Client, Cookie\CookieJar, Cookie\SetCookie, Handler\MockHandler, HandlerStack, Psr7\Response};

use Instagram\Api;
use Instagram\Auth\Login;
use Instagram\Auth\Session;
use Instagram\Exception\InstagramAuthException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class LoginTest extends TestCase
{
    public function testWithNoResultFromInstagram()
    {
        $this->expectException(InstagramAuthException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie']),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $login = new Login($client, 'username', 'password');
        $login->process();
    }

    public function testLoginWithWrongPassword()
    {
        $this->expectException(InstagramAuthException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/../fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-login-error.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $login   = new Login($client, 'username', 'password');
        $cookies = $login->process();

        $this->assertInstanceOf(CookieJar::class, $cookies);
    }

    public function testLoginWithUnknownError()
    {
        $this->expectException(InstagramAuthException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/../fixtures/instagram-home.html')),
            new Response(400),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $login   = new Login($client, 'username', 'password');
        $cookies = $login->process();

        $this->assertInstanceOf(CookieJar::class, $cookies);
    }

    public function testSucceededLogin()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/../fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-login-success.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $login   = new Login($client, 'username', 'password');
        $cookies = $login->process();

        $this->assertInstanceOf(CookieJar::class, $cookies);
    }

    public function testLoginWithExpiredSession()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

        // dummy cookie
        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(1521543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar = new CookieJar();
        $cookiesJar->setCookie($cookie);

        $cacheItem = $cachePool->getItem(Session::SESSION_KEY);
        $cacheItem->set($cookiesJar);
        $cachePool->save($cacheItem);

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        $api->login('username', 'password');

        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame(1518284433, $profile->getId());

        $api->logout();
    }

    public function testReuseSessionWhenLogin()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

        // dummy cookie
        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar = new CookieJar();
        $cookiesJar->setCookie($cookie);

        $cacheItem = $cachePool->getItem(Session::SESSION_KEY);
        $cacheItem->set($cookiesJar);
        $cachePool->save($cacheItem);

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        $api->login('username', 'password');

        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame(1518284433, $profile->getId());

        $api->logout();
    }

    public function testFetchingDataWithoutLogin()
    {
        $this->expectException(InstagramAuthException::class);
        
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/instagram-profile.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);
        $api->logout();
        $api->getProfile('robertdowneyjr');
    }
}
