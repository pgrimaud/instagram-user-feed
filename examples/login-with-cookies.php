<?php

declare(strict_types=1);

use Instagram\Api;
use Instagram\Auth\Session;
use Instagram\Utils\CacheHelper;
use Instagram\Exception\{InstagramException, InstagramAuthException};
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use GuzzleHttp\Cookie\{SetCookie, CookieJar};

require realpath(dirname(__FILE__)) . '/../vendor/autoload.php';
$credentials = include_once realpath(dirname(__FILE__)) . '/credentials.php';

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

/**
 * Make sure you are logged in with the login() method before using example "1. Get cookies from file"
 * See examples in https://github.com/pgrimaud/instagram-user-feed/blob/5b2358f9918b84c11b7d193f7f3205df87b35793/examples/profile.php#L17-L18
 */

/** 1. Get cookies from file */
$sessionId = $cachePool->getItem(Session::SESSION_KEY . '.' . CacheHelper::sanitizeUsername($credentials->getLogin()))
                       ->get()
                       ->getCookieByName('sessionId');

// Generate CookieJar from instagram cookie 'sessionid'
$cookieJar = new CookieJar(false, [$sessionId]);

/** 2. Insert cookies manually
$sessionId = new SetCookie([
    "Name"     => "sessionid",
    "Value"    => "YOUR_INSTAGRAM_SESSIONID",
    "Domain"   => ".instagram.com",
    "Path"     => "/",
    "Expires"  => "YOUR_INSTAGRAM_SESSIONID_EXPIRES",
    "Max-Age"  => "31536000",
    "Secure"   => true,
    "Discard"  => false,
    "HttpOnly" => true,
]);

// Generate CookieJar from instagram cookie 'sessionid'
$cookieJar = new CookieJar(false, [$sessionId]);
*/

try {
    $api = new Api();
    
    // Optionals for set user agent and language 
    $api->setUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.57 Safari/537.36');
    $api->setLanguage('id-ID');
    
    $api->loginWithCookies($cookieJar);
    
    $profile = $api->getProfile('robertdowneyjr');
    
    dd($profile);
} catch (InstagramAuthException $e) {
    print_r($e->getMessage());
} catch (InstagramException $e) {
    print_r($e->getMessage());
}
