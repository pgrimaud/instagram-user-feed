<?php

declare(strict_types=1);

namespace Instagram\Auth\Checkpoint;

use Instagram\Exception\InstagramAuthException;

class ImapCredentials
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
        $this->server = $server;
        $this->login = $login;
        $this->password = $password;

        $this->available();
    }

    /**
     * @throws InstagramAuthException
     * @codeCoverageIgnore
     */
    private function available()
    {
        // ext-imap is enabled?
        if(!extension_loaded('imap')){
            throw new InstagramAuthException('IMAP php extension must be enabled to bypass checkpoint_challenge.');
        }
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
}
