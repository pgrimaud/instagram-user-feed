<?php

namespace Instagram;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;
use Instagram\Transport\HTMLPage;
use Instagram\Transport\JsonFeed;

class Api
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $endCursor = null;

    /**
     * Api constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @return Hydrator\Feed
     * @throws InstagramException
     */
    public function getFeed()
    {
        if (empty($this->userName)) {
            throw new InstagramException('Username cannot be empty');
        }

        if ($this->endCursor) {
            $feed = new JsonFeed($this->client, $this->endCursor);
        } else {
            $feed = new HTMLPage($this->client);
        }

        $dataFetched = $feed->fetchData($this->userName);

        $hydrator = new Hydrator();
        $hydrator->setData($dataFetched);

        return $hydrator->getHydratedData();
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @param string $endCursor
     */
    public function setEndCursor($endCursor)
    {
        $this->endCursor = $endCursor;
    }
}
