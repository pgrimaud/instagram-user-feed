<?php

namespace Instagram\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Instagram\Api;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CommentsTest extends TestCase
{
    public function testGetMediaComments()
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'cookie'], file_get_contents(__DIR__ . '/fixtures/home.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/login-success.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-comments.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-comments-2.json')),
            new Response(200, [], file_get_contents(__DIR__ . '/fixtures/media-comments.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $api = new Api($cachePool, $client);

        // clear cache
        $api->logout('username');

        $api->login('username', 'password');

        $comments = $api->getMediaComments('CIvZJcurJaW');
        $this->assertCount(12, $comments->getComments());
        $this->assertTrue($comments->hasMoreComments());
        $this->assertSame('QVFCbnROamtGemh0QWdoN0Z1YVhTNWtPV3RqSXhIVkJaaEszdUlYNlozSGlUVi1nbXdtbF93VUliVFYzaWM5a2prMGJDNmlLZTR2VHcwajNOZWVWR3Z0ZA==', $comments->getEndCursor());
        $this->assertSame(45, $comments->getMediaCount());

        $comment = $comments->getComments()[0];
        $this->assertSame(17969014687354566, $comment->getId());
        $this->assertSame('Good themw', $comment->getCaption());
        $this->assertSame('26965138089', $comment->getOwner()->id);
        $this->assertInstanceOf(\DateTime::class, $comment->getDate());
        $this->assertIsArray($comment->toArray());
        $this->assertSame(17969014687354566, $comment->__serialize()['id']);

        $comments = $api->getMoreMediaComments('CIvZJcurJaW', $comments->getEndCursor());
        $this->assertCount(12, $comments->getComments());

        $comments2 = $api->getMediaCommentsById(2463298121680852630);
        $this->assertCount(12, $comments2->getComments());

        $api->logout('username');
    }
}
