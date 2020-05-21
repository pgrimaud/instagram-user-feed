<?php

namespace Instagram\Tests;

use GuzzleHttp\{Client, Handler\MockHandler, HandlerStack, Psr7\Response};
use Instagram\Api;

use Instagram\Exception\InstagramFetchException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApiTest extends TestCase
{
    public function testValidApiCalls()
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
        $this->assertSame('@officialfootprintcoalition @coreresponse', $profile->getBiography());
        $this->assertSame(46383825, $profile->getFollowers());
        $this->assertSame(50, $profile->getFollowing());
        $this->assertSame('https://scontent-frt3-2.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-frt3-2.cdninstagram.com&_nc_ohc=h2zGWoshNjUAX90AcTx&oh=ec27e20298c8765eccdfeb9c1b655f76&oe=5EEEC338', $profile->getProfilePicture());
        $this->assertSame('http://coreresponse.org/covid19', $profile->getExternalUrl());
        $this->assertSame(false, $profile->isPrivate());
        $this->assertSame(true, $profile->isVerified());
        $this->assertSame(453, $profile->getMediaCount());
        $this->assertSame(true, $profile->hasMoreMedias());
        $this->assertCount(12, $profile->getMedias());

        $profile = $api->getMoreMedias($profile);
        $media   = $profile->getMedias()[0];

        $this->assertSame(2224305748263047050, $media->getId());
        $this->assertSame('GraphVideo', $media->getTypeName());
        $this->assertSame(421, $media->getHeight());
        $this->assertSame(750, $media->getWidth());
        $this->assertSame('https://www.instagram.com/p/B7eUksNFA-K/', $media->getLink());
        $this->assertInstanceOf(\DateTime::class, $media->getDate());

        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=725b6b887273f8402ea4d448abd456f9&oe=5EC809A6', $media->getDisplaySrc());
        $this->assertSame('#happybirthday little brother @harrycollettactor , quite the night out in #berlin #germany with #stubbins and #ladyrose @carmellaniadoofficial @dolittlemovie #press tour #trudges on... (🎥 @jimmy_rich ) #sweet #16', $media->getCaption());
        $this->assertSame(1939, $media->getComments());
        $this->assertSame(695047, $media->getLikes());
        $this->assertCount(5, $media->getThumbnails());
        $this->assertInstanceOf(\StdClass::class, $media->getLocation());
        $this->assertSame(true, $media->isVideo());
        $this->assertSame(2726827, $media->getVideoViewCount());
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/c157.0.405.405a/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=72390bf5e7b875de6d6b7222337bb46e&oe=5EC7F96E', $media->getThumbnailSrc());

        $api->logout();
    }

    public function testJsonMediasWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-medias-error.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');
        $api->getMoreMedias($profile);

        $api->logout();
    }

    public function testProfileFetchWithNoContentInside()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');
        $api->getProfile('robertdowneyjr');

        $api->logout();
    }

    public function testProfileFetchWithNoValidJsonInside()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], '<script type="text/javascript">window._sharedData = {invalid};</script>'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');
        $api->getProfile('robertdowneyjr');

        $api->logout();
    }

    public function testValidStoriesFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');

        $instagramStories = $api->getStories(123456788);

        $this->assertInstanceOf(\DateTime::class, $instagramStories->getExpiringDate());
        $this->assertInstanceOf(\StdClass::class, $instagramStories->getOwner());
        $this->assertSame(false, $instagramStories->isAllowedToReply());
        $this->assertSame(true, $instagramStories->isReshareable());

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
            $this->assertSame(true, $story->isAudio());
        }

        $api->logout();
    }

    public function testHighlightsStoriesFetch()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/instagram-home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-profile.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-highlights-folders.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/instagram-highlights-stories.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout();

        $api->login('username', 'password');

        $profile = $api->getProfile('statement.paris');

        $storyHighlights = $api->getStoryHighlightsFolder($profile->getId());

        $this->assertCount(8, $storyHighlights->getFolders());

        $folder = $api->getStoriesOfHighlightsFolder($storyHighlights->getFolders()[0]);

        $this->assertSame(18137590444014024, $folder->getId());
        $this->assertSame('STORY', $folder->getName());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/s150x150/94263786_546583649377430_3277795491247917640_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=D6Img4muLocAX_bsIlI&oh=eeeec52698961ee00a070d3e210f532d&oe=5EF1ACCB', $folder->getCover());
        $this->assertCount(33, $folder->getStories());

        $api->logout();
    }
}
