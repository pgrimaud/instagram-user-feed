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

    // ID 1518284433 is robertdowneyjr
    $feed = $api->getTaggedMedias(1518284433);
    printMedias($feed->getMedias());

    do {
        $feed = $api->getTaggedMedias(1518284433, $feed->getEndCursor());
        printMedias($feed->getMedias());

        sleep(1);
    } while ($feed->hasNextPage());

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}

function printMedias(array $medias)
{
    foreach ($medias as $media) {
        echo 'ID        : ' . $media->getId() . "\n";
        echo 'Caption   : ' . $media->getCaption() . "\n";
        echo 'Link      : ' . $media->getLink() . "\n";
        echo 'Likes     : ' . $media->getLikes() . "\n";
        echo 'Date      : ' . $media->getDate()->format('Y-m-d h:i:s') . "\n\n";
    }
}
