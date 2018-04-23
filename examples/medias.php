<?php

require __DIR__ . '/../vendor/autoload.php';

$cache = new Instagram\Storage\CacheManager(__DIR__ . '/../cache/');

$api = new Instagram\Api($cache);
$api->setUserName('pgrimaud');

try {
    // First page

    /** @var \Instagram\Hydrator\Component\Feed $feed */
    $feed = $api->getFeed();

    echo '============================' . "\n";
    echo 'User Informations : ' . "\n";
    echo '============================' . "\n\n";

    echo 'ID        : ' . $feed->getId() . "\n";
    echo 'Full Name : ' . $feed->getFullName() . "\n";
    echo 'UserName  : ' . $feed->getUserName() . "\n";
    echo 'Following : ' . $feed->getFollowing() . "\n";
    echo 'Followers : ' . $feed->getFollowers() . "\n\n";

    echo '============================' . "\n";
    echo 'Medias first page : ' . "\n";
    echo '============================' . "\n\n";

    /** @var \Instagram\Hydrator\Component\Media $media */
    foreach ($feed->getMedias() as $media) {
        echo 'ID        : ' . $media->getId() . "\n";
        echo 'Caption   : ' . $media->getCaption() . "\n";
        echo 'Link      : ' . $media->getLink() . "\n";
        echo 'Likes     : ' . $media->getLikes() . "\n";
        echo 'Date      : ' . $media->getDate()->format('Y-m-d h:i:s') . "\n";
        echo '============================' . "\n";
    }

    // Second Page

    $api->setEndCursor($feed->getEndCursor());

    sleep(1); // avoir 429 Rate limit from Instagram

    $feed = $api->getFeed();

    echo "\n\n";
    echo '============================' . "\n";
    echo 'Medias second page : ' . "\n";
    echo '============================' . "\n\n";

    /** @var \Instagram\Hydrator\Component\Media $media */
    foreach ($feed->getMedias() as $media) {
        echo 'ID        : ' . $media->getId() . "\n";
        echo 'Caption   : ' . $media->getCaption() . "\n";
        echo 'Link      : ' . $media->getLink() . "\n";
        echo 'Likes     : ' . $media->getLikes() . "\n";
        echo 'Date      : ' . $media->getDate()->format('Y-m-d h:i:s') . "\n";
        echo '============================' . "\n";
    }

    // And etc...

} Catch (\Instagram\Exception\InstagramException $exception) {
    print_r($exception->getMessage());
}

// Second page