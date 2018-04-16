<?php

namespace Instagram;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;
use Instagram\Transport\HTMLPage;

class Api
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * Api constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @param string $username
     * @return Hydrator\Feed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFeed($username)
    {
        if(empty($username)) {
            throw new InstagramException('username cannot be empty');
        }
        $feed     = new HTMLPage($this->client);
        $hydrator = new Hydrator();

        $dataFetched = $feed->fetchData($username);
        $hydrator->setData($dataFetched);

        return $hydrator->getHydratedData();
    }
}
