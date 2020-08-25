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
    $api     = new Api($cachePool);
    $api->login($credentials->getLogin(), $credentials->getPassword());

    $followings = $api->getMoreFollowings(1518284433, 'QVFCQkEzYXR5ZnVJQ0gtdzB5S19JbWFsZk0xWW12NF9ydzllQ3lWckNSRWZROGswUi1TMVlZQW5zTGkyZ0RrU2dDaDVoLWdaVjRMaGNwMlNJeGRIVXozNQ==');

    print_r($followings);

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}