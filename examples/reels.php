<?php

declare(strict_types=1);

use Instagram\Api;
use Instagram\Exception\InstagramException;

use Instagram\Model\ReelsFeed;
use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require realpath(dirname(__FILE__)) . '/../vendor/autoload.php';
$credentials = include_once realpath(dirname(__FILE__)) . '/credentials.php';

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

try {
    $api = new Api($cachePool);
    $api->login($credentials->getLogin(), $credentials->getPassword());

    // id 25288085 is wendyswan (https://www.instagram.com/wendyswan)
    $reelsFeed = $api->getReels(25288085);

    printReels($reelsFeed);

    do {
        $reelsFeed = $api->getReels(25288085, $reelsFeed->getMaxId());
        printReels($reelsFeed);

        // avoid 429 Rate limit from Instagram
        sleep(1);
    } while ($reelsFeed->hasMaxId());

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}

function printReels(ReelsFeed $reelsFeed)
{
    /** @var \Instagram\Model\Reels $reels */
    foreach ($reelsFeed->getReels() as $reels) {
        echo 'ID        : ' . $reels->getId() . "\n";
        echo 'Code      : ' . $reels->getShortCode() . "\n";
        echo 'Caption   : ' . $reels->getCaption() . "\n";
        echo 'Link      : ' . $reels->getVideos()[0]->url . "\n";
        echo 'Likes     : ' . $reels->getLikes() . "\n";
        echo 'Date      : ' . $reels->getDate()->format('Y-m-d h:i:s') . "\n\n";
    }
}