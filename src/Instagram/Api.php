<?php
namespace Instagram;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;
use Instagram\Transport\JsonFeed;

class Api
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $userName = '';

    /**
     * Api constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     * @throws InstagramException
     */
    public function getFeed()
    {
        if (!$this->userName) {
            throw new InstagramException();
        }

        $rss  = new JsonFeed($this->client, $this->userName);
        $data = $rss->fetch();

        $hydrator = new Hydrator();
        $hydrator->setData($data);

        return $hydrator->getHydratedData();
    }
}
