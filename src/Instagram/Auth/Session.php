<?php

declare(strict_types=1);

namespace Instagram\Auth;

use GuzzleHttp\Cookie\CookieJar;

class Session
{
    const SESSION_KEY = 'instagram.session';

    /**
     * @var CookieJar
     */
    private $cookies;

    /**
     * @param CookieJar $cookieJar
     */
    public function __construct(CookieJar $cookieJar)
    {
        $this->cookies = $cookieJar;
    }

    /**
     * @return CookieJar
     */
    public function getCookies(): CookieJar
    {
        return $this->cookies;
    }
}
