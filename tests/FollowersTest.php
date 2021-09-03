<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;
use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FollowersTest extends TestCase
{
    public function testGetFollowersFeed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followers-feed.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/followers-feed-2.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $followersFeed = $api->getFollowers(1234567);
        $this->assertCount(24, $followersFeed->getUsers());
        $this->assertSame(46650946, $followersFeed->getCount());
        $this->assertTrue($followersFeed->hasNextPage());
        $this->assertSame('QVFBVUJiZjcydktSUHZyMFFkbjdrU3NGN0M2bUhzWHQwRUNuMHJHNF9hWlBNSVA3aUxvRk5YSC02WlVNbXpHaGpUMUFJeFdjYVRIcVpaYXVVWEtfbDhYUw==', $followersFeed->getEndCursor());
        $this->assertCount(4, $followersFeed->__serialize());

        $userToTest = $followersFeed->getUsers()[0];

        $this->assertSame(41055093479, $userToTest->getId());
        $this->assertSame('martinasalomao0602', $userToTest->getUserName());
        $this->assertSame('Martina S', $userToTest->getFullName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/s150x150/118546270_727334558122985_5873885402138414428_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=3nIm_ZGjF-cAX9tM_rk&oh=a27e90a68da855ed18d8a23d72e1629c&oe=5F76E1FF', $userToTest->getProfilePicUrl());
        $this->assertFalse($userToTest->isPrivate());
        $this->assertFalse($userToTest->isVerified());
        $this->assertFalse($userToTest->isFollowedByViewer());
        $this->assertFalse($userToTest->isRequestedByViewer());

        $this->assertCount(8, $userToTest->__serialize());

        // with endcursor
        $api->getMoreFollowers(1234567, $followersFeed->getEndCursor());

        $api->logout('username');
    }

    public function testErrorWithGetFollowersFeed()
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

        $api->getFollowers(1234567);
    }
}
