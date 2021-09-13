<?php

namespace Instagram\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Instagram\Api;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class MediasTest extends TestCase
{
    public function testGetMediaComments()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/tagged-medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $feed = $api->getTaggedMedias(123456789);
        $this->assertCount(10, $feed->getMedias());
        $this->assertTrue($feed->hasNextPage());
        $this->assertSame('QVFEeG5UZm1Edm0zeVB2VFo3QTdBdnhLVWE0TmFnN3dtUmpXWVFOSHlMUElWYzEwZjZSM1A3eEFvYW5DX2FlOVVjNk1TMkl3VXpFOXFELU5zeWg3dmY3Qw==', $feed->getEndCursor());

        $api->logout('username');
    }
}
