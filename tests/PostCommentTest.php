<?php

namespace Instagram\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Instagram\Api;
use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;

class PostCommentTest extends TestCase
{
    use GenerateCookiesTrait;

    public function testPostComment()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/comment-post.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        $api->login('username', 'password');

        $result = $api->commentPost(123455, 'Awesome!');
        $this->assertSame('ok', $result);

        $api->logout('username');
    }

    public function testPostCommentWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], '{"status":""}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        $api->login('username', 'password');

        $api->commentPost(123455, 'Awesome!');

        $api->logout('username');
    }
}
