<?php

declare(strict_types=1);

namespace Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\{SetCookie, CookieJar};
use Instagram\{Hydrator\InstagramHydrator, Model\InstagramProfile, Transport\JsonTransportFeed};
use Instagram\Transport\HtmlTransportFeed;
use Instagram\Auth\{Login, Session};
use Instagram\Exception\InstagramException;
use Psr\Cache\CacheItemPoolInterface;

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
    private $session = null;

    /**
     * @param CacheItemPoolInterface $cachePool
     * @param Client|null            $client
     */
    public function __construct(CacheItemPoolInterface $cachePool, Client $client = null)
    {
        $this->cachePool = $cachePool;
        $this->client    = $client ?: new Client();
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @throws Exception\InstagramAuthException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function login(string $username, string $password): void
    {
        $login = new Login($this->client, $username, $password);

        // fetch previous session an re-use it
        $sessionData = $this->cachePool->getItem(Session::SESSION_KEY);
        $cookies     = $sessionData->get();

        if ($cookies instanceof CookieJar) {
            /** @var SetCookie */
            $session = $cookies->getCookieByName('sessionId');

            // Session expired (should never happened, Instagram TTL is ~ 1 year)
            if ($session->getExpires() < time()) {
                $this->logout();
                $this->login($username, $password);
            }

        } else {
            $cookies = $login->process();
            $sessionData->set($cookies);
            $this->cachePool->save($sessionData);
        }

        $this->session = new Session($cookies);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function logout(): void
    {
        $this->cachePool->deleteItem(Session::SESSION_KEY);
    }

    /**
     * @param string $user
     *
     * @return InstagramProfile
     *
     * @throws InstagramException
     */
    public function getProfile(string $user): InstagramProfile
    {
        $feed = new HtmlTransportFeed($this->session, $this->client);
        $data = $feed->fetchData($user);

        $hydrator = new InstagramHydrator();
        $hydrator->hydrateProfile($data);
        $hydrator->hydrateMedias($data);

        return $hydrator->getProfile();
    }

    /**
     * @param InstagramProfile $instagramProfile
     *
     * @return InstagramProfile
     *
     * @throws InstagramException
     */
    public function getMoreMedias(InstagramProfile $instagramProfile): InstagramProfile
    {
        $feed = new JsonTransportFeed($this->session, $this->client);
        $data = $feed->fetchData($instagramProfile);

        $hydrator = new InstagramHydrator($instagramProfile);
        $hydrator->hydrateMedias($data);

        return $hydrator->getProfile();
    }
}
