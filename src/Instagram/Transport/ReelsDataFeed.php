<?php

declare(strict_types=1);

namespace Instagram\Transport;

use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ClientException;
use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\{Endpoints, OptionHelper, CacheResponse};

class ReelsDataFeed extends AbstractDataFeed
{
    const IG_APP_ID = 936619743392459;

    /**
     * @param int         $userId
     * @param string|null $maxId
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchData(int $userId, string $maxId = null): \StdClass
    {
        $endpoint = Endpoints::REELS_URL;

        $csrfToken = '';

        /** @var SetCookie $cookie */
        foreach ($this->session->getCookies() as $cookie) {
            if ($cookie->getName() === 'csrftoken') {
                $csrfToken = $cookie->getValue();
                break;
            }
        }

        $options = [
            'headers' => [
                'user-agent'       => OptionHelper::$USER_AGENT,
                'accept-language'  => OptionHelper::$LOCALE,
                'x-csrftoken'      => $csrfToken,
                'x-ig-app-id'      => self::IG_APP_ID,
            ],
            'cookies' => $this->session->getCookies(),
        ];

        $params = [
            'target_user_id' => $userId,
            'page_size'      => 12,
        ];

        if ($maxId) {
            $params = array_merge($params, [
                'max_id' => $maxId,
            ]);
        }

        $options = array_merge($options, ['form_params' => $params]);

        try {
            $res = $this->client->request('POST', $endpoint, $options);
        } catch (ClientException $exception) {
            CacheResponse::setResponse($exception->getResponse());
            throw new InstagramFetchException('Reels fetch error');
        }

        CacheResponse::setResponse($res);

        $data = (string) $res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramFetchException('Reels fetch error (invalid JSON)');
        }

        return $data;
    }
}