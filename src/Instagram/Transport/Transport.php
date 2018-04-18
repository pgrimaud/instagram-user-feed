<?php

namespace Instagram\Transport;

use GuzzleHttp\Client;

abstract class Transport
{
    const INSTAGRAM_ENDPOINT = 'https://www.instagram.com/';
    const USER_AGENT         = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36';
    const QUERY_HASH         = '42323d64886122307be10013ad2dcc44';

    /**
     * @var Client
     */
    protected $client;

    /**
     * Transport constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

}