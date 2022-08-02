<?php

declare(strict_types=1);

namespace Instagram\Auth\Checkpoint;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use Instagram\Exception\InstagramAuthException;
use Instagram\Utils\{InstagramHelper, OptionHelper};

class Challenge
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * @var string
     */
    private $checkPointUrl;

    /**
     * @var string
     */
    private $midCookie;

    /**
     * @var int
     */
    private $delay;

    /**
     * @param ClientInterface $client
     * @param CookieJar $cookieJar
     * @param string $checkpointUrl
     * @param int $delay
     */
    public function __construct(ClientInterface $client, CookieJar $cookieJar, string $checkpointUrl, int $delay = 3)
    {
        $this->client        = $client;
        $this->cookieJar     = $cookieJar;
        $this->checkPointUrl = InstagramHelper::URL_IG . $checkpointUrl;
        $this->delay         = $delay;
    }

    /**
     * Trigger email verification
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchChallengeContent(): \StdClass
    {
        $headers = [
            'headers' => [
                'user-agent'      => OptionHelper::$USER_AGENT,
                'accept-language' => OptionHelper::$LOCALE,
            ],
            'cookies' => $this->cookieJar
        ];

        // fetch old cookie "mid" to next requests
        $this->midCookie = $this->cookieJar->getCookieByName('mid')->getValue();

        $res  = $this->client->request('GET', $this->checkPointUrl, $headers);
        $body = (string)$res->getBody();
        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $body, $matches);

        return json_decode($matches[1]);
    }


    /**
     * @param \StdClass $challengeContent
     * @param string $url
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSecurityCode(\StdClass $challengeContent, string $url = '')
    {
        $url = $url != '' ? $url : $this->checkPointUrl;

        $method = 0;

        // selection of method to verify login
        foreach ($challengeContent->entry_data->Challenge[0]->extraData->content as $item) {
            if ($item->__typename === 'GraphChallengePageForm') {
                foreach ($item->fields[0]->values as $method) {
                    if (strpos($method->label, 'Email') !== false) {
                        $method = $method->value;
                    }
                }
            }
        }

        $verificationMethod = $method;

        // simulate click on "Send Security Code"
        $cookie      = 'ig_cb=1; ig_did=' . $challengeContent->device_id . '; csrftoken=' . $challengeContent->config->csrf_token . '; mid=' . $this->getMidCookie();
        $postHeaders = [
            'form_params' => [
                'choice' => $verificationMethod,
            ],
            'headers'     => [
                'x-instagram-ajax' => $challengeContent->rollout_hash,
                'content-type'     => 'application/x-www-form-urlencoded',
                'accept'           => '*/*',
                'user-agent'       => OptionHelper::$USER_AGENT,
                'accept-language'  => OptionHelper::$LOCALE,
                'x-requested-with' => 'XMLHttpRequest',
                'x-csrftoken'      => $challengeContent->config->csrf_token,
                'x-ig-app-id'      => 123456889,
                'referer'          => $url,
                'cookie'           => $cookie,
            ]
        ];

        $res2 = $this->client->request('POST', $url, $postHeaders);

        sleep($this->delay);

        $body = (string)$res2->getBody();
    }

    /**
     * @return string
     */
    public function getMidCookie(): string
    {
        return $this->midCookie;
    }

    /**
     * Force resend code
     * Here we directly replay email sent (not sure about it, need to investigate)
     * https://www.instagram.com/challenge/6242737647/JLcKrPEBdX/ will be
     * https://www.instagram.com/challenge/replay/6242737647/JLcKrPEBdX/
     *
     * @param \StdClass $challengeContent
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function reSendSecurityCode(\StdClass $challengeContent): void
    {
        $urlForceResend = str_replace('challenge/', 'challenge/replay/', $this->checkPointUrl);
        $this->sendSecurityCode($challengeContent, $urlForceResend);

        sleep($this->delay * 3);
    }

    /**
     * @param \StdClass $challengeContent
     * @param string $code
     *
     * @return CookieJar
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InstagramAuthException
     */
    public function submitSecurityCode(\StdClass $challengeContent, string $code): CookieJar
    {
        // here we create a new CookieJar to retrieve real session cookies
        $cookieJarClean = new CookieJar();

        $cookie      = 'ig_cb=1; ig_did=' . $challengeContent->device_id . '; csrftoken=' . $challengeContent->config->csrf_token . '; mid=' . $this->getMidCookie();
        $postHeaders = [
            'form_params' => [
                'security_code' => $code,
            ],
            'headers'     => [
                'x-instagram-ajax' => $challengeContent->rollout_hash,
                'content-type'     => 'application/x-www-form-urlencoded',
                'accept'           => '*/*',
                'user-agent'       => OptionHelper::$USER_AGENT,
                'accept-language'  => OptionHelper::$LOCALE,
                'x-requested-with' => 'XMLHttpRequest',
                'x-csrftoken'      => $challengeContent->config->csrf_token,
                'x-ig-app-id'      => 123456889,
                'referer'          => $this->checkPointUrl,
                'cookie'           => $cookie,
            ],
            'cookies'     => $cookieJarClean
        ];

        $res3 = $this->client->request('POST', $this->checkPointUrl, $postHeaders);

        $codeSubmissionData = json_decode((string)$res3->getBody());

        if ($codeSubmissionData->status === 'ok') {
            return $cookieJarClean;
        } else {
            throw new InstagramAuthException('Unknown error, please report it with a GitHub issue.');
        }
    }
}
