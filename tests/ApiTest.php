<?php
namespace Instagram\tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use Instagram\Api;
use Instagram\Exception\InstagramCacheException;
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\Component\Feed;
use Instagram\Hydrator\Component\Media;
use Instagram\Storage\CacheManager;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheManager
     */
    private $validCacheManager;

    /**
     * @var CacheManager
     */
    private $invalidCacheManager;

    /**
     * @var CacheManager
     */
    private $emptyCacheManager;

    /**
     * @var CacheManager
     */
    private $unwritableCacheManager;

    /**
     * @var Client
     */
    private $validHtmlClient;

    /**
     * @var Client
     */
    private $validStatementHtmlClient;

    /**
     * @var Client
     */
    private $invalidJsonHtmlClient;

    /**
     * @var Client
     */
    private $invalidHtmlClient;

    /**
     * @var Client
     */
    private $validJsonClient;

    /**
     * @var Client
     */
    private $invalidJsonClient;

    /**
     * @return void
     */
    public function setUp()
    {
        if (is_file(__DIR__ . '/cache/empty/pgrimaud.cache')) {
            unlink(__DIR__ . '/cache/empty/pgrimaud.cache');
        }

        copy(__DIR__ . '/cache/invalid/demo.cache', __DIR__ . '/cache/invalid/pgrimaud.cache');

        $validHtmlFixtures          = file_get_contents(__DIR__ . '/fixtures/pgrimaud.html');
        $validStatementHtmlFixtures = file_get_contents(__DIR__ . '/fixtures/statement.paris.html');
        $invalidHtmlJsonFixtures    = file_get_contents(__DIR__ . '/fixtures/invalid_pgrimaud.html');
        $invalidHtmlFixtures        = '<html></html>';

        $validJsonFixtures   = file_get_contents(__DIR__ . '/fixtures/pgrimaud.json');
        $invalidJsonFixtures = '<html></html>';

        $headers = [
            'Set-Cookie' => 'cookie'
        ];

        $response              = new Response(200, $headers, $validHtmlFixtures);
        $mock                  = new MockHandler([$response]);
        $handler               = HandlerStack::create($mock);
        $this->validHtmlClient = new Client(['handler' => $handler]);

        $response                       = new Response(200, $headers, $validStatementHtmlFixtures);
        $mock                           = new MockHandler([$response]);
        $handler                        = HandlerStack::create($mock);
        $this->validStatementHtmlClient = new Client(['handler' => $handler]);

        $response                = new Response(200, [], $invalidHtmlFixtures);
        $mock                    = new MockHandler([$response]);
        $handler                 = HandlerStack::create($mock);
        $this->invalidHtmlClient = new Client(['handler' => $handler]);

        $response                    = new Response(200, $headers, $invalidHtmlJsonFixtures);
        $mock                        = new MockHandler([$response]);
        $handler                     = HandlerStack::create($mock);
        $this->invalidJsonHtmlClient = new Client(['handler' => $handler]);

        $response              = new Response(200, $headers, $validJsonFixtures);
        $mock                  = new MockHandler([$response]);
        $handler               = HandlerStack::create($mock);
        $this->validJsonClient = new Client(['handler' => $handler]);

        $response                = new Response(200, [], $invalidJsonFixtures);
        $mock                    = new MockHandler([$response]);
        $handler                 = HandlerStack::create($mock);
        $this->invalidJsonClient = new Client(['handler' => $handler]);

        if (is_dir(__DIR__ . '/cache/unwritable')) {
            rmdir(__DIR__ . '/cache/unwritable');
        }
        mkdir(__DIR__ . '/cache/unwritable', 0555);

        $this->validCacheManager      = new CacheManager(__DIR__ . '/cache/valid/');
        $this->invalidCacheManager    = new CacheManager(__DIR__ . '/cache/invalid/');
        $this->emptyCacheManager      = new CacheManager(__DIR__ . '/cache/empty/');
        $this->unwritableCacheManager = new CacheManager(__DIR__ . '/cache/unwritable/');
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyCacheValueOnJsonFeed()
    {
        $api = new Api($this->emptyCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testInvalidCacheValueOnJsonFeed()
    {
        $api = new Api($this->invalidCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidCacheValueOnJsonFeed()
    {
        $api = new Api($this->validCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testInvalidJsonFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->invalidJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyUserName()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidFeedReturn()
    {
        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->setUserName('pgrimaud');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testInvalidHtmlFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->invalidHtmlClient);
        $api->setUserName('pgrimaud');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidHtmlFeedAndInvalidJsonValue()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->invalidJsonHtmlClient);
        $api->setUserName('pgrimaud');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUnwritableCacheManager()
    {
        $this->expectException(InstagramCacheException::class);

        $api = new Api($this->unwritableCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFeedContent()
    {
        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->setUserName('pgrimaud');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        $this->assertSame('184263228', $feed->getId());
        $this->assertSame('pgrimaud', $feed->getUserName());
        $this->assertSame('Mercenary developer & retired gladiator', $feed->getBiography());
        $this->assertSame('Pierre Grimaud', $feed->getFullName());

        $this->assertSame(false, $feed->isPrivate());
        $this->assertSame(false, $feed->isVerified());

        $this->assertSame('https://scontent-sea1-1.cdninstagram.com/vp/1dd1ba2f432f6614be04ca9607e48800/5D5DDA2E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg?_nc_ht=scontent-sea1-1.cdninstagram.com', $feed->getProfilePicture());

        $this->assertSame(369, $feed->getFollowers());
        $this->assertSame(151, $feed->getFollowing());

        $this->assertSame('https://p.ier.re/', $feed->getExternalUrl());
        $this->assertSame(40, $feed->getMediaCount());
        $this->assertSame('QVFBbWp4dC1aYUFMc1p1czgtdEU2VFgxemVSem5XN3M0cnhxc0NxOTRHWmRBNWhqX3JoZnFRZE1pVzZmNndybDN2S2g3RXp2aXRfSjV0MTk1RV9lM09vSA==', $feed->getEndCursor());

        $this->assertCount(12, $feed->getMedias());
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testMediaContent()
    {
        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->setUserName('pgrimaud');

        /** @var Feed $feed */
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        /** @var Media $media */
        $media = $feed->getMedias()[0];

        $this->assertInstanceOf(Media::class, $media);

        $this->assertSame('1855305733072311817', $media->getId());
        $this->assertSame('GraphImage', $media->getTypeName());

        $this->assertSame(1080, $media->getWidth());
        $this->assertSame(1080, $media->getHeight());

        $this->assertSame('https://scontent-sea1-1.cdninstagram.com/vp/0d2e66f5dfb7330bd57621a76625e532/5D796EB6/t51.2885-15/sh0.08/e35/s640x640/39309315_472046209872119_3843556148607188992_n.jpg?_nc_ht=scontent-sea1-1.cdninstagram.com', $media->getThumbnailSrc());
        $this->assertSame('https://scontent-sea1-1.cdninstagram.com/vp/ff940b758ed4dfbd5f182b3a6b068e3e/5D6D0C53/t51.2885-15/e35/39309315_472046209872119_3843556148607188992_n.jpg?_nc_ht=scontent-sea1-1.cdninstagram.com', $media->getDisplaySrc());

        $this->assertSame('https://www.instagram.com/p/Bm_XrwBhVYJ/', $media->getLink());

        $this->assertInstanceOf(\DateTime::class, $media->getDate());

        $this->assertSame('Ola ! #casino #paella #ept #pokerstars', $media->getCaption());

        $this->assertSame(10, $media->getComments());
        $this->assertSame(35, $media->getLikes());

        $this->assertCount(5, $media->getThumbnails());

        $fakeLocation                  = new \StdClass();
        $fakeLocation->id              = '350380063';
        $fakeLocation->has_public_page = true;
        $fakeLocation->name            = 'Casino Barcelona';
        $fakeLocation->slug            = 'casino-barcelona';

        $location = $media->getLocation();

        $this->assertInstanceOf(\StdClass::class, $location);
        $this->assertSame($fakeLocation->id, $location->id);
        $this->assertSame($fakeLocation->has_public_page, $location->has_public_page);
        $this->assertSame($fakeLocation->name, $location->name);
        $this->assertSame($fakeLocation->slug, $location->slug);


        $this->assertSame(false, $media->isVideo());
        $this->assertSame(0, $media->getVideoViewCount());
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testHtmlFeedWithNoCacheManager()
    {
        $this->expectException(InstagramCacheException::class);

        $api = new Api(null, $this->validHtmlClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testStatementHtmlFeedWithVideoPost()
    {
        $api = new Api($this->validCacheManager, $this->validStatementHtmlClient);
        $api->setUserName('statement.paris');

        $feed = $api->getFeed();

        // fist media is a video
        /** @var Media $post */
        $media = $feed->getMedias()[0];
        $this->assertSame(199, $media->getVideoViewCount());
    }
}
