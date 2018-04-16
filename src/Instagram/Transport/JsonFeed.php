<?php

namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;

class JsonFeed
{
    const INSTAGRAM_ENDPOINT = 'https://api.instagram.com/v1/users/';

    /**
     * @var Client
     */
    private $clientUser;

    /**
     * @var Client
     */
    private $clientMedia;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * JsonFeed constructor.
     * @param Client $clientUser
     * @param Client $clientMedia
     * @param null $accessToken
     */
    public function __construct(Client $clientUser, Client $clientMedia, $accessToken = null)
    {
        $this->clientUser  = $clientUser;
        $this->clientMedia = $clientMedia;
        $this->accessToken = $accessToken;
    }

    /**
     * @param $userId
     * @return mixed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchUserData($userId)
    {
        $endpoint = self::INSTAGRAM_ENDPOINT . $userId . '?access_token=' . $this->accessToken;

        $res = $this->clientUser->request('GET', $endpoint);

        $json = (string)$res->getBody();
        $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!is_array($data)) {
            throw new InstagramException('Invalid JSON');
        }

        return $data['data'];
    }

    /**
     * @param $userId
     * @param null $maxId
     * @return mixed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchMediaData($userId, $maxId = null)
    {
        $endpoint = self::INSTAGRAM_ENDPOINT . $userId . '/media/recent/?access_token=' . $this->accessToken;

        if ($maxId) {
            $endpoint .= '&max_id=' . $maxId;
        }

        $res = $this->clientMedia->request('GET', $endpoint);

        $json = (string)$res->getBody();
        $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!is_array($data)) {
            throw new InstagramException('Invalid JSON');
        }

        return $data;
    }
}
