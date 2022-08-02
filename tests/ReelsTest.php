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

        /** @var ReelsFeed $reelsFeed */
        $reelsFeed = $api->getReels(123456788);

        $this->assertCount(12, $reelsFeed->getReels());
        $this->assertTrue($reelsFeed->hasMaxId());
        $this->assertSame('QVFDcVkyR09pYU9nZXN6RFp3bzFJbkQxckVuQ0lFM3BISFFhdWVjdHV0bGw0UzFqbjF5ODZVMjJ5TFAtaDFTOVVHdS1CLXNTdy1FZFRLLWFkU1Z3SGhlZg==', $reelsFeed->getMaxId());

        /** @var Reels $firstReels */
        $firstReels = current($reelsFeed->getReels());

        $this->assertSame('2589531623544609760', $firstReels->getId());
        $this->assertSame('CPv3UqsBFvg', $firstReels->getShortCode());
        $this->assertSame('https://www.instagram.com/reel/CPv3UqsBFvg/', $firstReels->getLink());
        $this->assertSame(16965, $firstReels->getLikes());
        $this->assertFalse($firstReels->isLiked());
        $this->assertSame(80, $firstReels->getComments());
        $this->assertSame(100856, $firstReels->getViews());
        $this->assertSame(401421, $firstReels->getPlays());
        $this->assertSame(20.433, $firstReels->getDuration());
        $this->assertSame(4032, $firstReels->getHeight());
        $this->assertSame(2268, $firstReels->getWidth());
        $this->assertTrue($firstReels->getHasAudio());
        $this->assertCount(8, $firstReels->getImages());
        $this->assertCount(3, $firstReels->getVideos());
        $this->assertSame('Une journée d’évasion en famille 🐮🌿', $firstReels->getCaption());
        $this->assertSame(null, $firstReels->getLocation());
        $this->assertSame(1622916710, $firstReels->getDate()->getTimestamp());
        $this->assertCount(0, $firstReels->getHashtags());
        $this->assertCount(1, $firstReels->getUserTags());

        /** @var User $userTags */
        $firstUserTags = $firstReels->getUserTags()[0];

        $this->assertSame(305379838, $firstUserTags->getId());
        $this->assertSame('saam_nas', $firstUserTags->getUserName());
        $this->assertSame('Saam', $firstUserTags->getFullName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/s150x150/117292984_626655964649190_5418868436046077194_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=nU2KegodIxYAX8USyvK&edm=ACHbZRIBAAAA&ccb=7-4&oh=a2dfb82054ec877fe02778b4737e17ac&oe=6139D680&_nc_sid=4a9e64', $firstUserTags->getProfilePicUrl());
        $this->assertFalse($firstUserTags->isPrivate());
        $this->assertFalse($firstUserTags->isVerified());
        
        /** @var User $user */
        $user = $firstReels->getUser();

        $this->assertSame(3171067, $user->getId());
        $this->assertSame('noholita', $user->getUserName());
        $this->assertSame('NOHOLITA 🌹 Camille Callen', $user->getFullName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/s150x150/121188252_1020958988369407_946994132399906308_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=ZTU6RVISXJoAX-dhxNr&edm=ACHbZRIBAAAA&ccb=7-4&oh=0411eb019f297bd197f81fdd62d7ba8f&oe=613909BA&_nc_sid=4a9e64', $user->getProfilePicUrl());
        $this->assertFalse($user->isPrivate());
        $this->assertTrue($user->isVerified());

        $api->getReels(123456788, $reelsFeed->getMaxId());

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
