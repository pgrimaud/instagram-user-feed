<?php

declare(strict_types=1);

namespace Instagram\Transport;

use GuzzleHttp\Exception\ClientException;
use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\{InstagramHelper, OptionHelper, CacheResponse};

class HtmlProfileDataFeed extends AbstractDataFeed
{
    /**
     * @param string $userName
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(string $userName): \StdClass
    {
        $endpoint = InstagramHelper::URL_BASE . $userName . '/';

        $headers = [
            'headers' => [
                'user-agent'      => OptionHelper::$USER_AGENT,
                'accept-language' => OptionHelper::$LOCALE,
            ],
        ];
        
        if (!empty($this->session)) {
            $headers['cookies'] = $this->session->getCookies();
        }

        try {
            $res = $this->client->request('GET', $endpoint, $headers);
        } catch (ClientException $exception) {
            CacheResponse::setResponse($exception->getResponse());

            if ($exception->getCode() === 404) {
                throw new InstagramFetchException('User ' . $userName . ' not found');
            } else {
                throw new InstagramFetchException('Internal error: ' . $exception->getMessage());
            }
        }

        CacheResponse::setResponse($res);

        $html = (string)$res->getBody();

        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);

        if (!isset($matches[1])) {
            throw new InstagramFetchException('Profile #1 : Unable to extract JSON data');
        }

        $data = json_decode($matches[1], false);

        if ($data === null) {
            throw new InstagramFetchException(json_last_error_msg());
        }

        if (!empty($data->entry_data->ProfilePage[0]->graphql)) {
            return $data->entry_data->ProfilePage[0]->graphql->user;
        }

        preg_match('/<script type="text\/javascript">window\.__additionalDataLoaded\([^,]*,(.+)\);<\/script>/', $html, $matches);

        if (!isset($matches[1])) {
            throw new InstagramFetchException('Profile #2 : Unable to extract JSON data');
        }

        $data = json_decode($matches[1], false);

        return $data->graphql->user;
    }
}
