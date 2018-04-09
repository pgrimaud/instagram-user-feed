<?php
namespace Instagram\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Instagram\Exception\InstagramException;

class JsonFeed
{
    const INSTAGRAM_ENDPOINT   = 'https://www.instagram.com/';
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
     * @param null $queryHash
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
     * @param null $endCursor
     * @return mixed
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchMediaData($userId, $endCursor = null)
    {
        $endpoint = self::INSTAGRAM_ENDPOINT . 'graphql/query/?query_hash=' . $this->getQueryHash() . '&variables={"id":"' . $userId . '","first":"12"';


        if ($endCursor) {
            $endpoint .= ',"after":"' . $endCursor . '"';
        }

        $endpoint .= '}';

        $cookieJar = CookieJar::fromArray([
            'ig_pr' => '2'
        ], 'instagram.com');

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'cookies'    => $cookieJar
        ];

        $res = $this->clientMedia->request('GET', $endpoint, $headers);

        $json = (string)$res->getBody();
        $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!is_array($data)) {
            throw new InstagramException('Invalid JSON');
        }

        return $data['data']['user'];
    }
}
