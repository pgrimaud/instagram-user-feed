<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApiTest extends TestCase
{
    public function testLogin()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], 'Hello, World'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);
        $this->assertInstanceOf(Api::class, $api);
    }
}
