<?php

declare(strict_types=1);

namespace Instagram\Transport;

use GuzzleHttp\ClientInterface;
use Instagram\Auth\Session;
use Instagram\Exception\{InstagramAuthException, InstagramFetchException};
use Instagram\Utils\{UserAgentHelper, InstagramHelper};

abstract class AbstractDataFeed
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     * @param Session|null    $session
     *
     * @throws InstagramAuthException
     */
    public function __construct(ClientInterface $client, ?Session $session)
    {
        $this->client  = $client;
        $this->session = $session;
    }

    /**
     * @param string $endpoint
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    protected function fetchJsonDataFeed(string $endpoint): \StdClass
    {
        $headers = [
            'headers' => [
                'user-agent'       => UserAgentHelper::AGENT_DEFAULT,
                'x-requested-with' => 'XMLHttpRequest',
            ],
        ];

        if (!empty($this->session)) {
            $headers['cookies'] = $this->session->getCookies();
        }

        $res = $this->client->request('GET', $endpoint, $headers);

        $data = (string) $res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramFetchException(json_last_error_msg());
        }

        return $data;
    }

    /**
     * @param string $endpoint
     *
     * @throws InstagramFetchException
     */
    protected function postJsonDataFeed(string $endpoint, array $formParameters = []): \StdClass
    {
        $options = [
            'headers' => [
                'user-agent'       => UserAgentHelper::AGENT_DEFAULT,
                'x-requested-with' => 'XMLHttpRequest',
                'x-instagram-ajax' => $this->getRolloutHash(),
                'x-csrftoken'      => $this->session->getCookies()->getCookieByName('csrftoken')->getValue(),
            ],
            'cookies' => $this->session->getCookies(),
        ];

        if (count($formParameters) > 0) {
            $options = array_merge($options, [
                'form_params' => $formParameters,
            ]);
        }

        $res = $this->client->request('POST', $endpoint, $options);

        $data = (string) $res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramFetchException(json_last_error_msg());
        }

        return $data;
    }

    /**
     * @return mixed
     *
     * @throws InstagramFetchException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getRolloutHash(): string
    {
        try {
            $baseRequest = $this->client->request('GET', InstagramHelper::URL_BASE, [
                'headers' => [
                    'user-agent' => UserAgentHelper::AGENT_DEFAULT,
                ],
            ]);

            $html = (string) $baseRequest->getBody();

            preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);

            if (!isset($matches[1])) {
                throw new InstagramAuthException('Unable to extract JSON data');
            }

            $data = json_decode($matches[1]);
            return $data->rollout_hash;
        } catch (\Exception $e) {
            throw new InstagramFetchException($e->getMessage());
        }
    }
}
