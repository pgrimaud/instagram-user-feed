<?php

declare(strict_types=1);

namespace Instagram\Utils;

class InstagramHelper
{
    const URL_IG = 'https://www.instagram.com';
    /** @todo IMPROVE ME LATER HEEHH */
    const URL_BASE = 'https://www.instagram.com/';
    const URL_API_BASE = 'https://i.instagram.com/';
    const URL_AUTH = 'https://www.instagram.com/accounts/login/ajax/';
    const URL_MEDIA_DETAILED = 'https://www.instagram.com/p/';

    const QUERY_HASH_PROFILE = 'c9100bf9110dd6361671f113dd02e7d6';
    const QUERY_HASH_MEDIAS = '42323d64886122307be10013ad2dcc44';
    const QUERY_HASH_IGTVS = 'bc78b344a68ed16dd5d7f264681c4c76';
    const QUERY_HASH_STORIES = '5ec1d322b38839230f8e256e1f638d5f';
    const QUERY_HASH_HIGHLIGHTS_FOLDERS = 'ad99dd9d3646cc3c0dda65debcd266a7';
    const QUERY_HASH_HIGHLIGHTS_STORIES = '5ec1d322b38839230f8e256e1f638d5f';
    const QUERY_HASH_FOLLOWERS = 'c76146de99bb02f6415203be841dd25a';
    const QUERY_HASH_FOLLOWINGS = 'd04b0a864b4b54837c0d870b0e77e076';
    const QUERY_HASH_HASHTAG = '174a5243287c5f3a7de741089750ab3b';
    const QUERY_HASH_COMMENTS = '33ba35852cb50da46f5b5e889df7d159';
    const QUERY_HASH_TAGGED_MEDIAS = 'be13233562af2d229b008d2976b998b5';

    const PAGINATION_DEFAULT = 12;
    const PAGINATION_DEFAULT_FIRST_FOLLOW = 24;

    /**
     * @param string|null $caption
     * @return array
     *
     * @codeCoverageIgnore
     */
    static function buildHashtags(?string $caption): array
    {
        if ($caption) {
            preg_match_all('/(?<!\w)#\w+/u', $caption, $allMatches);

            return reset($allMatches);
        } else {
            return [];
        }
    }

    /**
     * @param int $id
     *
     * @return string
     */
    static function getCodeFromId($id)
    {
        $parts = explode('_', (string)$id);

        $id = $parts[0];
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $code = '';

        while ($id > 0) {
            $remainder = $id % 64;
            $id = ($id - $remainder) / 64;
            $code = $alphabet[$remainder] . $code;
        }

        return $code;
    }
}
