<?php

declare(strict_types=1);

namespace Instagram\Utils;

class Endpoints
{
    const FOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/follow/';
    const UNFOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/unfollow/';

    const LIKE_URL = 'https://www.instagram.com/web/likes/{{postId}}/like/';
    const UNLIKE_URL = 'https://www.instagram.com/web/likes/{{postId}}/unlike/';

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

    /**
     * @param int $postId
     *
     * @return string
     */
    public static function getLikeUrl(int $postId): string
    {
        return str_replace('{{postId}}', $postId, static::LIKE_URL);
    }

    /**
     * @param int $postId
     *
     * @return string
     */
    public static function getUnlikeUrl(int $postId): string
    {
        return str_replace('{{postId}}', $postId, static::UNLIKE_URL);
    }
}
