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
    public function testEmptyUserIdAndEmptyUserName()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->retrieveUserData(true);
        $api->retrieveMediaData(true);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyUserIdAndMaxId()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setMaxId(123);
        $api->setUserName('pgrimaud');
        $api->retrieveUserData(true);
        $api->retrieveMediaData(true);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidFeedReturn()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserName('pgrimaud');

        $api->retrieveMediaData(false);
        $api->retrieveUserData(true);
        $api->setQueryHash('xxxx');
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyUserName()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserId(123);

        $api->retrieveMediaData(false);
        $api->retrieveUserData(true);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyUserFeedReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->invalidUserClient, $this->invalidMediaClient);
        $api->setUserName('pgrimaud');
        $api->retrieveMediaData(false);
        $api->retrieveUserData(true);
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
        $api->setUserName('pgrimaud');
        $api->setUserId(123);
        $api->retrieveMediaData(true);
        $api->retrieveUserData(true);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidFeedWithMaxIdReturn()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserName('pgrimaud');
        $api->setUserId(12345);
        $api->setMaxId(1);

        $api->retrieveMediaData(true);
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testValidFeedWithoutUserNameReturn()
    {
        $this->expectException(InstagramException::class);

        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->getFeed();
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFeedContent()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserName('pgrimaud');
        $api->setUserId(123);

        /** @var Feed $feed */
        $api->retrieveMediaData(true);
        $api->retrieveUserData(true);
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        // test feed
        $this->assertInstanceOf(Feed::class, $feed);

        $this->assertSame('184263228', $feed->getId());
        $this->assertSame('pgrimaud', $feed->getUserName());
        $this->assertSame('Gladiator retired - ESGI 14\'', $feed->getBiography());
        $this->assertSame('Pierre G', $feed->getFullName());

        $this->assertSame(true, $feed->getHasNextPage());
        $this->assertSame(false, $feed->getisVerified());

        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/faf7cfb2f6ea29b57d3032717d8789bf/5B34242E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg', $feed->getProfilePicture());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/faf7cfb2f6ea29b57d3032717d8789bf/5B34242E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg', $feed->getProfilePictureHd());

        $this->assertSame(337, $feed->getFollowers());
        $this->assertSame(112, $feed->getFollowing());

        $this->assertSame('https://p.ier.re/', $feed->getExternalUrl());
        $this->assertSame(30, $feed->getMediaCount());

        $this->assertCount(12, $feed->getMedias());
    }

    /**
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testMediaContent()
    {
        $api = new Api($this->validUserClient, $this->validMediaClient);
        $api->setUserName('pgrimaud');
        $api->setUserId(123);

        /** @var Feed $feed */
        $api->retrieveMediaData(true);
        $feed = $api->getFeed();

        $this->assertInstanceOf(Feed::class, $feed);

        /** @var Media $media */
        $media = $feed->getMedias()[0];

        $this->assertInstanceOf(Media::class, $media);

        $this->assertSame('1676900800864278214', $media->getId());
        $this->assertSame('GraphImage', $media->getTypeName());

        $this->assertSame(1080, $media->getWidth());
        $this->assertSame(1080, $media->getHeight());

        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/90b54127c36ce17fefee861606db228e/5B430967/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg', $media->getThumbnailSrc());
        $this->assertSame('https://scontent-cdg2-1.cdninstagram.com/vp/89ddb8f8c3466e7436c29d041ece4300/5B4AF306/t51.2885-15/e35/25024600_726096737595175_9198105573181095936_n.jpg', $media->getDisplaySrc());
        $this->assertSame(1080, $media->getHeight());

        $this->assertCount(5, $media->getThumbnailResources());

        $this->assertSame('BdFjGTPFVbG', $media->getCode());
        $this->assertSame('https://www.instagram.com/p/BdFjGTPFVbG/', $media->getLink());

        $this->assertInstanceOf(\DateTime::class, $media->getDate());

        $this->assertSame('🎄🎅💸🙃 #casino #monaco', $media->getCaption());

        $this->assertSame(0, $media->getComments());
        $this->assertSame(29, $media->getLikes());
    }
}
