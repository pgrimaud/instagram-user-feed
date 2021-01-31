<?php

namespace Instagram\Tests\Utils;

use Instagram\Utils\Endpoints;
use Instagram\Utils\InstagramHelper;
use PHPUnit\Framework\TestCase;

class InstagramHelperTest extends TestCase
{
    public function testInstagramGetCodeFromId()
    {
        $code = InstagramHelper::getCodeFromId(2463298121680852630);
        $this->assertSame('CIvZJcurJaW', $code);
    }
}
