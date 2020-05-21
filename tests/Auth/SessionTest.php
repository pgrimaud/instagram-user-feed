<?php

namespace Instagram\Tests\Auth;

use GuzzleHttp\Cookie\CookieJar;
use Instagram\Auth\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testSessionInitAndGetter()
    {
        $cookies = new CookieJar();
        $session = new Session($cookies);

        $this->assertSame($cookies, $session->getCookies());
    }
}
