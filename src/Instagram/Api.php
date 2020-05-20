<?php

declare(strict_types=1);

namespace Instagram;

use GuzzleHttp\Client;
use Instagram\Hydrator\ProfileHydrator;
use Instagram\Transport\HtmlTransportFeed;
use GuzzleHttp\Cookie\{SetCookie, CookieJar};
use Instagram\Auth\{Login, Session};
use Instagram\Exception\{InstagramAuthException, InstagramException};
use Psr\Cache\{CacheItemPoolInterface, InvalidArgumentException};

class Api
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param CacheItemPoolInterface $cachePool
     * @param Client|null $client
     */
    public function __construct(CacheItemPoolInterface $cachePool, Client $client = null)
    {
        $this->cachePool = $cachePool;
        $this->client    = $client ?: new Client();
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @throws InstagramException
     */
    public function login(string $login, string $password): void
    {
        $login = new Login($this->client, $login, $password);

        try {
            $sessionData = $this->cachePool->getItem('instagram.session');
            /** @var CookieJar $cookies */
            $cookies = $sessionData->get();
        } catch (InvalidArgumentException $exception) {
            throw new InstagramException($exception->getMessage());
        }

        /** @var SetCookie */
        $session = $cookies->getCookieByName('sessionId');
        if ($session->getExpires() < time()) {
            throw new InstagramException('Session expired. Please login again');
        }

        if (!$cookies instanceof CookieJar) {
            try {
                $cookies = $login->process();
                $sessionData->set($cookies);
                $this->cachePool->save($sessionData);
            } catch (InstagramAuthException $exception) {
                throw new InstagramException($exception->getMessage());
            }
        }

        $this->session = new Session($cookies);
    }

    /**
     * @param string $string
     *
     * @throws InstagramException
     */
    public function getProfile(string $string)
    {
        $feed = new HtmlTransportFeed($this->session, $this->client);
        try {
            $data = $feed->fetchData($string, 'profile');
        } catch (Exception\InstagramFetchException $exception) {
            throw new InstagramException($exception->getMessage());
        }

        $hydrator = new ProfileHydrator($data);

        return $hydrator->getProfile();
    }
}
