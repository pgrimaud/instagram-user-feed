<?php

namespace Instagram\Tests;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Instagram\Auth\Session;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait GenerateCookiesTrait
{
    protected function generateCookiesForFollow(): FilesystemAdapter
    {
        $cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/cache');

        $cookiesJar = new CookieJar();

        // dummy cookies
        $cookie = new SetCookie();
        $cookie->setName('csrftoken');
        $cookie->setValue('123456789');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');

        $cookiesJar->setCookie($cookie);

        $cookie = new SetCookie();
        $cookie->setName('sessionId');
        $cookie->setValue('123456789');
        $cookie->setExpires(1621543830);
        $cookie->setDomain('.instagram.com');
        $cookiesJar->setCookie($cookie);

        $cacheItem = $cachePool->getItem(Session::SESSION_KEY . '.username');
        $cacheItem->set($cookiesJar);
        $cachePool->save($cacheItem);

        return $cachePool;
    }
}