<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;
use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FollowingsTest extends TestCase
{
    public function testGetFollowingsFeed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followings-feed.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followings-feed-2.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $followingsFeed = $api->getFollowings(1234567);
        $this->assertCount(24, $followingsFeed->getUsers());
        $this->assertSame(55, $followingsFeed->getCount());
        $this->assertTrue($followingsFeed->hasNextPage());
        $this->assertSame('QVFCa2oybzk2OGxERHdEMzFPMGtsUjUzTUZ5MU9WZFo2SWFyeVkycWJucWlNb1FVV3VOY3V3NzZ2TkFSWHJ0MUJvX2ZQN2EzV29lRHVFQ0V3TE1vM05vNA==', $followingsFeed->getEndCursor());
        $this->assertCount(4, $followingsFeed->__serialize());

        // with endcursor
        $api->getMoreFollowings(1234567, $followingsFeed->getEndCursor());

        $api->logout('username');
    }

    public function testErrorWithGetFollowingsFeed()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], '{"data":{"user":""}}'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getFollowings(1234567);
    }
}
