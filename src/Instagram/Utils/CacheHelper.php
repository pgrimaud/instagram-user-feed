<?php

declare(strict_types=1);

namespace Instagram\Utils;

class CacheHelper
{
    /**
     * @param string $username
     *
     * @return string
     */
    public static function sanitizeUsername(string $username)
    {
        return str_replace([
            '@', '.'
        ], [
            'at', 'dot'
        ], $username);
    }
}
