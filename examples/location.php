<?php

declare(strict_types=1);

use Instagram\Api;
use Instagram\Exception\InstagramException;

use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require realpath(dirname(__FILE__)) . '/../vendor/autoload.php';
$credentials = include_once realpath(dirname(__FILE__)) . '/credentials.php';

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

try {
    $api = new Api($cachePool);
    $api->login($credentials->getLogin(), $credentials->getPassword());

    // 558750588 is https://www.instagram.com/explore/locations/558750588/ (Little Choc Apothecary)
    $postId = 558750588;

    $location = $api->getLocation($postId);

    // location data
    print_r($location);

    // get more data
    $locationMore = $api->getMoreLocationMedias($postId, $location->getEndCursor());
    print_r($locationMore);

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}
