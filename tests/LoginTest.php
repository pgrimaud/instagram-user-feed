<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Cookie\CookieJar, Handler\MockHandler, HandlerStack, Psr7\Response};

use Instagram\Auth\Login;
use Instagram\Exception\InstagramAuthException;
use PHPUnit\Framework\TestCase;

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
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-error.json')),
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
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
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
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $login   = new Login($client, 'username', 'password');
        $cookies = $login->process();

        $this->assertInstanceOf(CookieJar::class, $cookies);
    }
}
