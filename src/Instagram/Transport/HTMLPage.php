<?php

namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;

class HTMLPage
{
    const INSTAGRAM_ENDPOINT = 'https://www.instagram.com/';

    /**
     * @var Client
     */
    private $client;

    /**
     * HTMLPage constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client  = $client;
    }

    /**
     * @param string $userName
     * @return mixed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchData($userName)
    {
        $endpoint = self::INSTAGRAM_ENDPOINT . "$userName/";

        $res = $this->client->request('GET', $endpoint);

        $html = (string)$res->getBody();
        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);
        if(!isset($matches[1])) {
            throw new InstagramException('Unable to extract JSON data');
        }
        $data = json_decode($matches[1]);

        if ($data === null) {
            throw new InstagramException(json_last_error_msg());
        }

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
