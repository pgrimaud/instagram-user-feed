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
    $followersFeed = $api->getMoreFollowers(1518284433, 'QVFBaTBMSVpINlVMdEU1aTl5aEpWel9uTDJrcWFKa3lWSTFMMjIyWktqanNxNGxhTmY3V3NrRHVQWElGeUZCYk9jcFlrVTdySHdrUVRsazRCQjdBaV8zeg==');

    printUsers($followersFeed->getUsers());

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
