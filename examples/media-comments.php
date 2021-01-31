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

    // we need media code
    $feedComments = $api->getMediaComments('CIvZJcurJaW');
    //$feedComments = $api->getMediaCommentsById('2463298121680852630');

    print_r($feedComments->getComments());

    $feedComments = $api->getMoreMediaComments('CIvZJcurJaW', $feedComments->getEndCursor());

    print_r($feedComments->getComments());

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}
