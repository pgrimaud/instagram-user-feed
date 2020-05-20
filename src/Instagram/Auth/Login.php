<?php

declare(strict_types=1);

namespace Instagram\Auth;

use GuzzleHttp\Client;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use Instagram\Exception\InstagramAuthException;
use Instagram\Utils\InstagramHelper;
use Instagram\Utils\UserAgentHelper;

class Login
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @param Client $client
     * @param string $login
     * @param string $password
     */
    public function __construct(Client $client, string $login, string $password)
    {
        $this->client   = $client;
        $this->login    = $login;
        $this->password = $password;
    }

    /**
     * @return CookieJar
     *
     * @throws InstagramAuthException
     */
    public function process(): CookieJar
    {
        $baseRequest = $this->client->request('GET', InstagramHelper::URL_BASE, [
            'headers' => [
                'user-agent' => UserAgentHelper::AGENT_DEFAULT
            ]
        ]);

        $html = (string)$baseRequest->getBody();

        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);

        if (!isset($matches[1])) {
            throw new InstagramAuthException('Unable to extract JSON data');
        }

        $data = json_decode($matches[1]);

        $cookieJar = new CookieJar();

        try {
            $query = $this->client->request('POST', InstagramHelper::URL_AUTH, [
                'form_params' => [
                    'username'     => $this->login,
                    'enc_password' => '#PWD_INSTAGRAM_BROWSER:0:' . time() . ':' . $this->password,
                ],
                'headers'     => [
                    'cookie'      => 'ig_cb=1; csrftoken=' . $data->config->csrf_token,
                    'referer'     => InstagramHelper::URL_BASE,
                    'x-csrftoken' => $data->config->csrf_token,
                    'user-agent'  => UserAgentHelper::AGENT_DEFAULT,
                ],
                'cookies'     => $cookieJar
            ]);
        } catch (ClientException $exception) {
            throw new InstagramAuthException('Unknown error (' . $exception->getMessage() . '). Please report it with a GitHub issue.');
        }

        $response = json_decode((string)$query->getBody());
        if ($response->authenticated == true) {
            return $cookieJar;
        } else {
            throw new InstagramAuthException('Wrong login / password');
        }
    }
}
