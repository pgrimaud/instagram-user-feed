<?php

require __DIR__ . '/../vendor/autoload.php';

$api = new Instagram\Api();
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