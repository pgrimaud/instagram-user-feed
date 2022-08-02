<?php

declare(strict_types=1);

namespace Instagram\Utils;

class Endpoints
{
    const FOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/follow/';
    const UNFOLLOW_URL = 'https://www.instagram.com/web/friendships/{{accountId}}/unfollow/';

    const LIKE_URL = 'https://www.instagram.com/web/likes/{{postId}}/like/';
    const UNLIKE_URL = 'https://www.instagram.com/web/likes/{{postId}}/unlike/';

    const LOCATION_URL = 'https://www.instagram.com/explore/locations/{{locationId}}/';

    const LIVE_URL = 'https://www.instagram.com/{{username}}/live/?__a=1';

    const REELS_URL = 'https://i.instagram.com/api/v1/clips/user/?hl=en';

    const PROFILE_URL = 'https://i.instagram.com/api/v1/users/{{userId}}/info/';

    const COMMENT_URL = 'https://www.instagram.com/web/comments/{{postId}}/add/';

    const TIMELINE_URL = 'https://i.instagram.com/api/v1/feed/timeline/';

    /**
     * @param int $accountId
     *
     * @return string
     */
    public static function getFollowUrl(int $accountId): string
    {
        return str_replace('{{accountId}}', (string) $accountId, static::FOLLOW_URL);
    }

    /**
     * @param int $accountId
     *
     * @return string
     */
    public static function getUnfollowUrl(int $accountId): string
    {
        return str_replace('{{accountId}}', (string) $accountId, static::UNFOLLOW_URL);
    }

    /**
     * @param int $postId
     *
     * @return string
     */
    public static function getLikeUrl(int $postId): string
    {
        return str_replace('{{postId}}', (string) $postId, static::LIKE_URL);
    }

    /**
     * @param int $postId
     *
     * @return string
     */
    public static function getUnlikeUrl(int $postId): string
    {
        return str_replace('{{postId}}', (string) $postId, static::UNLIKE_URL);
    }

    /**
     * @param int $locationId
     *
     * @return string
     */
    public static function getLocationUrl(int $locationId): string
    {
        return str_replace('{{locationId}}', (string) $locationId, static::LOCATION_URL);
    }

    /**
     * @param string $username
     *
     * @return string
     */
    public static function getLiveUrl(string $username): string
    {
        return str_replace('{{username}}', $username, static::LIVE_URL);
    }

    /**
     * @param int $userId
     *
     * @return string
     */
    public static function getProfileUrl(int $userId): string
    {
        return str_replace('{{userId}}', (string) $userId, static::PROFILE_URL);
    }

    /**
     * @param int $postId
     *
     * @return string
     */
    public static function getCommentUrl(int $postId): string
    {
        return str_replace('{{postId}}', (string) $postId, static::COMMENT_URL);
    }
}
