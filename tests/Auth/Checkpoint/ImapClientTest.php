<?php

namespace Instagram\Tests\Auth\Checkpoint;

use Instagram\Auth\Checkpoint\ImapClient;
use PHPUnit\Framework\TestCase;

class ImapClientTest extends TestCase
{
    public function testSetUpCredentials()
    {
        $credentials = new ImapClient('imap.google.com', 'login', 'password');
        $this->assertSame('imap.google.com', $credentials->getServer());
        $this->assertSame('login', $credentials->getLogin());
        $this->assertSame('password', $credentials->getPassword());
        $this->assertSame('imap', $credentials->getConnectionType());
    }
}
