<?php

namespace Instagram\Tests\Utils;

use Instagram\Exception\InstagramDownloadException;
use Instagram\Utils\MediaDownloadHelper;
use PHPUnit\Framework\TestCase;

class MediaDownloadHelperTest extends TestCase
{
    public function testInvalidMediaUrl()
    {
        $this->expectException(InstagramDownloadException::class);

        $url = 'invalid url';
        MediaDownloadHelper::downloadMedia($url);
    }

    public function testDownloadUrl()
    {
        $baseImg = 'https://placehold.co/20';
        $fileName = MediaDownloadHelper::downloadMedia($baseImg, __DIR__ . '/../cache');

        $this->assertSame('20', $fileName);
    }
}
