<?php

declare(strict_types=1);

use Instagram\Api;
use Instagram\Exception\InstagramException;
use Instagram\Model\Media;
use Instagram\Utils\MediaDownloadHelper;
use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require realpath(dirname(__FILE__)) . '/../vendor/autoload.php';
$credentials = include_once realpath(dirname(__FILE__)) . '/credentials.php';

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

try {
    $api = new Api($cachePool);
    $api->login($credentials->getLogin(), $credentials->getPassword());

    $profile = $api->getProfile('twhiddleston');

    downloadMedias($profile->getMedias());

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}

function downloadMedias(array $medias)
{
    /** @var Media $media */
    foreach ($medias as $media) {
        if ($media->isVideo()) {
            $fileName = MediaDownloadHelper::downloadMedia($media->getVideoUrl());
        } else {
            $fileName = MediaDownloadHelper::downloadMedia($media->getDisplaySrc());
        }
        echo 'Media downloaded as : ' . $fileName . PHP_EOL;
    }
}
