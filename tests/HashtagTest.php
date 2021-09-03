<?php

namespace Instagram\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Instagram\Api;
use Instagram\Model\Hashtag;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class HashtagTest extends TestCase
{
    public function testGetHashtag()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/hashtag.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        /** @var Hashtag $hashtag */
        $hashtag = $api->getHashtag('paris');

        $this->assertSame(17841562894101784, $hashtag->getId());
        $this->assertSame('paris', $hashtag->getName());
        $this->assertFalse($hashtag->isTopMediaOnly());
        $this->assertFalse($hashtag->isFollowing());
        $this->assertTrue($hashtag->isAllowFollowing());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/v/t51.2885-15/e35/s150x150/143729191_707659816564323_8378880941716604054_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=1&_nc_ohc=k1fqM0dgMikAX8TI-Xu&tp=1&oh=0ce210f245a8b7789031a2a77d3b8357&oe=60406A2A', $hashtag->getProfilePicture());
        $this->assertSame(125782874, $hashtag->getMediaCount());
        $this->assertCount(34, $hashtag->getMedias());
        $this->assertTrue($hashtag->hasMoreMedias());
        $this->assertSame('QVFEYy1pYW90TGt4aWF0aExLQnM4c1JCYV9BbkhkbmZySzRKRVBrWEtCR0pJQlJES0NNUFVmbE5CUDF2eW45eVp0Mk1odG5KT3pJeHE0R2ZDd05reEItbA==', $hashtag->getEndCursor());
        $this->assertIsArray($hashtag->toArray());
        $this->assertSame(17841562894101784, $hashtag->__serialize()['id']);

        $api->logout('username');
    }

    public function testGetMoreHashtag()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/hashtag-2.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        /** @var Hashtag $hashtag */
        $hashtag = $api->getMoreHashtagMedias('paris', 'endcursor');

        $this->assertSame('paris', $hashtag->getName());

        $api->logout('username');
    }
}