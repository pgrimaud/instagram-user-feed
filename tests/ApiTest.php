<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApiTest extends TestCase
{
    public function testValidApiCalls()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame(1518284433, $profile->getId());
        $this->assertSame('robertdowneyjr', $profile->getUserName());
        $this->assertSame('Robert Downey Jr. Official', $profile->getFullName());
        $this->assertSame('@officialfootprintcoalition @coreresponse', $profile->getBiography());
        $this->assertSame(46383825, $profile->getFollowers());
        $this->assertSame(50, $profile->getFollowing());
        $this->assertSame('https://scontent-frt3-2.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-frt3-2.cdninstagram.com&_nc_ohc=h2zGWoshNjUAX90AcTx&oh=ec27e20298c8765eccdfeb9c1b655f76&oe=5EEEC338', $profile->getProfilePicture());
        $this->assertSame('http://coreresponse.org/covid19', $profile->getExternalUrl());
        $this->assertSame(false, $profile->isPrivate());
        $this->assertSame(true, $profile->isVerified());
        $this->assertSame(453, $profile->getMediaCount());
        $this->assertSame(true, $profile->hasMoreMedias());
        $this->assertSame(12, count($profile->getMedias()));

        $profile = $api->getMoreMedias($profile);
        $media   = $profile->getMedias()[0];

        $this->assertSame(2224305748263047050, $media->getId());
        $this->assertSame('GraphVideo', $media->getTypeName());
        $this->assertSame(421, $media->getHeight());
        $this->assertSame(750, $media->getWidth());
        $this->assertSame('https://www.instagram.com/p/B7eUksNFA-K/', $media->getLink());
        $this->assertInstanceOf(\DateTime::class, $media->getDate());

        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=725b6b887273f8402ea4d448abd456f9&oe=5EC809A6', $media->getDisplaySrc());
        $this->assertSame('#happybirthday little brother @harrycollettactor , quite the night out in #berlin #germany with #stubbins and #ladyrose @carmellaniadoofficial @dolittlemovie #press tour #trudges on... (🎥 @jimmy_rich ) #sweet #16', $media->getCaption());
        $this->assertSame(1939, $media->getComments());
        $this->assertSame(695047, $media->getLikes());
        $this->assertSame(5, count($media->getThumbnails()));
        $this->assertInstanceOf(\StdClass::class, $media->getLocation());
        $this->assertSame(true, $media->isVideo());
        $this->assertSame(2726827, $media->getVideoViewCount());

        $api->logout();
    }

    public function testJsonMediasWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-medias-error.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');
        $api->getMoreMedias($profile);

        $api->logout();
    }
}
