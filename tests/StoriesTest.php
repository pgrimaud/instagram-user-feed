<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Cookie\CookieJar, Cookie\SetCookie, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use Instagram\Auth\Session;
use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Hashtag;
use Instagram\Model\Media;
use Instagram\Model\Profile;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class StoriesTest extends TestCase
{
    public function testValidStoriesFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $instagramStories = $api->getStories(123456788);

        $this->assertInstanceOf(\DateTime::class, $instagramStories->getExpiringDate());
        $this->assertInstanceOf(\StdClass::class, $instagramStories->getOwner());
        $this->assertFalse($instagramStories->isAllowedToReply());
        $this->assertTrue($instagramStories->isReshareable());

        foreach ($instagramStories->getStories() as $story) {
            $this->assertSame(2313478624923545316, $story->getId());
            $this->assertSame('GraphStoryVideo', $story->getTypeName());
            $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.12442-15/e15/100088826_248178139603068_2736940532157996068_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=ZFwA_I6gw40AX_dnybW&oh=700dca3b88fa748a1bd6e7bb570b23c4&oe=5EC918AF', $story->getDisplayUrl());
            $this->assertSame(750, $story->getWidth());
            $this->assertSame(1333, $story->getHeight());
            $this->assertSame('https://www.youtube.com/watch?v=-bhq2bxwAzg', $story->getCtaUrl());
            $this->assertInstanceOf(\DateTime::class, $story->getTakenAtDate());
            $this->assertInstanceOf(\DateTime::class, $story->getExpiringAtDate());
            $this->assertSame(14.5, $story->getVideoDuration());
            $this->assertCount(2, $story->getVideoResources());
            $this->assertCount(3, $story->getDisplayResources());
            $this->assertTrue($story->isAudio());
            $this->assertCount(1, $story->getHashtags());
            $this->assertCount(1, $story->getMentions());
            $this->assertCount(1, $story->getLocations());
        }

        $api->logout('username');
    }

    public function testHighlightsStoriesFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/highlights-folders.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/highlights-stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $profile = $api->getProfile('statement.paris');

        $storyHighlights = $api->getStoryHighlightsFolder($profile->getId());

        $this->assertCount(8, $storyHighlights->getFolders());

        $folder = $api->getStoriesOfHighlightsFolder($storyHighlights->getFolders()[0]);

        $this->assertSame(18137590444014024, $folder->getId());
        $this->assertSame(5670185369, $folder->getUserId());
        $this->assertSame('STORY', $folder->getName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/s150x150/94263786_546583649377430_3277795491247917640_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=D6Img4muLocAX_bsIlI&oh=eeeec52698961ee00a070d3e210f532d&oe=5EF1ACCB', $folder->getCover());
        $this->assertCount(33, $folder->getStories());
        $this->assertSame('https://www.instagram.com/s/aGlnaGxpZ2h0OjE4MTM3NTkwNDQ0MDE0MDI0', $folder->getUrl());
        $this->assertSame('https://www.instagram.com/s/aGlnaGxpZ2h0OjE4MTM3NTkwNDQ0MDE0MDI0?story_media_id=18137590444014024_5670185369&utm_medium=copy_link', $folder->getSharableUrl());

        $api->logout('username');
    }

    public function testHighlightsStoriesByIdFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/highlights-stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $storyFolder = $api->getStoriesOfHighlightsFolderById(12345);

        $this->assertCount(33, $storyFolder->getStories(false));

        $api->logout('username');
    }
}
