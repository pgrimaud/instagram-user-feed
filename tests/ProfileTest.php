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

class ProfileTest extends TestCase
{
    use GenerateCookiesTrait;

    public function testValidApiCalls()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame(1518284433, $profile->getId());
        $this->assertSame('1518284433', $profile->getId32Bit());
        $this->assertSame('robertdowneyjr', $profile->getUserName());
        $this->assertSame('Robert Downey Jr. Official', $profile->getFullName());
        $this->assertSame('Get involved: @officialfootprintcoalition 🌎🙏', $profile->getBiography());
        $this->assertSame(52979346, $profile->getFollowers());
        $this->assertSame(5, $profile->getFollowing());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/143237481_227994098925572_6634984787450078090_n.jpg?stp=dst-jpg_s320x320&_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=6QVB3trbfacAX9Ha8m4&edm=AOQ1c0wBAAAA&ccb=7-5&oh=00_AT8_gOYMGLxeiLssqUOIgQQKIvlD87hJsHYVGAS7gJc_Mg&oe=629FFA81&_nc_sid=8fd12b', $profile->getProfilePicture());
        $this->assertSame(null, $profile->getExternalUrl());
        $this->assertFalse($profile->isPrivate());
        $this->assertTrue($profile->isVerified());
        $this->assertSame(419, $profile->getMediaCount());
        $this->assertTrue($profile->hasMoreMedias());
        $this->assertCount(12, $profile->getMedias());
        $this->assertSame(null, $profile->getMedias()[0]->getAccessibilityCaption());

        $this->assertSame(1518284433, $profile->__serialize()['id']);
        $this->assertSame('robertdowneyjr', $profile->__serialize()['userName']);
        $this->assertSame('Robert Downey Jr. Official', $profile->__serialize()['fullName']);
        $this->assertSame('Get involved: @officialfootprintcoalition 🌎🙏', $profile->__serialize()['biography']);
        $this->assertSame(52979346, $profile->__serialize()['followers']);
        $this->assertSame(5, $profile->__serialize()['following']);
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/143237481_227994098925572_6634984787450078090_n.jpg?stp=dst-jpg_s320x320&_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=6QVB3trbfacAX9Ha8m4&edm=AOQ1c0wBAAAA&ccb=7-5&oh=00_AT8_gOYMGLxeiLssqUOIgQQKIvlD87hJsHYVGAS7gJc_Mg&oe=629FFA81&_nc_sid=8fd12b', $profile->__serialize()['profilePicture']);
        $this->assertSame(null, $profile->__serialize()['externalUrl']);
        $this->assertFalse($profile->__serialize()['private']);
        $this->assertTrue($profile->__serialize()['verified']);
        $this->assertSame(419, $profile->__serialize()['mediaCount']);
        $this->assertTrue($profile->__serialize()['hasMoreMedias']);
        $this->assertCount(12, $profile->__serialize()['medias']);

        $profile = $api->getMoreMedias($profile);
        $media   = $profile->getMedias()[0];

        $this->assertSame(2224305748263047050, $media->getId());
        $this->assertSame('B7eUksNFA-K', $media->getShortCode());
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
        $this->assertTrue($media->isVideo());
        $this->assertFalse($media->isIgtv());
        $this->assertSame(2726827, $media->getVideoViewCount());
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/c157.0.405.405a/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=72390bf5e7b875de6d6b7222337bb46e&oe=5EC7F96E', $media->getThumbnailSrc());
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t50.2886-16/83335079_254957552139702_725315034130023361_n.mp4?efg=eyJ2ZW5jb2RlX3RhZyI6InZ0c192b2RfdXJsZ2VuLjcyMC5mZWVkIiwicWVfZ3JvdXBzIjoiW1wiaWdfd2ViX2RlbGl2ZXJ5X3Z0c19vdGZcIl0ifQ&_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=106&_nc_ohc=SKlxV9v5K-MAX86Q58R&vs=17865910405614045_1706490228&_nc_vs=HBksFQAYJEdLZVg5d1MyLVZQdTRlY0FBTUVEa2o4dTFoQUtia1lMQUFBRhUAAsgBABUAGCRHTmppLXdRaWVxb3dDNGtGQUZRTnYyNVdTSUVUYmtZTEFBQUYVAgLIAQAoABgAGwGIB3VzZV9vaWwBMBUAABgAFrq6n%2FPsvLw%2FFQIoAkMzLBdAPMQYk3S8ahgSZGFzaF9iYXNlbGluZV8xX3YxEQB16gcA&_nc_rid=91150bc2b9&oe=5EC805C4&oh=4d13b190d246a46430b05fe1da4b1fa8', $media->getVideoUrl());

