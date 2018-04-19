<?php

use Instagram\Storage\CacheManager;

require __DIR__ . '/../vendor/autoload.php';

$cache = new CacheManager();

$api = new Instagram\Api($cache);
$api->setUserName('pgrimaud');

/** @var \Instagram\Hydrator\Feed $feed */
$feed = $api->getFeed();

foreach ($feed->getMedias() as $media) {
    echo $media->getCaption() . "\n";
}

$api->setEndCursor($feed->getEndCursor());
$feed = $api->getFeed();

foreach ($feed->getMedias() as $media) {
    echo $media->getCaption() . "\n";
}