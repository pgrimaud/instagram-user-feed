<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;
use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;

class LikeUnlikeTest extends TestCase
{
    use GenerateCookiesTrait;

    public function testLikeAndUnlikeUser()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/follow.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/unfollow.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->like(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unlike(1234567);
        $this->assertSame('ok', $result);

        $api->logout('username');
    }

    public function testLikeUnlikeWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], '{"status": ""}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        // clear cache
        $api->login('username', 'password');

        $result = $api->like(1234567);
        $this->assertSame('ok', $result);

        $result = $api->unlike(1234567);
        $this->assertSame('ok', $result);

        $api->logout('username');
    }
}
