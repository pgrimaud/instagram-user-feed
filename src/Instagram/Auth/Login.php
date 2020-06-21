<?php

declare(strict_types=1);

namespace Instagram\Auth;

use GuzzleHttp\Client;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use Instagram\Auth\Checkpoint\ImapCredentials;
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
     * @param ImapCredentials|null $imapCredentials
     */
    public function __construct(Client $client, string $login, string $password, ?ImapCredentials $imapCredentials = null)
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
            throw new InstagramAuthException('Unknown error, please report it with a GitHub issue. ' . $exception->getMessage());

            /** @todo IMPROVE ME !! */
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

            // fetch old cookie "mid" to next requests
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

            // Simulate click on "Send Security Code"
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
            ];

            $res2 = $this->client->request('POST', $url, $postHeaders);
            $body = (string)$res2->getBody();

            sleep(3);
            // force resend code
            // here we directly replay email sent (not sure about it, need to investigate)
            // https://www.instagram.com/challenge/6242737647/JLcKrPEBdX/ will be
            // https://www.instagram.com/challenge/replay/6242737647/JLcKrPEBdX/
            $urlForceReply = str_replace('challenge/', 'challenge/replay/', $url);
            $this->client->request('POST', $urlForceReply, $postHeaders);


            // Fetch code in emails (imap connection)
            dump('Wait for email... 10 seconds');
            sleep(10);

            $mailsIds = $this->mailbox->searchMailbox();

            $foundCode = false;
            $code      = null;

            // check into the last 3 mails
            for ($i = 0; $i < 3; $i++) {
                $mail = end($mailsIds);
                if ($mail) {
                    $mail = $this->mailbox->getMail($mail);
                    preg_match('/<font size="6">([0-9]{6})<\/font>/s', $mail->textHtml, $match);
                    if ($mail->fromAddress === 'security@mail.instagram.com' && isset($match[1])) {
                        $foundCode = true;
                        $code      = $match[1];
                        break;
                    }

                    array_pop($mailsIds);
                }
            }

            if (!$foundCode) {
                /** @todo maybe sleep(10) + retry imap check */
            }

            dump('Code is : ' . $code);
            sleep(2);

            // here we create a new CookieJar to retrieve real session cookies
            $cookieJarClean = new CookieJar();

            $cookie      = 'ig_cb=1; ig_did=' . $data->device_id . '; csrftoken=' . $data->config->csrf_token . '; mid=' . $mid;
            $postHeaders = [
                'form_params' => [
                    'security_code' => (int)$code,
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
                ],
                'cookies'     => $cookieJarClean
            ];

            $res3 = $this->client->request('POST', $url, $postHeaders);

            $codeSubmissionData = json_decode((string)$res3->getBody());

            if ($codeSubmissionData->status === 'ok') {
                return $cookieJarClean;
            } else {
                dd('Error during connection');
            }
        }

        $response = json_decode((string)$query->getBody());

        if ($response->authenticated == true) {
            return $cookieJar;
        } else {
            throw new InstagramAuthException('Wrong login / password');
        }
    }
}
