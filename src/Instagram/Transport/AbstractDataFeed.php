<?php

declare(strict_types=1);

namespace Instagram\Transport;

use GuzzleHttp\ClientInterface;
use Instagram\Auth\Session;
use Instagram\Exception\{InstagramAuthException, InstagramFetchException};
use Instagram\Utils\{OptionHelper, InstagramHelper, CacheResponse};

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
     * @param Session|null $session
     *
     * @throws InstagramAuthException
     */
    public function __construct(ClientInterface $client, ?Session $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    /**
     * @param string $endpoint
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    protected function fetchJsonDataFeed(string $endpoint, array $headers = []): \StdClass
    {
        $headers = [
            'headers' => array_merge([
                'user-agent'       => OptionHelper::$USER_AGENT,
                'accept-language'  => OptionHelper::$LOCALE,
                'x-requested-with' => 'XMLHttpRequest',
            ], $headers),
        ];

        if (!empty($this->session)) {
            $headers['cookies'] = $this->session->getCookies();
        }

        $res = $this->client->request('GET', $endpoint, $headers);
        CacheResponse::setResponse($res);

        $data = (string)$res->getBody();
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
                'user-agent'       => OptionHelper::$USER_AGENT,
                'accept-language'  => OptionHelper::$LOCALE,
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
        CacheResponse::setResponse($res);

        $data = (string)$res->getBody();
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
                    'user-agent'      => OptionHelper::$USER_AGENT,
                    'accept-language' => OptionHelper::$LOCALE,
                ],
            ]);
            CacheResponse::setResponse($baseRequest);

            $html = (string)$baseRequest->getBody();
            //preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);
            preg_match('/\"client_revision\":(.*?),/', $html, $matches);

            if (!isset($matches[1])) {
                throw new InstagramAuthException('Unable to extract server version');
            }

            return $matches[1];
        } catch (\Exception $e) {
            throw new InstagramFetchException($e->getMessage());
        }
    }
}
