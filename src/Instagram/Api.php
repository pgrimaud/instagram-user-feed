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
     * @var integer
     */
    private $maxId = false;

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
     * @param int $maxId
     */
    public function setMaxId($maxId)
    {
        $this->maxId = $maxId;
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

        $rss  = new JsonFeed($this->client, $this->userName, $this->maxId);
        $data = $rss->fetch();

        $hydrator = new Hydrator();
        $hydrator->setData($data);

        return $hydrator->getHydratedData();
    }
}