        $this->assertSame(2224305748263047050, $media->__serialize()['id']);
        $this->assertSame('GraphVideo', $media->__serialize()['typeName']);
        $this->assertSame(421, $media->__serialize()['height']);
        $this->assertSame(750, $media->__serialize()['width']);
        $this->assertSame('https://www.instagram.com/p/B7eUksNFA-K/', $media->__serialize()['link']);
        $this->assertInstanceOf(\DateTime::class, $media->__serialize()['date']);
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=725b6b887273f8402ea4d448abd456f9&oe=5EC809A6', $media->__serialize()['displaySrc']);
        $this->assertSame('#happybirthday little brother @harrycollettactor , quite the night out in #berlin #germany with #stubbins and #ladyrose @carmellaniadoofficial @dolittlemovie #press tour #trudges on... (🎥 @jimmy_rich ) #sweet #16', $media->__serialize()['caption']);
        $this->assertSame(1939, $media->__serialize()['comments']);
        $this->assertSame(695047, $media->__serialize()['likes']);
        $this->assertCount(5, $media->__serialize()['thumbnails']);
        $this->assertInstanceOf(\StdClass::class, $media->__serialize()['location']);
        $this->assertTrue($media->__serialize()['video']);
        $this->assertFalse($media->__serialize()['igtv']);
        $this->assertSame(2726827, $media->__serialize()['videoViewCount']);
        $this->assertSame('https://scontent-frt3-1.cdninstagram.com/v/t51.2885-15/e35/c157.0.405.405a/81891490_817416122018719_3074772560002831394_n.jpg?_nc_ht=scontent-frt3-1.cdninstagram.com&_nc_cat=107&_nc_ohc=pInBTStlOVIAX_wSuVO&oh=72390bf5e7b875de6d6b7222337bb46e&oe=5EC7F96E', $media->__serialize()['thumbnailSrc']);
        $this->assertCount(9, $media->getHashtags());
        $this->assertSame('#happybirthday', $media->getHashtags()[0]);
        $this->assertSame(1518284433, $media->getOwnerId());

