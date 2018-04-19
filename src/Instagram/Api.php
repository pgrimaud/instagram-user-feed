<?php

namespace Instagram;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\HtmlHydrator;
use Instagram\Hydrator\JsonHydrator;
use Instagram\Storage\CacheManager;
use Instagram\Transport\HtmlTransportFeed;
use Instagram\Transport\JsonTransportFeed;

class Api
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

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
     * @param CacheManager|null $cacheManager
     */
    public function __construct(CacheManager $cacheManager, Client $client = null)
    {
        $this->cacheManager = $cacheManager;
        $this->client       = $client ?: new Client();
    }

    /**
     * @return Hydrator\Component\Feed
     * @throws InstagramException
     */
    public function getFeed()
    {
        if (empty($this->userName)) {
            throw new InstagramException('Username cannot be empty');
        }

        if ($this->endCursor) {
            $feed     = new JsonTransportFeed($this->cacheManager, $this->client, $this->endCursor);
            $hydrator = new JsonHydrator();
        } else {
            $feed     = new HtmlTransportFeed($this->cacheManager, $this->client);
            $hydrator = new HtmlHydrator();
        }

        $dataFetched = $feed->fetchData($this->userName);

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
