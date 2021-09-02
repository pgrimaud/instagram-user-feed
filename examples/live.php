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

    // user must be on live
    $username = 'pgrimaud';

    $live = $api->getLive($username);

    print_r($live);

} catch (InstagramException $e) {
    print_r($e->getMessage() . ' for ' . $username);
} catch (CacheException $e) {
    print_r('CacheException ' . $e->getMessage());
}
