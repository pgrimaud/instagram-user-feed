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

    public function testGetLikeUrl()
    {
        $endpoint = Endpoints::getLikeUrl(123456789);
        $this->assertSame('https://www.instagram.com/web/likes/123456789/like/', $endpoint);
    }

    public function testGetUnlikeUrl()
    {
        $endpoint = Endpoints::getUnlikeUrl(123456789);
        $this->assertSame('https://www.instagram.com/web/likes/123456789/unlike/', $endpoint);
    }

    public function testGetLocationUrl()
    {
        $endpoint = Endpoints::getLocationUrl(123456789);
        $this->assertSame('https://www.instagram.com/explore/locations/123456789/', $endpoint);
    }

    public function testGetLiveUrl()
    {
        $endpoint = Endpoints::getLiveUrl('pgrimaud');
        $this->assertSame('https://www.instagram.com/pgrimaud/live/?__a=1', $endpoint);
    }

    public function testGetCommentUrl()
    {
        $endpoint = Endpoints::getCommentUrl(123456789);
        $this->assertSame('https://www.instagram.com/web/comments/123456789/add/', $endpoint);
    }
    public function testGetProfileUrl()
    {
        $endpoint = Endpoints::getProfileUrl(123456789);
        $this->assertSame('https://i.instagram.com/api/v1/users/123456789/info/', $endpoint);
    }
}
