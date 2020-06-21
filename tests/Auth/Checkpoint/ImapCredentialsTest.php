<?php

namespace Instagram\Tests\Auth\Checkpoint;

use Instagram\Auth\Checkpoint\ImapCredentials;
use PHPUnit\Framework\TestCase;

class ImapCredentialsTest extends TestCase
{
    public function testSetUpCredentials()
    {
        $credentials = new ImapCredentials('imap.google.com', 'login', 'password');
        $this->assertSame('imap.google.com', $credentials->getServer());
        $this->assertSame('login', $credentials->getLogin());
        $this->assertSame('password', $credentials->getPassword());
    }
}
