<?php

declare(strict_types=1);

namespace Instagram\Utils;

class Endpoints
{
    const FOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/follow/';
    const UNFOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/unfollow/';

    public static function getFollowUrl(int $accountId): string
    {
        $url = str_replace('{{accountId}}', $accountId, static::FOLLOW_URL);

        return $url;
    }

    public static function getUnfollowUrl(int $accountId): string
    {
        $url = str_replace('{{accountId}}', $accountId, static::UNFOLLOW_URL);

        return $url;
    }
}