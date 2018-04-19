<?php
namespace Instagram\tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use Instagram\Api;
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\Component\Feed;
use Instagram\Hydrator\Component\Media;
use Instagram\Storage\CacheManager;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Client
     */
    private $validClient;

    /**
     * @var Client
     */
    private $invalidClient;

    /**
     * @return void
     */
    public function setUp()
    {
        $validFixtures   = file_get_contents(__DIR__ . '/fixtures/pgrimaud.html');
        $invalidFixtures = '<html></html>';

        $headers = [
            'Set-Cookie' => 'cookie'
        ];

        $response          = new Response(200, $headers, $validFixtures);
        $mock              = new MockHandler([$response]);
        $handler           = HandlerStack::create($mock);
        $this->validClient = new Client(['handler' => $handler]);

        $response            = new Response(200, [], $invalidFixtures);
        $mock                = new MockHandler([$response]);
        $handler             = HandlerStack::create($mock);
        $this->invalidClient = new Client(['handler' => $handler]);

        $this->cacheManager = new CacheManager();

    }

    /**
     * @throws InstagramException
     */
    public function testEmptyUserName()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->cacheManager, $this->validClient);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testValidFeedReturn()
    {
        $api = new Api($this->cacheManager, $this->validClient);
        $api->setUserName('pgrimaud');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramException
     */
    public function testEmptyUserFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->cacheManager, $this->invalidClient);
        $api->setUserName('pgrimaud');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     */
    public function testFeedContent()
    {
        $api = new Api($this->cacheManager, $this->validClient);
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
        $api = new Api($this->cacheManager, $this->validClient);
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
    }
}
