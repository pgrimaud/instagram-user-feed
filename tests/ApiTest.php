<?php
namespace Instagram\tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use Instagram\Api;
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\Feed;
use Instagram\Hydrator\Media;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $validUserClient;

    /**
     * @var Client
     */
    private $invalidUserClient;

    /**
     * @var Client
     */
    private $validMediaClient;

    /**
     * @var Client
     */
    private $invalidMediaClient;

    public function setUp()
    {
        $validUserFixtures   = file_get_contents(__DIR__ . '/fixtures/user_feed.json');
        $invalidUserFixtures = '';

        $response              = new Response(200, [], $validUserFixtures);
        $mock                  = new MockHandler([$response]);
        $handler               = HandlerStack::create($mock);
        $this->validUserClient = new Client(['handler' => $handler]);

        $response                = new Response(200, [], $invalidUserFixtures);
        $mock                    = new MockHandler([$response]);
        $handler                 = HandlerStack::create($mock);
        $this->invalidUserClient = new Client(['handler' => $handler]);

        $validMediaFixtures   = file_get_contents(__DIR__ . '/fixtures/medias_feed.json');
        $invalidMediaFixtures = '';

        $response               = new Response(200, [], $validMediaFixtures);
        $mock                   = new MockHandler([$response]);
        $handler                = HandlerStack::create($mock);
        $this->validMediaClient = new Client(['handler' => $handler]);

        $response                 = new Response(200, [], $invalidMediaFixtures);
        $mock                     = new MockHandler([$response]);
        $handler                  = HandlerStack::create($mock);
        $this->invalidMediaClient = new Client(['handler' => $handler]);
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyUserId()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setAccessToken('123.123.1233');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testMissingAccessToken()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserId(123);

        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidFeedReturn()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserId(1234);
        $api->setAccessToken('123.123.1233');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyUserFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->invalidUserClient, $this->invalidMediaClient);
        $api->setUserId(123);
        $api->setAccessToken('123.123.1234');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyMediaFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->invalidMediaClient);
        $api->setUserId(123);
        $api->setAccessToken('123.123.1234');
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidFeedReturnWithMaxId()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserId(123);
        $api->setAccessToken('123.123.1234');
        $api->setMaxId('123_1234');

        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFeedContent()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserId(123);
        $api->setAccessToken('123.123.1233');

        /** @var Feed $feed */
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        // test feed
        $this->assertInstanceOf(Feed::class, $feed);

        $this->assertSame('184263228', $feed->getId());
        $this->assertSame('pgrimaud', $feed->getUserName());
        $this->assertSame('Gladiator retired - ESGI 14\'', $feed->getBiography());
        $this->assertSame('Pierre G', $feed->getFullName());

        $this->assertSame(true, $feed->getHasNextPage());

        $this->assertSame('https://scontent.cdninstagram.com/vp/f49bc1ac9af43314d3354b4c4a987c6d/5B5BB12E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg', $feed->getProfilePicture());

        $this->assertSame(342, $feed->getFollowers());
        $this->assertSame(114, $feed->getFollowing());

        $this->assertSame('https://p.ier.re/', $feed->getExternalUrl());
        $this->assertSame(33, $feed->getMediaCount());

        $this->assertCount(20, $feed->getMedias());

        $this->assertSame('1230468487398454311_184263228', $feed->getMaxId());
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testMediaContent()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserId(123);
        $api->setAccessToken('123.123.1233');

        /** @var Feed $feed */
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        /** @var Media $media */
        $media = $feed->getMedias()[0];

        $this->assertInstanceOf(Media::class, $media);

        $this->assertSame('1758133053345287778_184263228', $media->getId());
        $this->assertSame('image', $media->getTypeName());

        $this->assertSame(640, $media->getWidth());
        $this->assertSame(640, $media->getHeight());

        $this->assertSame('https://scontent.cdninstagram.com/vp/e64c51de7f5401651670fd0bbdfd9837/5B69AF2B/t51.2885-15/s150x150/e35/30604700_183885172242354_7971196573931536384_n.jpg', $media->getThumbnailSrc());
        $this->assertSame('https://scontent.cdninstagram.com/vp/dd39e08d3c740e764c61bc694d36f5a7/5B643B2F/t51.2885-15/s640x640/sh0.08/e35/30604700_183885172242354_7971196573931536384_n.jpg', $media->getDisplaySrc());
        $this->assertSame(640, $media->getHeight());

        $this->assertSame('https://www.instagram.com/p/BhmJLJwhM5i/', $media->getLink());

        $this->assertInstanceOf(\DateTime::class, $media->getDate());

        $this->assertSame(null, $media->getCaption());

        $this->assertSame(2, $media->getComments());
        $this->assertSame(14, $media->getLikes());
    }
}
