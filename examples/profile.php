<?php

use Instagram\Api;
use Instagram\Exception\InstagramException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require realpath(dirname(__FILE__)) . '/../vendor/autoload.php';
$credentials = include_once realpath(dirname(__FILE__)) . '/credentials.php';

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

try {
    $api = new Api($cachePool);
    $api->login($credentials->getLogin(), $credentials->getPassword());
    $profile = $api->getFeed('robertdowneyjr');

    while ($profile->hasMoreMedias()) {
        foreach ($profile->getMedias() as $media) {
            dump($media->getDate()->format('Y-m-d'));
        }

        $profile = $api->getFeed('robertdowneyjr', $profile);
    }
} catch (InstagramException $e) {
    print_r($e->getMessage());
}
