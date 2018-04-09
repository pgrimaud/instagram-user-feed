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
    private $cursor = false;

    /**
     * @var bool
     */
    private $retrieveMediaData = false;

    /**
     * @var bool
     */
    private $retrieveUserData = false;

    /**
     * @var string
     */
    private $queryHash = false;

    /**
     * Api constructor.
     * @param Client|null $clientUser
     * @param Client|null $clientMedia
     * @param null $queryHash
     */
    public function __construct(Client $clientUser = null, Client $clientMedia = null, $queryHash = null)
    {
        $this->clientUser  = $clientUser ?: new Client();
        $this->clientMedia = $clientMedia ?: new Client();
        $this->queryHash   = $queryHash;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @param string $cursor
     */
    public function setCursor($cursor)
    {
        $this->cursor = $cursor;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return Hydrator\Feed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFeed()
    {
        if (!$this->userName && !$this->userId) {
            throw new InstagramException('Missing userName or userId');
        }

        if ($this->retrieveUserData && !$this->userName) {
            throw new InstagramException('You must specify a userName to retrieve userData');
        }

        if (($this->retrieveMediaData || $this->cursor) && !$this->userId) {
            throw new InstagramException('You must specify a userId to retrieve mediaData');
        }

        $feed     = new JsonFeed($this->clientUser, $this->clientMedia, $this->queryHash);
        $hydrator = new Hydrator();

        if ($this->retrieveUserData) {
            $userDataFetched = $feed->fetchUserData($this->userName);
            $hydrator->setUserData($userDataFetched);
        }

        if ($this->retrieveMediaData) {
            $mediaDataFetched = $feed->fetchMediaData($this->userId, $this->cursor);
            $hydrator->setMediaData($mediaDataFetched);
        }

        return $hydrator->getHydratedData();
    }

    /**
     * @param bool $retrieveMediaData
     */
    public function retrieveMediaData($retrieveMediaData)
    {
        $this->retrieveMediaData = $retrieveMediaData;
    }

    /**
     * @param bool $retrieveUserData
     */
    public function retrieveUserData($retrieveUserData)
    {
        $this->retrieveUserData = $retrieveUserData;
    }

    /**
     * @param string $queryHash
     */
    public function setQueryHash($queryHash)
    {
        $this->queryHash = $queryHash;
    }
}
