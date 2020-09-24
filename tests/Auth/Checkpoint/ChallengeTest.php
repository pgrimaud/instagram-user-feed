<?php

namespace Instagram\Tests\Auth\Checkpoint;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Instagram\Auth\Checkpoint\Challenge;
use Instagram\Exception\InstagramException;
use PHPUnit\Framework\TestCase;

class ChallengeTest extends TestCase
{
    public function testTriggerCheckpointChallenge()
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/checkpoint/challenge-content.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/checkpoint/send-email.json')),
            new Response(200, [], ''), // force resend code, balec of content
            new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/checkpoint/challenge-done.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $cookie = new SetCookie();
        $cookie->setName('mid');
        $cookie->setValue('cookieValue');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');

        $cookieJar = new CookieJar();
        $cookieJar->setCookie($cookie);

        $challenge = new Challenge($client, $cookieJar, '/challenge/123456/LAZDZAD/', 0);

        $challengeContent = $challenge->fetchChallengeContent();
        $challenge->sendSecurityCode($challengeContent);
        $challenge->reSendSecurityCode($challengeContent);

        $challenge->submitSecurityCode($challengeContent, '123456');

        $this->assertTrue(true);
    }

    public function testSubmitWrongCode()
    {
        $this->expectException(InstagramException::class);

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/checkpoint/challenge-content.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/checkpoint/send-email.json')),
            new Response(200, [], ''), // force resend code, balec of content
            new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/checkpoint/challenge-error.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $cookie = new SetCookie();
        $cookie->setName('mid');
        $cookie->setValue('cookieValue');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');

        $cookieJar = new CookieJar();
        $cookieJar->setCookie($cookie);

        $challenge = new Challenge($client, $cookieJar, '/challenge/123456/LAZDZAD/', 0);

        $challengeContent = $challenge->fetchChallengeContent();
        $challenge->sendSecurityCode($challengeContent);
        $challenge->reSendSecurityCode($challengeContent);

        $challenge->submitSecurityCode($challengeContent, '123456');

        $this->assertTrue(true);
    }
}
