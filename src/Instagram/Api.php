<?php

declare(strict_types=1);

namespace Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Instagram\Auth\Login;
use Instagram\Exception\{InstagramAuthException, InstagramException};
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

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
     * @throws InstagramException
     */
    public function login(string $login, string $password)
    {
        $login = new Login($this->client, $login, $password);

        try {
            $sessionData = $this->cachePool->getItem('instagram.session');
            $cookies     = $sessionData->get();
        } catch (InvalidArgumentException $exception) {
            throw new InstagramException($exception->getMessage());
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

    }
}
