<?php

declare(strict_types=1);

namespace Instagram\Transport;

use GuzzleHttp\Client;
use Instagram\Auth\Session;
use Instagram\Exception\InstagramFetchException;
use Instagram\Model\InstagramProfile;
use Instagram\Utils\{InstagramHelper, UserAgentHelper};

class JsonTransportFeed
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
     * @param Client  $client
     */
    public function __construct(Session $session, Client $client)
    {
        $this->session = $session;
        $this->client  = $client;
    }

    /**
     * @param InstagramProfile $instagramProfile
     *
     * @return \StdClass
     * @throws InstagramFetchException
     */
    public function fetchData(InstagramProfile $instagramProfile): \StdClass
    {
        $variables = [
            'id'    => $instagramProfile->getId(),
            'first' => InstagramHelper::PAGINATION_DEFAULT,
            'after' => $instagramProfile->getEndCursor(),
        ];

        $headers = [
            'headers' => [
                'user-agent'       => UserAgentHelper::AGENT_DEFAULT,
                'x-requested-with' => 'XMLHttpRequest',
            ],
            'cookies' => $this->session->getCookies()
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH . '&variables=' . json_encode($variables);

        $res = $this->client->request('GET', $endpoint, $headers);

        $data = (string)$res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramFetchException(json_last_error_msg());
        }

        return $data->data->user;
    }
}
