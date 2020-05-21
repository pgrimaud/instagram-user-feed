<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\{InstagramHelper, UserAgentHelper};

class HtmlProfileDataFeed extends AbstractDataFeed
{
    /**
     * @param string $userName
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(string $userName): \StdClass
    {
        $endpoint = InstagramHelper::URL_BASE . $userName . '/';

        $headers = [
            'headers' => [
                'user-agent' => UserAgentHelper::AGENT_DEFAULT,
            ],
            'cookies' => $this->session->getCookies()
        ];

        $res = $this->client->request('GET', $endpoint, $headers);

        $html = (string)$res->getBody();

        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);

        if (!isset($matches[1])) {
            throw new InstagramFetchException('Unable to extract JSON data');
        }

        $data = json_decode($matches[1]);

        if ($data === null) {
            throw new InstagramFetchException(json_last_error_msg());
        }

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
