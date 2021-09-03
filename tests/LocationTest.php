<?php

namespace Instagram\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Instagram\Api;
use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class LocationTest extends TestCase
{
    public function testGetLocation()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/location.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/location-medias.json')),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $location = $api->getLocation(558750588);

        $this->assertEquals(558750588, $location->getId());
        $this->assertSame('Little Choc Apothecary', $location->getName());
        $this->assertTrue($location->hasPublicPage());
        $this->assertSame(40.71192, $location->getLatitude());
        $this->assertSame(-73.95721, $location->getLongitude());
        $this->assertSame('little-choc-apothecary', $location->getSlug());
        $this->assertSame('NYC\'s first fully vegan and gluten-free creperie! ', $location->getDescription());
        $this->assertSame('http://littlechoc.nyc', $location->getWebsite());
        $this->assertSame('(718) 963-0420', $location->getPhone());
        $this->assertSame('LittleChoc', $location->getFacebookAlias());
        $this->assertCount(8, $location->getAddress());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/v/t51.2885-15/e35/c0.179.1440.1440a/s150x150/74607460_3223523367722884_955674193158361307_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=104&_nc_ohc=jU8HIsOILvoAX_HbDTh&tp=16&oh=2fa2f9f8da8a84e17bcecf934e37d673&oe=5FCFAF2A', $location->getProfilePicture());
        $this->assertEquals(6446, $location->getTotalMedia());
        $this->assertIsArray($location->getMedias());
        $this->assertTrue($location->hasMoreMedias());
        $this->assertSame('2420224628294009909', $location->getEndCursor());

        $moreMedias = $api->getMoreLocationMedias(558750588, $location->getEndCursor());
        $this->assertCount(24, $moreMedias->getMedias());

        $api->logout('username');
    }

    public function testGetLocationWithLocationNotFound()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(404),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $api->getLocation(1234567);

        $api->logout('username');
    }

    public function testGetLocationWithInternalError()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(429),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $api->getLocation(558750588);

        $api->logout('username');
    }

    public function testGetLocationWithInvalidJson()
    {
        $this->expectException(InstagramFetchException::class);

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], 'RIEN'),
        ]);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $api->getLocation(1234567);

        $api->logout('username');
    }
}