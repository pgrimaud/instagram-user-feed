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
        $baseImg = 'https://cdn.discordapp.com/attachments/862683614256168990/862724864581959680/input1.png';
        $fileName = MediaDownloadHelper::downloadMedia($baseImg, __DIR__ . '/../cache');

        $this->assertSame('attachments-862683614256168990-862724864581959680-input1.png', $fileName);
    }
}
