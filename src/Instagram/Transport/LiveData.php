<?php

declare(strict_types=1);

namespace Instagram\Transport;

use GuzzleHttp\Exception\ClientException;
use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\{Endpoints, OptionHelper, CacheResponse};


class LiveData extends AbstractDataFeed
{
    /**
     * @param string $username
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchData(string $username): \StdClass
    {
        $endpoint = Endpoints::getLiveUrl($username);

        $headers = [
            'headers' => [
                'user-agent'      => OptionHelper::$USER_AGENT,
                'accept-language' => OptionHelper::$LOCALE,
            ],
            'cookies' => $this->session->getCookies(),
        ];

        try {
            $res = $this->client->request('GET', $endpoint, $headers);
        } catch (ClientException $exception) {
            CacheResponse::setResponse($exception->getResponse());
            // should throw a 404 if live isn't on
            throw new InstagramFetchException('No live streaming found');
        }

        CacheResponse::setResponse($res);

        $data = (string)$res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramFetchException('No live streaming found');
        }

        return $data;
    }
}
