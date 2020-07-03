<?php

declare(strict_types=1);

namespace Instagram\Auth\Checkpoint;

use Instagram\Exception\InstagramAuthException;

class ImapClient
{
    /**
     * @var string
     */
    private $server;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $server
     * @param string $login
     * @param string $password
     *
     * @throws InstagramAuthException
     */
    public function __construct(string $server, string $login, string $password)
    {
        $this->server   = $server;
        $this->login    = $login;
        $this->password = $password;

        $this->available();
    }

    /**
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @throws InstagramAuthException
     *
     * @codeCoverageIgnore
     */
    private function available()
    {
        // ext-imap is enabled?
        if (!extension_loaded('imap')) {
            throw new InstagramAuthException('IMAP php extension must be enabled to bypass checkpoint_challenge.');
        }
    }

    /**
     * @param int $try
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getLastInstagramEmailContent(int $try = 1): string
    {
        $resource  = imap_open('{' . $this->getServer() . '/imap/ssl}INBOX', $this->getLogin(), $this->getPassword());
        $numberMax = imap_num_msg($resource);

        $foundCode = false;
        $code      = '';

        // $numberMax = 0 when mailbox is empty
        if ($numberMax > 0) {

            // check into the last 3 mails
            for ($i = $numberMax; $i > ($numberMax - 3); $i--) {
                $body = imap_body($resource, $i);
                $body = quoted_printable_decode($body);

                $headers = imap_headerinfo($resource, $i, 0);

                preg_match('/<font size="6">([0-9]{6})<\/font>/s', $body, $match);
                if ($headers->senderaddress === 'Instagram <security@mail.instagram.com>' && isset($match[1])) {
                    imap_delete($resource, $i);

                    $foundCode = true;
                    $code      = $match[1];
                    break;
                }
            }
        }

        imap_close($resource);

        // retry imap check (3 times max)
        if (!$foundCode && $try <= 3) {
            sleep(6);
            $code = $this->getLastInstagramEmailContent($try + 1);
        }

        return $code;
    }
}
