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
     * @var integer
     */
    private $userId = null;

    /**
     * @var string
     */
    private $accessToken = null;

    /**
     * @var string
     */
    private $maxId = null;

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
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param $token
     */
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
            throw new InstagramException('Missing userId');
        }

        if (!$this->accessToken) {
            throw new InstagramException('Missing access token');
        }

        $feed     = new JsonFeed($this->clientUser, $this->clientMedia, $this->accessToken);
        $hydrator = new Hydrator();

        $userDataFetched = $feed->fetchUserData($this->userId);
        $hydrator->setUserData($userDataFetched);

        $mediaDataFetched = $feed->fetchMediaData($this->userId, $this->maxId);
        $hydrator->setMediaData($mediaDataFetched);

        return $hydrator->getHydratedData();
    }

    /**
     * @param string $maxId
     */
    public function setMaxId($maxId)
    {
        $this->maxId = $maxId;
    }
}
