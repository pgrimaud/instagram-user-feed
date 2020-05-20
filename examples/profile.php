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

    $profile = $api->getFeed('twhiddleston');

    dump(count($profile->getMedias()));

    while ($profile->hasMoreMedias()) {
        $profile = $api->getFeed('robertdowneyjr', $profile);
        sleep(1);
        dump(count($profile->getMedias()));
    }

} catch (InstagramException $e) {
    print_r($e->getMessage());
}
