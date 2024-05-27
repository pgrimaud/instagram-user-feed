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

    // we need instagram user id
    $profile = $api->getProfile('starwars');
    sleep(1);
    $feedStories = $api->getStories($profile->getId());

    if (count($feedStories->getStories())) {
        /** @var \Instagram\Model\StoryMedia $story */
        foreach ($feedStories->getStories() as $story) {
            $storyId = $story->getId();
            $ownerId = intval($feedStories->getOwner()->id);
            $storyTakenAt = strtotime($story->getTakenAtDate()->format("Y-m-d h:i:sa"));
            $storySeenAt = time();

            $seenStory = $api->seenStory($storyId, $ownerId, $storyTakenAt, $storySeenAt);
            echo "Seen story {$storyId} : {$seenStory} \n";
        }
    } else {
        echo 'No stories' . "\n";
    }

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}
