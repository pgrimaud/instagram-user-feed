<?php

namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Auth\Session;
use Instagram\Exception\InstagramAuthException;
use Instagram\Exception\InstagramException;
use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\InstagramHelper;
use Instagram\Utils\UserAgentHelper;

class HtmlTransportFeed
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Session $session
     * @param Client $client
     */
    public function __construct(Session $session, Client $client)
    {
        $this->session = $session;
        $this->client  = $client;
    }

    /**
     * @param string $userName
     *
     * @return mixed
     *
     * @throws InstagramFetchException
     */
    public function fetchData(string $userName)
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