        $api->logout('username');
    }

    public function testJsonMediasWithError()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias-error.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');
        $api->getMoreMedias($profile);

        $api->logout('username');
    }

    public function testProfileFetchWithNoContentInside()
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
        $api->getProfile('robertdowneyjr');

        $api->logout('username');
    }

    public function testProfileFetchWithNoValidJsonInside()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], '<script type="text/javascript">window._sharedData = {invalid};</script>'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('robertdowneyjr');

        $api->logout('username');
    }

    public function testGetMoreMediasWithEndCursor()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        // dummy cookie
        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(2621543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar = new CookieJar();
        $cookiesJar->setCookie($cookie);

        $cacheItem = $cachePool->getItem(Session::SESSION_KEY . '.username');
        $cacheItem->set($cookiesJar);
        $cachePool->save($cacheItem);

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);
        $api->login('username', 'password');
        $profile = $api->getMoreMediasWithCursor(12345567, 'endCursorVeryLongString');

        $medias = $profile->getMedias();
        $this->assertCount(12, $medias);

        $api->logout('username');
    }

    public function testGetMediaDetailed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-2.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-3.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $media = new Media();
        $media->setLink('https://www.instagram.com/p/CAnqPB-Jzcj/');

        $mediaDetailed = $api->getMediaDetailed($media);

        $this->assertFalse($mediaDetailed->hasAudio());
        $this->assertNull($mediaDetailed->getVideoUrl());
        $this->assertCount(0, $mediaDetailed->getTaggedUsers());
        $this->assertCount(3, $mediaDetailed->getSideCarItems());
        $this->assertCount(14, $mediaDetailed->getDisplayResources());

        $media->setLink('https://www.instagram.com/p/CAG3E8-A3wf/');

        $mediaDetailed = $api->getMediaDetailed($media);

        $this->assertTrue($mediaDetailed->hasAudio());
        $this->assertSame('https://scontent-arn2-1.cdninstagram.com/v/t50.2886-16/97123416_266521251063435_4033037212785927152_n.mp4?efg=eyJ2ZW5jb2RlX3RhZyI6InZ0c192b2RfdXJsZ2VuLjcyMC5mZWVkLmRlZmF1bHQiLCJxZV9ncm91cHMiOiJbXCJpZ193ZWJfZGVsaXZlcnlfdnRzX290ZlwiXSJ9&_nc_ht=scontent-arn2-1.cdninstagram.com&_nc_cat=103&_nc_ohc=Qrb1mQxsWqQAX8p4YGF&edm=AABBvjUBAAAA&vs=17845745546134702_3610077759&_nc_vs=HBksFQAYJEdGajh5UVdMcG41UVp2SUFBUEJqQ1NMNk9mZzNia1lMQUFBRhUAAsgBABUAGCRHR1hQeEFWUlZQeUx6amdEQU5aa2tvTVdESzh3YmtZTEFBQUYVAgLIAQAoABgAGwGIB3VzZV9vaWwBMBUAACbcq87WjKezPxUCKAJDMywXQDBEGJN0vGoYEmRhc2hfYmFzZWxpbmVfMV92MREAdeoHAA%3D%3D&_nc_rid=a997aa2dad&ccb=7-4&oe=622EC877&oh=00_AT_lfy7tuGTWv80Ru8Ha9dZk7TBAToiCCJwCmHmac5_pLQ&_nc_sid=83d603', $mediaDetailed->getVideoUrl());
        $this->assertCount(2, $mediaDetailed->getTaggedUsers());
        $this->assertCount(0, $mediaDetailed->getSideCarItems());
        $this->assertCount(12, $mediaDetailed->getDisplayResources());

        $media->setLink('https://www.instagram.com/p/B-NYjoGpQqC/');

        $mediaDetailed = $api->getMediaDetailed($media);

        $this->assertFalse($mediaDetailed->hasAudio());
        $this->assertCount(0, $mediaDetailed->getTaggedUsers());
        $this->assertCount(4, $mediaDetailed->getSideCarItems());
        $this->assertCount(10, $mediaDetailed->getDisplayResources());

        $api->logout('username');
    }

    public function testProfileFetchWithNoContentInAdditionalData()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-additional-data-invalid.html')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('robertdowneyjr');

        $this->assertSame('robertdowneyjr', $profile->getUserName());

        $api->logout('username');
    }

    public function testGetProfileById()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-id.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $profile = $api->getProfileById(12345);
        $this->assertSame('robertdowneyjr', $profile->getUserName());

        $api->logout('username');
    }

    public function testGetProfileByIdWithInvalidId()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-id-invalid.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfileById(12345);

        $api->logout('username');
    }

    public function testGetMediaDetailedByShortCode()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $media = new Media();
        $media->setShortCode('CAnqPB-Jzcj');

        $mediaDetailed = $api->getMediaDetailedByShortCode($media);
        $this->assertSame(2317006284167526179, $mediaDetailed->getId());
        
        $this->assertNotNull($mediaDetailed->getProfile());

        $api->logout('username');
    }

    public function testNotFoundOnProfile()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(404, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('odazdozajdoazjdazodjzaodazjdazod');

        $api->logout('username');
    }

    public function testInternalErrorOnProfile()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(429, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfile('odazdozajdoazjdazodjzaodazjdazod');

        $api->logout('username');
    }

    public function testGetMoreProfileById()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/medias.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $profile = $api->getMoreMediasWithProfileId(12345);
        $this->assertSame(12345, $profile->getId());

        $api->logout('username');
    }

    public function testIgtvFetchFeed()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/igtv.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $profile = $api->getProfile('test');

        $profile = $api->getMoreIgtvs($profile);
        $igtvs = $profile->getIgtvs();

        $this->assertCount(12, $igtvs);

        $this->assertSame('QVFCN0wyU1FnVXBfSkhVRmlOLTZTSDdvRHZzZTc5X3hIdU10NkhzTm5WWFZDYkd1QlRUZDBEUVFhZmdZSnJOcHZQN1Y1N29KQmItcGc1UXVKR3J2VDhyQg==', $profile->getEndCursorIgtvs());
        $this->assertTrue($profile->hasMoreIgtvs());

        $api->logout('username');
    }

    public function testProfileAlternativeFeed()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/profile-alternative.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($this->generateCookiesForFollow(), $client);

        $api->login('username', 'password');
        $profile = $api->getProfileAlternative(12345678);

        $this->assertSame(1518284433, $profile->getId());
        $this->assertSame('https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/143237481_227994098925572_6634984787450078090_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=XLHUTgFQFyMAX8ZQ_xh&edm=AEF8tYYBAAAA&ccb=7-4&oh=d97910fcfa8d73e4da653d56773f2f2f&oe=614FCC01&_nc_sid=a9513d', $profile->getProfilePicture());

        $api->logout('username');
    }

    public function testErrorOnProfileAlternativeFeed()
    {
        $this->expectException(InstagramFetchException::class);

        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(429, [], file_get_contents(__DIR__ . '/fixtures/profile-alternative.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');
        $api->getProfileAlternative(1234);

        $api->logout('username');
    }

    public function testEmptyJsonOnProfileAlternativeFeed()
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
        $api->getProfileAlternative(1234);

        $api->logout('username');
    }
}
