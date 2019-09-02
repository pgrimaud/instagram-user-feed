<?php

namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Storage\CacheManager;

abstract class TransportFeed
{
    const BASE_URL   = 'https://www.instagram.com/';
    const AUTH_URL   = 'https://www.instagram.com/accounts/login/ajax/';
    const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36';
    const QUERY_HASH = '42323d64886122307be10013ad2dcc44';

    /**
     * @var CacheManager|null
     */
    protected $cacheManager;

    /**
     * @var Client
     */
    protected $client;

    /**
     * TransportFeed constructor.
     *
     * @param Client            $client
     * @param CacheManager|null $cacheManager
     */
    public function __construct(Client $client, CacheManager $cacheManager = null)
    {
        $this->cacheManager = $cacheManager;
        $this->client       = $client;
    }
}
