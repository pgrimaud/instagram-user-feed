<?php

declare(strict_types=1);

namespace Instagram\Utils;

class Endpoints
{
    const FOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/follow/';
    const UNFOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/unfollow/';

    /**
     * @param int $accountId
     *
     * @return string
     */
    public static function getFollowUrl(int $accountId): string
    {
        return str_replace('{{accountId}}', $accountId, static::FOLLOW_URL);
    }

    /**
     * @param int $accountId
     *
     * @return string
     */
    public static function getUnfollowUrl(int $accountId): string
    {
        return str_replace('{{accountId}}', $accountId, static::UNFOLLOW_URL);
    }
}
