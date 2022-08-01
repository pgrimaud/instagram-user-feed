<?php

declare(strict_types=1);

namespace Instagram\Utils;

use GuzzleHttp\Psr7\Response;

class CacheResponse
{
    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    private static $response;

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return void
     */
     public static function setResponse(Response $response): void
     {
         self::$response = $response;
     }

    /**
     * @return \GuzzleHttp\Psr7\Response|null
     */
     public static function getResponse(): mixed
     {
         return self::$response;
     }
}
