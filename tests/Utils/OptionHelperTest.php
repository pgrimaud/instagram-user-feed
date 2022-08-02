<?php

namespace Instagram\Tests\Utils;

use Instagram\Utils\Endpoints;
use Instagram\Utils\OptionHelper;
use PHPUnit\Framework\TestCase;

class OptionHelperTest extends TestCase
{
    public function testGetInstagramUserAgent()
    {
        $userAgent = OptionHelper::$USER_AGENT;
        $this->assertSame('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36', $userAgent);
    }

    public function testSetInstagramUserAgent()
    {
        // Set User Agent
        OptionHelper::$USER_AGENT = 'Mozilla/5.0 (Linux; Android 10; RMX2030) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.57 Mobile Safari/537.36';
        
        $userAgent = OptionHelper::$USER_AGENT;
        $this->assertSame('Mozilla/5.0 (Linux; Android 10; RMX2030) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.57 Mobile Safari/537.36', $userAgent);
    }

    public function testGetInstagramLanguage()
    {
        $userAgent = OptionHelper::$LOCALE;
        $this->assertSame('en-EN', $userAgent);
    }

    public function testSetInstagramLanguage()
    {
        OptionHelper::$LOCALE = 'id-ID';
        
        $userAgent = OptionHelper::$LOCALE;
        $this->assertSame('id-ID', $userAgent);
    }
}
