<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Cookie\CookieJar, Cookie\SetCookie, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use Instagram\Auth\Session;
use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Hashtag;
use Instagram\Model\Media;
use Instagram\Model\Profile;
use Instagram\Model\Reels;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ReelsTest extends TestCase
{
    use GenerateCookiesTrait;

    public function testValidReelsFetch()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/reels.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/reels.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        $api->login('username', 'password');

        $reelsFeed = $api->getReels(123456788);

        $this->assertCount(12, $reelsFeed->getReels());

        /** @var Reels $firstReels */
        $firstReels = current($reelsFeed->getReels());

        $this->assertSame('2589531623544609760_3171067', $firstReels->getId());
        $this->assertSame('CPv3UqsBFvg', $firstReels->getShortCode());
        $this->assertSame(16965, $firstReels->getLikes());
        $this->assertSame(20.433, $firstReels->getVideoDuration());
        $this->assertSame(100856, $firstReels->getViewCount());
        $this->assertSame(401421, $firstReels->getPlayCount());
        $this->assertCount(8, $firstReels->getImageVersions());
        $this->assertCount(3, $firstReels->getVideoVersions());
        $this->assertSame(1622916710, $firstReels->getDate()->getTimestamp());
        $this->assertSame('Une journée d’évasion en famille 🐮🌿', $firstReels->getCaption());

        $api->getReels(123456788, $reelsFeed->getMaxId());

        $this->assertTrue($reelsFeed->hasMaxId());

        $api->logout('username');
    }

    public function testGetReelsWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(404, []),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getReels(1);

    }

    public function testGetReelsWithInvalidJson()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getReels(1);
    }
}
