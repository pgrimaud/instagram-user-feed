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
            /** @todo IMPROVE ME */
            //throw new InstagramAuthException('Unknown error, please report it with a GitHub issue. ' . $exception->getMessage());
            $data = json_decode((string)$exception->getResponse()->getBody());

            if ($data->message !== 'checkpoint_required') {
                dd($data);
            }

            // trigger email verification
            $url = InstagramHelper::URL_IG . $data->checkpoint_url;

            $headers = [
                'headers' => [
                    'user-agent' => UserAgentHelper::AGENT_DEFAULT,
                ],
                'cookies' => $cookieJar
            ];

            $mid = $cookieJar->getCookieByName('mid')->getValue();

            $res  = $this->client->request('GET', $url, $headers);
            $body = (string)$res->getBody();
            preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $body, $matches);

            $data = json_decode($matches[1]);

            $method = 0;

            // selection of method to verify login
            foreach ($data->entry_data->Challenge[0]->extraData->content as $item) {
                if ($item->__typename === 'GraphChallengePageForm') {
                    foreach ($item->fields[0]->values as $method) {
                        if (strpos($method->label, 'Email') !== false) {
                            $method = $method->value;
                        }
                    }
                }
            }

            $verificationMethod = $method;

            // "Send Security Code"
            $cookie      = 'ig_cb=1; ig_did=' . $data->device_id . '; csrftoken=' . $data->config->csrf_token . '; mid=' . $mid;
            $postHeaders = [
                'form_params' => [
                    'choice' => $verificationMethod,
                ],
                'headers'     => [
                    'x-instagram-ajax' => $data->rollout_hash,
                    'content-type'     => 'application/x-www-form-urlencoded',
                    'accept'           => '*/*',
                    'user-agent'       => UserAgentHelper::AGENT_DEFAULT,
                    'x-requested-with' => 'XMLHttpRequest',
                    'x-csrftoken'      => $data->config->csrf_token,
                    'x-ig-app-id'      => 123456889,
                    'referer'          => $url,
                    'cookie'           => $cookie,
                ]
                /** @todo may be useful later!? */
                //'cookies'     => $cookieJar
            ];

            $res2 = $this->client->request('POST', $url, $postHeaders);
            $body = (string)$res2->getBody();
            // email is sent - need to verify

            dd($body);
            exit;
        }

        $response = json_decode((string)$query->getBody());

        if ($response->authenticated == true) {
            return $cookieJar;
        } else {
            throw new InstagramAuthException('Wrong login / password');
        }
    }
}
