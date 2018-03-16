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
    private $clientUser = null;

    /**
     * @var Client
     */
    private $clientMedia = null;

    /**
     * @var string
     */
    private $userName = null;

    /**
     * @var integer
     */
    private $userId = null;

    /**
     * @var integer
     */
    private $maxId = false;

    /**
     * Api constructor.
     * @param Client|null $clientUser
     * @param Client|null $clientMedia
     */
    public function __construct(Client $clientUser = null, Client $clientMedia = null)
    {
        $this->clientUser  = $clientUser ?: new Client();
        $this->clientMedia = $clientMedia ?: new Client();
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
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param bool $fetchMedia
     * @param bool $fetchUser
     * @return Hydrator\Feed
     * @throws InstagramException
     */
    public function getFeed($fetchMedia = true, $fetchUser = false)
    {
        if (!$this->userName && !$this->userId) {
            throw new InstagramException('Missing userName or userId');
        }

        if ($fetchUser && !$this->userName) {
            throw new InstagramException('You must specify a userName to retrieve userData');
        }

        if (($fetchMedia || $this->maxId) && !$this->userId) {
            throw new InstagramException('You must specify a userId to retrieve mediaData');
        }

        $feed     = new JsonFeed($this->clientUser, $this->clientMedia);
        $hydrator = new Hydrator();

        if ($fetchUser) {
            $userDataFetched = $feed->fetchUserData($this->userName);
            $hydrator->setUserData($userDataFetched);
        }

        if ($fetchMedia) {
            $mediaDataFetched = $feed->fetchMediaData($this->userId, $this->maxId);
            $hydrator->setMediaData($mediaDataFetched);
        }

        return $hydrator->getHydratedData();
    }
}
