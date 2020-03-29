<?php

namespace Instagram\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Instagram\Exception\InstagramAuthException;
use Instagram\Exception\InstagramException;
use Instagram\Storage\Cache;
use Instagram\Storage\CacheManager;

class HtmlTransportFeed extends TransportFeed
{
    /**
     * HtmlTransportFeed constructor.
     *
     * @param Client            $client
     * @param CacheManager|null $cacheManager
     */
    public function __construct(Client $client, CacheManager $cacheManager = null)
    {
        parent::__construct($client, $cacheManager);
    }

    /**
     * @param $userName
     *
     * @return mixed
     *
     * @throws InstagramAuthException
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Instagram\Exception\InstagramCacheException
     */
    public function fetchData($userName)
    {
        $endpoint = self::BASE_URL . $userName . '/';

        if ($this->cacheManager instanceof CacheManager && $this->cacheManager->sessionName) {
            $cookieJar = $this->cacheManager->getSession();

            $headers = [
                'headers' => [
                    'user-agent' => self::USER_AGENT,
                ],
                'cookies' => $cookieJar
            ];
        } else {
            $headers = [
                'headers' => [
                    'user-agent' => self::USER_AGENT,
                ]
            ];
        }

        $res = $this->client->request('GET', $endpoint, $headers);

        $html = (string)$res->getBody();

        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);

        if (!isset($matches[1])) {
            throw new InstagramException('Unable to extract JSON data');
        }

        $data = json_decode($matches[1]);

        if ($data === null) {
            throw new InstagramException(json_last_error_msg());
        }

        if (isset($data->entry_data->LoginAndSignupPage)) {
            throw new InstagramAuthException('Instagram blocked your IP. Login is required.');
        }

        if ($this->cacheManager && $this->cacheManager->sessionName) {
            $this->cacheManager->setSession($this->cacheManager->sessionName, $cookieJar);
        } elseif ($this->cacheManager instanceof CacheManager) {
            $newCache = new Cache();
            $newCache->setUserId($data->entry_data->ProfilePage[0]->graphql->user->id);
            $newCache->setCsrfToken($data->config->csrf_token);

            if ($res->hasHeader('Set-Cookie')) {
                $newCache->setCookie($res->getHeaders()['Set-Cookie']);
            }

            $this->cacheManager->set($newCache, $userName);
        }

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
