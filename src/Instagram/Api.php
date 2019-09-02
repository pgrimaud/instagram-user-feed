<?php

namespace Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Instagram\Auth\Login;
use Instagram\Exception\InstagramCacheException;
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\HtmlHydrator;
use Instagram\Hydrator\JsonHydrator;
use Instagram\Storage\CacheManager;
use Instagram\Transport\HtmlTransportFeed;
use Instagram\Transport\JsonTransportFeed;

class Api
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $endCursor = null;

    /**
     * Api constructor.
     *
     * @param Client|null       $client
     * @param CacheManager|null $cacheManager
     */
    public function __construct(CacheManager $cacheManager = null, Client $client = null)
    {
        $this->cacheManager = $cacheManager;
        $this->client       = $client ?: new Client();
    }

    /**
     * @param integer $limit
     *
     * @return Hydrator\Component\Feed
     *
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getFeed($limit = 12)
    {
        if (empty($this->userName)) {
            throw new InstagramException('Username cannot be empty');
        }

        if ($this->endCursor) {
            if (!$this->cacheManager instanceof CacheManager) {
                throw new InstagramCacheException('CacheManager object must be specified to use pagination');
            }

            $feed     = new JsonTransportFeed($this->client, $this->endCursor, $this->cacheManager);
            $hydrator = new JsonHydrator();
        } else {
            $feed     = new HtmlTransportFeed($this->client, $this->cacheManager);
            $hydrator = new HtmlHydrator();
        }

        $dataFetched = $feed->fetchData($this->userName, $limit);

        $hydrator->setData($dataFetched);

        return $hydrator->getHydratedData();
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
     * @param             $username
     * @param             $password
     * @param Client|null $client
     *
     * @throws Exception\InstagramAuthException
     * @throws InstagramCacheException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login($username, $password, Client $client = null)
    {
        if (!$this->cacheManager instanceof CacheManager) {
            throw new InstagramCacheException('CacheManager is required with login');
        }

        $login   = new Login($client);
        $cookies = $login->execute($username, $password);

        if ($cookies instanceof CookieJar) {
            $this->cacheManager->sessionName = $username;
            $this->cacheManager->setSession($username, $cookies);
        }
    }
}
