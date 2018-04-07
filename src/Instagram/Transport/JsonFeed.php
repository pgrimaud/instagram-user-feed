<?php
namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;

class JsonFeed
{
    const INSTAGRAM_ENDPOINT = 'https://www.instagram.com/';
    const INSTAGRAM_QUERY_HASH = '472f257a40c653c64c666ce877d59d2b';

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
    private $queryHash;

    /**
     * JsonFeed constructor.
     * @param Client $clientUser
     * @param Client $clientMedia
     */
    public function __construct(Client $clientUser, Client $clientMedia, $queryHash = null)
    {
        $this->clientUser  = $clientUser;
        $this->clientMedia = $clientMedia;
        $this->queryHash   = $queryHash;
    }

    /**
     * @param $userName
     * @return mixed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchUserData($userName)
    {
        $endpoint = self::INSTAGRAM_ENDPOINT . $userName . '?__a=1';

        $res = $this->clientUser->request('GET', $endpoint);

        $json = (string)$res->getBody();
        $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!is_array($data)) {
            throw new InstagramException('Invalid JSON');
        }

        return $data['graphql']['user'];
    }

    private function getQueryHash()
    {
        return $this->queryHash ? $this->queryHash : self::INSTAGRAM_QUERY_HASH;
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
        $endpoint = self::INSTAGRAM_ENDPOINT . 'graphql/query/?query_hash=' . $this->getQueryHash() . '&first=12&id=' .
            $userId;

        if ($maxId) {
            $endpoint .= '&after=' . $maxId;
        }

        $res = $this->clientMedia->request('GET', $endpoint);

        $json = (string)$res->getBody();
        $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!is_array($data)) {
            throw new InstagramException('Invalid JSON');
        }

        return $data['data']['user'];
    }
}
