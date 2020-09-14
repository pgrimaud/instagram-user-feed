<?php

namespace Instagram\Tests\Utils;

use GuzzleHttp\Cookie\CookieJar;
use Instagram\Auth\Session;
use Instagram\Utils\Endpoints;
use PHPUnit\Framework\TestCase;

class EndpointsTest extends TestCase
{
    public function testGetFollowUrl()
    {
        $endpoint = Endpoints::getFollowUrl(123456789);
        $this->assertSame('https://www.instagram.com/web/friendships/123456789/follow/', $endpoint);
    }

    public function testGetUnfollowUrl()
    {
        $endpoint = Endpoints::getUnfollowUrl(123456789);
        $this->assertSame('https://www.instagram.com/web/friendships/123456789/unfollow/', $endpoint);
    }
}
