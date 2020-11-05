<?php

namespace Instagram\Tests\Utils;

use Instagram\Utils\CacheHelper;
use PHPUnit\Framework\TestCase;

class CacheHelperTest extends TestCase
{
    public function testEmailToSanitize()
    {
        $this->assertSame(CacheHelper::sanitizeUsername('pierre@freyum.com'), 'pierreatfreyumdotcom');
    }
}
