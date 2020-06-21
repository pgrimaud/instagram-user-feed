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

    $userId    = 1518284433;
    $endCursor = 'QVFDSk9BZU8tUXVpcUw5d0VUZER0QVlGX1BNYjE2clRFajNnZk4xelY1b2xlZ2xFdVE1eWJjblViMEJMTzBhNVRiQ3J5b05LanVHcWVjbWZEZ1RpdmNSRQ==';
    $profile   = $api->getMoreMediasWithCursor($userId, $endCursor);

    printMedias($profile->getMedias());
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
