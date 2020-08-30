<?php

declare(strict_types=1);

use Instagram\Api;
use Instagram\Exception\InstagramException;

use Instagram\Model\User;
use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require realpath(dirname(__FILE__)) . '/../vendor/autoload.php';
$credentials = include_once realpath(dirname(__FILE__)) . '/credentials.php';

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

try {
    $api = new Api($cachePool);
    $api->login($credentials->getLogin(), $credentials->getPassword());

    // 1518284433 is robertdowneyjr's account id
    $userId = 1518284433;

    $followersFeed = $api->getFollowers($userId);

    printUsers($followersFeed->getUsers());

    do {
        $followersFeed = $api->getMoreFollowers($userId, $followersFeed->getEndCursor());

        printUsers($followersFeed->getUsers());

        // avoid 429 Rate limit from Instagram
        sleep(1);
    } while ($followersFeed->hasNextPage());

} catch (InstagramException $e) {
    print_r($e->getMessage());
} catch (CacheException $e) {
    print_r($e->getMessage());
}

function printUsers(array $users)
{
    /** @var User $user */
    foreach ($users as $user) {
        echo $user->getUserName() . PHP_EOL;
    }
}
