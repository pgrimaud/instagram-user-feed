<?php
namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Exception\InstagramException;

class JsonFeed
{
    const INSTAGRAM_ENDPOINT = 'https://www.instagram.com/';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * FetchRss constructor.
     * @param Client $client
     * @param $userName
     */
    public function __construct(Client $client, $userName)
    {
        $this->client   = $client;
        $this->endpoint = self::INSTAGRAM_ENDPOINT . $userName . '?__a=1';
    }

    /**
     * @throws InstagramException
     */
    public function fetch()
    {
        $res = $this->client->request('GET', $this->endpoint);

        $json = (string)$res->getBody();
        $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!is_array($data)) {
            throw new InstagramException('Invalid JSON');
        }

        return $data['user'];
    }
}
