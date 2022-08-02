<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class TimelineTest extends TestCase
{
    use GenerateCookiesTrait;

    public function testValidTimelineFetch()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/timeline.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/timeline.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/timeline.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/timeline.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        $api->login('username', 'password');

        $timelineFeed = $api->getTimeline();

        $this->assertCount(12, $timelineFeed->getTimeline());
        $this->assertTrue($timelineFeed->hasMoreTimeline());
        $this->assertSame('KGEAReU-f8agKifHBbQo8T4qJ4yFuozqEConTT6FHc4VKifY8SaNeHQqJ99NuNQaGConYrO-huzAKicjvhc_PzEqJySfsjT-PionLboxQ5wiKyeuQb8y5Z8qJzZWMDYKFionFsr24sCKYEYCKQQZBCIA', $timelineFeed->getMaxId());

        $firstTimeline = current($timelineFeed->getTimeline());

        $this->assertSame("carousel", $firstTimeline->getType());

        $contentTimeline = $firstTimeline->getContent();

        $this->assertSame('2822243923248759214', $contentTimeline->getId());
        $this->assertSame('Ccqn-Uyv0Gu', $contentTimeline->getShortCode());
        $this->assertSame('https://www.instagram.com/p/Ccqn-Uyv0Gu/', $contentTimeline->getLink());
        $this->assertSame(660599, $contentTimeline->getLikes());
        $this->assertFalse($contentTimeline->isLiked());
        $this->assertSame(1092, $contentTimeline->getComments());
        $this->assertSame(960, $contentTimeline->getHeight());
        $this->assertSame(1440, $contentTimeline->getWidth());
        $this->assertCount(5, $contentTimeline->getCarousel());
        $this->assertSame('2822243917217328550', $contentTimeline->getCarousel()[0]->id);
        $this->assertSame('2822243923248759214_3514890218', $contentTimeline->getCarousel()[0]->parentId);
        $this->assertSame('image', $contentTimeline->getCarousel()[0]->type);
        $this->assertSame(1440, $contentTimeline->getCarousel()[0]->width);
        $this->assertSame(960, $contentTimeline->getCarousel()[0]->height);
        $this->assertCount(14, $contentTimeline->getCarousel()[0]->image);
        $this->assertSame('Photo by Marvel Studios on April 22, 2022. May be an image of 2 people and people standing.', $contentTimeline->getCarousel()[0]->accessibilityCaption);
        $this->assertSame('The trip through the Multiverse has begun 🌀 The stars of Marvel Studios’ Doctor Strange in the Multiverse of Madness began the global tour in Berlin, Germany! 🇩🇪 Experience the movie only in theaters May 6. Get Tickets Now: Link in Bio', $contentTimeline->getCaption());
        $this->assertSame(null, $contentTimeline->getLocation());
        $this->assertSame(1650657714, $contentTimeline->getDate()->getTimestamp());
        $this->assertCount(0, $contentTimeline->getHashtags());
        $this->assertCount(0, $contentTimeline->getUserTags());

        /** @var User $user */
        $user = $firstTimeline->getUser();

        $this->assertSame(3514890218, $user->getId());
        $this->assertSame('marvelstudios', $user->getUserName());
        $this->assertSame('Marvel Studios', $user->getFullName());
        $this->assertSame('https://instagram.fplm3-1.fna.fbcdn.net/v/t51.2885-19/14624721_319705655075007_2253184395177361408_a.jpg?stp=dst-jpg_s150x150&_nc_ht=instagram.fplm3-1.fna.fbcdn.net&_nc_cat=1&_nc_ohc=TPCC26ncgKYAX97zV7m&edm=AJ9x6zYBAAAA&ccb=7-4&oh=00_AT_5sVebUU-xWlsla3X8TtUo7SNktLeh2IkAXR9WzqAing&oe=626962B4&_nc_sid=cff2a4', $user->getProfilePicUrl());
        $this->assertFalse($user->isPrivate());
        $this->assertTrue($user->isVerified());

        $api->getTimeline($timelineFeed->getMaxId());

        $api->logout('username');
    }

    public function testGetTimelineWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(404, []),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getTimeline(1);
    }

    public function testGetTimelineWithInvalidJson()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');
        $api->login('username', 'password');

        $api->getTimeline(1);
    }

}
