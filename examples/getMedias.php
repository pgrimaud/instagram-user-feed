<?php

require __DIR__ . '/../vendor/autoload.php';

$cache = new Instagram\Storage\CacheManager();

$api = new Instagram\Api($cache);
$api->setUserName('pgrimaud');

try {
    /** @var \Instagram\Hydrator\Component\Feed $feed */
    $feed = $api->getFeed();
} Catch (\Instagram\Exception\InstagramException $exception) {
    print_r($exception->getMessage());
}
