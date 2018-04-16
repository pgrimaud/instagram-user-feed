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
     * @var string
     */
    private $endCursor = false;

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
     * @var string
     */
    private $accessToken = null;

    /**
     * Api constructor.
     * @param Client|null $clientUser
     * @param Client|null $clientMedia
     * @param null $queryHash
     */
    public function __construct(Client $clientUser = null, Client $clientMedia = null, $queryHash = null)
    {
        $this->clientUser = $clientUser ?: new Client();
        $this->clientMedia = $clientMedia ?: new Client();
        $this->queryHash = $queryHash;
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

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setAccessToken($token) 
    {
    	$this->accessToken = $token;
    }

    /**
     * @return Hydrator\Feed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFeed()
    {
        if (!$this->userId) {
            throw new InstagramException('Missing userName or userId');
        }

        if (!$this->accessToken) {
            throw new InstagramException('Missing access token');
        }

        /*if ($this->retrieveUserData && !$this->userName) {
            throw new InstagramException('You must specify a userName to retrieve userData');
        }*/

        if (($this->retrieveMediaData || $this->endCursor) && !$this->userId) {
            throw new InstagramException('You must specify a userId to retrieve mediaData');
        }

        $feed = new JsonFeed($this->clientUser, $this->clientMedia, $this->queryHash);
        $hydrator = new Hydrator();

        if ($this->retrieveUserData) {
            $userDataFetched = $feed->fetchUserData($this->userId, $this->accessToken);
            $hydrator->setUserData($userDataFetched);
        }

        if ($this->retrieveMediaData) {
            $mediaDataFetched = $feed->fetchMediaData($this->userId, $this->endCursor);
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