<?php
namespace Instagram\tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use Instagram\Api;
use Instagram\Exception\CacheException;
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

        $validHtmlFixtures       = file_get_contents(__DIR__ . '/fixtures/pgrimaud.html');
        $invalidHtmlJsonFixtures = file_get_contents(__DIR__ . '/fixtures/invalid_pgrimaud.html');
        $invalidHtmlFixtures     = '<html></html>';

        $validJsonFixtures   = file_get_contents(__DIR__ . '/fixtures/pgrimaud.json');
        $invalidJsonFixtures = '<html></html>';

        $headers = [
            'Set-Cookie' => 'cookie'
        ];

        $response              = new Response(200, $headers, $validHtmlFixtures);
        $mock                  = new MockHandler([$response]);
        $handler               = HandlerStack::create($mock);
        $this->validHtmlClient = new Client(['handler' => $handler]);

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
     * @throws InstagramException
     */
    public function testEmptyCacheValueOnJsonFeed()
    {
        $api = new Api($this->emptyCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testInvalidCacheValueOnJsonFeed()
    {
        $api = new Api($this->invalidCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testValidCacheValueOnJsonFeed()
    {
        $api = new Api($this->validCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
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
     * @throws InstagramException
     */
    public function testEmptyUserName()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testValidFeedReturn()
    {
        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->setUserName('pgrimaud');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramException
     */
    public function testInvalidHtmlFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->invalidHtmlClient);
        $api->setUserName('pgrimaud');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testValidHtmlFeedAndInvalidJsonValue()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validCacheManager, $this->invalidJsonHtmlClient);
        $api->setUserName('pgrimaud');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testUnwritableCacheManager()
    {
        $this->expectException(CacheException::class);

        $api = new Api($this->unwritableCacheManager, $this->validJsonClient);
        $api->setUserName('pgrimaud');
        $api->setEndCursor('endCursor');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testFeedContent()
    {
        $api = new Api($this->validCacheManager, $this->validHtmlClient);
        $api->setUserName('pgrimaud');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        $this->assertSame('184263228', $feed->getId());
        $this->assertSame('pgrimaud', $feed->getUserName());
        $this->assertSame('Gladiator retired - ESGI 14\'', $feed->getBiography());
        $this->assertSame('Pierre G', $feed->getFullName());

        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/f49bc1ac9af43314d3354b4c4a987c6d/5B5BB12E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg', $feed->getProfilePicture());

        $this->assertSame(342, $feed->getFollowers());
        $this->assertSame(114, $feed->getFollowing());

        $this->assertSame('https://p.ier.re/', $feed->getExternalUrl());
        $this->assertSame(33, $feed->getMediaCount());
        $this->assertSame('AQCHJTRY7cTG6nZCLrX6HkDIcHSfgNvslHRkLJK9X5u892u7moUUJdTARhZkXahDsd8iJtXYRvq12FxbqqAXsV3pEq9ST0wlMBdznqoZpFa-Xw', $feed->getEndCursor());

        $this->assertCount(12, $feed->getMedias());
    }

    /**
     * @throws InstagramException
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

        $this->assertSame('1758133053345287778', $media->getId());
        $this->assertSame('GraphImage', $media->getTypeName());

        $this->assertSame(1080, $media->getWidth());
        $this->assertSame(1080, $media->getHeight());

        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/dd39e08d3c740e764c61bc694d36f5a7/5B643B2F/t51.2885-15/s640x640/sh0.08/e35/30604700_183885172242354_7971196573931536384_n.jpg', $media->getThumbnailSrc());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/51a54157b8868d715b8dd51a5ecbc46d/5B632D4E/t51.2885-15/e35/30604700_183885172242354_7971196573931536384_n.jpg', $media->getDisplaySrc());

        $this->assertSame('https://www.instagram.com/p/BhmJLJwhM5i/', $media->getLink());

        $this->assertInstanceOf(\DateTime::class, $media->getDate());

        $this->assertSame(null, $media->getCaption());

        $this->assertSame(2, $media->getComments());
        $this->assertSame(14, $media->getLikes());

        $this->assertCount(5, $media->getThumbnails());
    }
}
