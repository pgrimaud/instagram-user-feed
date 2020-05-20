<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApiTest extends TestCase
{
    public function testApi()
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
        $this->assertSame(46383825, $profile->getFollowers());
        $this->assertSame(50, $profile->getFollowing());
        $this->assertSame('https://scontent-frt3-2.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-frt3-2.cdninstagram.com&_nc_ohc=h2zGWoshNjUAX90AcTx&oh=ec27e20298c8765eccdfeb9c1b655f76&oe=5EEEC338', $profile->getProfilePicture());
        $this->assertSame('http://coreresponse.org/covid19', $profile->getExternalUrl());
        $this->assertSame(false, $profile->isPrivate());
        $this->assertSame(true, $profile->isVerified());
        $this->assertSame(453, $profile->getMediaCount());
        $this->assertSame(true, $profile->hasMoreMedias());

        if ($profile->hasMoreMedias()) {
            $api->getMoreMedias($profile);
        }

        $api->logout();
    }
}
