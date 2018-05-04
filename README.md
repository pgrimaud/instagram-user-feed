# Instagram user feed PHP

[![Build Status](https://travis-ci.org/pgrimaud/instagram-user-feed.svg?branch=master)](https://travis-ci.org/pgrimaud/instagram-user-feed)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Code Climate](https://codeclimate.com/github/pgrimaud/instagram-user-feed/badges/gpa.svg)](https://codeclimate.com/github/pgrimaud/instagram-user-feed)
[![Test Coverage](https://codeclimate.com/github/pgrimaud/instagram-user-feed/badges/coverage.svg)](https://codeclimate.com/github/pgrimaud/instagram-user-feed/coverage)
[![Issue Count](https://codeclimate.com/github/pgrimaud/instagram-user-feed/badges/issue_count.svg)](https://codeclimate.com/github/pgrimaud/instagram-user-feed)

## Information
This library offers 2 packages to retrieve your or any Instagram feed without oAuth for PHP.

## Version ^4.0
This version can retrieve **YOUR** Instagram feed using an **access token**.

- [Installation](#installation-of-version-40)
- [Usage](#usage-of-version-40)
- [Paginate](#paginate-for-version-40)

## Version ^5.0
This version can retrieve **ANY** Instagram feed using **web scrapping**.

- [Installation](#installation-of-version-50)
- [Usage](#usage-of-version-50)
- [Paginate](#paginate-for-version-50)

## Changelog

**2018-04-20 : Release of version ^5.0 in parallel of version ^4.0 which still working. (Kudos for [@jannejava](https://github.com/jannejava) and [@cookieguru](https://github.com/cookieguru)**

~~2018-04-17 : Now fetching data with screen scraping (thanks [@cookieguru](https://github.com/cookieguru)), please upgrade to version ^5.0~~

~~2018-04-16 : Now fetching data with access token, only for your account (thanks [@jannejava](https://github.com/jannejava)), please upgrade to version ^4.0~~

~~2018-04-08 : Due to changes of the Instagram API (again...), you must upgrade to version ^3.0~~

~~2018-03-16 : Due to changes of the Instagram API, you must upgrade to version ^2.1~~

# Installation of version ^4.0

```
composer require pgrimaud/instagram-user-feed "^4.0"
```

1. Visit [http://instagram.pixelunion.net/](http://instagram.pixelunion.net/) and create an access token

2. The first part of the access token is your User Id

```
$api = new \Instagram\Api();

$api->setAccessToken('1234578.abcabc.abcabcabcabcabcabcabcabcabcabc');
$api->setUserId(1234578);
```

**Seems like you can only access your own media until 2020.**

## Usage of version ^4.0

```php
$api = new \Instagram\Api();

$feed = $api->getFeed('pgrimaud');

print_r($feed);

```

```php
Instagram\Hydrator\Feed Object
(
    [id] => 184263228
    [userName] => pgrimaud
    [fullName] => Pierre G
    [biography] => Gladiator retired - ESGI 14
    [followers] => 342
    [following] => 114
    [profilePicture] => https://scontent.cdninstagram.com/vp/f49bc1ac9af43314d3354b4c4a987c6d/5B5BB12E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [externalUrl] => https://p.ier.re/
    [mediaCount] => 33
    [hasNextPage] => 1
    [maxId] => 1230468487398454311_184263228
    [medias] => Array
        (
            [0] => Instagram\Hydrator\Media Object
                (
                    [id] => 1758133053345287778_184263228
                    [typeName] => image
                    [height] => 640
                    [width] => 640
                    [thumbnailSrc] => https://scontent.cdninstagram.com/vp/e64c51de7f5401651670fd0bbdfd9837/5B69AF2B/t51.2885-15/s150x150/e35/30604700_183885172242354_7971196573931536384_n.jpg
                    [link] => https://www.instagram.com/p/BhmJLJwhM5i/
                    [date] => DateTime Object
                        (
                            [date] => 2018-04-15 17:23:33.000000
                            [timezone_type] => 3
                            [timezone] => Europe/Paris
                        )

                    [displaySrc] => https://scontent.cdninstagram.com/vp/dd39e08d3c740e764c61bc694d36f5a7/5B643B2F/t51.2885-15/s640x640/sh0.08/e35/30604700_183885172242354_7971196573931536384_n.jpg
                    [caption] => 
                    [comments] => 2
                    [likes] => 14
                )
            ...
        )
)
```

## Paginate for version ^4.0
If you want to use paginate, retrieve `maxId` from previous call and add it to your next call.

```php
// First call :

$api = new Api();
$api->setUserId(184263228);
$api->setAccessToken('1234578.abcabc.abcabcabcabcabcabcabcabcabcabc');

$feed = $api->getFeed();

$endCursor = $feed->getMaxId();

// Second call : 

$api = new Api();
$api->setUserId(184263228);
$api->setAccessToken('1234578.abcabc.abcabcabcabcabcabcabcabcabcabc');
$api->setMaxId('1230468487398454311_184263228');

$feed = $api->getFeed();

// And etc...
```
___

# Installation of version ^5.0

```
composer require pgrimaud/instagram-user-feed "^5.0"
```

## Usage of version ^5.0

```php
$cache = new Instagram\Storage\CacheManager('/path/to/your/cache/folder');
$api   = new Instagram\Api($cache);
$api->setUserName('pgrimaud');

$feed = $api->getFeed();

print_r($feed):

```

```php
Instagram\Hydrator\Component\Feed Object
(
    [id] => 184263228
    [userName] => pgrimaud
    [fullName] => Pierre G
    [biography] => Gladiator retired - ESGI 14'
    [followers] => 342
    [following] => 114
    [profilePicture] => https://scontent-cdg2-1.cdninstagram.com/vp/f49bc1ac9af43314d3354b4c4a987c6d/5B5BB12E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [externalUrl] => https://p.ier.re/
    [mediaCount] => 33
    [medias] => Array
        (
            [0] => Instagram\Hydrator\Component\Media Object
                (
                    [id] => 1766843207939161485
                    [typeName] => GraphImage
                    [height] => 1080
                    [width] => 1080
                    [thumbnailSrc] => https://scontent-cdg2-1.cdninstagram.com/vp/94afe3a6f9cc26a03c2ce29321b57074/5B81AE5C/t51.2885-15/s640x640/sh0.08/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                    [link] => https://www.instagram.com/p/BiFFolXBn2N/
                    [date] => DateTime Object
                        (
                            [date] => 2018-04-27 17:49:05.000000
                            [timezone_type] => 3
                            [timezone] => Europe/Paris
                        )

                    [displaySrc] => https://scontent-cdg2-1.cdninstagram.com/vp/fefa950b40d2d469a9db457df43a23f9/5B936A76/t51.2885-15/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                    [caption] => 🔮
                    [comments] => 0
                    [likes] => 25
                    [thumbnails] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/aa2b29265c6b29465921df833b65010e/5B996421/t51.2885-15/s150x150/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                                    [config_width] => 150
                                    [config_height] => 150
                                )

                            [1] => stdClass Object
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/84359fab575fcb75b672baa83dcf914d/5B9897B5/t51.2885-15/s240x240/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                                    [config_width] => 240
                                    [config_height] => 240
                                )

                            [2] => stdClass Object
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/e900d5b8fdd60f9f1ff55d69d4a77f7f/5B977F11/t51.2885-15/s320x320/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                                    [config_width] => 320
                                    [config_height] => 320
                                )

                            [3] => stdClass Object
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/faec6a654dc3ca8c8752e784d808afe2/5B885F49/t51.2885-15/s480x480/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                                    [config_width] => 480
                                    [config_height] => 480
                                )

                            [4] => stdClass Object
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/94afe3a6f9cc26a03c2ce29321b57074/5B81AE5C/t51.2885-15/s640x640/sh0.08/e35/30601606_2078730052397861_6535696870134513664_n.jpg
                                    [config_width] => 640
                                    [config_height] => 640
                                )

                        )

                )

        )
        
        ...
        
    [endCursor] => AQBkklLNRIkvdOUFDHvLEZrssIcYn2TauR6cpvDgxiGJZq8mHb8ZFWNVwql1W78We0aOgfJZyQDF32yoP_h2zRKZ2iRY6zVJdDaLaGfUU23iXA
)

```

## Paginate for version ^5.0
If you want to use paginate, retrieve `endCursor` from previous call and add it to your next call.

```php
// Initialization

$cache = new Instagram\Storage\CacheManager('/path/to/your/cache/folder');
$api   = new Instagram\Api($cache);
$api->setUserName('pgrimaud');

// First call :

$feed = $api->getFeed();

// Second call : 

$endCursor = $feed->getEndCursor();
$api->setEndCursor($endCursor);
$feed = $api->getFeed();

// And etc...
```
