# Instagram user feed PHP

[![Build Status](https://travis-ci.org/pgrimaud/instagram-user-feed.svg?branch=master)](https://travis-ci.org/pgrimaud/instagram-user-feed)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Code Climate](https://codeclimate.com/github/pgrimaud/instagram-user-feed/badges/gpa.svg)](https://codeclimate.com/github/pgrimaud/instagram-user-feed)
[![Test Coverage](https://codeclimate.com/github/pgrimaud/instagram-user-feed/badges/coverage.svg)](https://codeclimate.com/github/pgrimaud/instagram-user-feed/coverage)
[![Issue Count](https://codeclimate.com/github/pgrimaud/instagram-user-feed/badges/issue_count.svg)](https://codeclimate.com/github/pgrimaud/instagram-user-feed)

## Installation

```
composer require pgrimaud/instagram-user-feed
```
1. Visit [http://instagram.pixelunion.net/](http://instagram.pixelunion.net/) and create an access token

2. The first part of the access token is your User Id

```
$api->setAccessToken('1234578.abcabc.abcabcabcabcabcabcabcabcabcabc');
$api->setUserId(1234578);
```




## Warning

**2018-04-08 : Due to changes of the Instagram API (again...), you must upgrade to version ^3.0**

~~2018-03-16 : Due to changes of the Instagram API, you must upgrade to version ^2.1~~

## Usage

### Retrieve user data only

```php
$api = new Api();

// for user data, userName is mandatory!
$api->setUserName('pgrimaud');

$api->retrieveUserData(true);

$feed = $api->getFeed();

print_r($feed);

```

```php
Instagram\Hydrator\Feed Object
(
    [id] => 184263228
    [userName] => pgrimaud
    [fullName] => Pierre G
    [biography] => Gladiator retired - ESGI 14
    [isVerified] =>
    [followers] => 337
    [following] => 113
    [profilePicture] => https://scontent-cdg2-1.cdninstagram.com/vp/faf7cfb2f6ea29b57d3032717d8789bf/5B34242E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [profilePictureHd] => https://scontent-cdg2-1.cdninstagram.com/vp/faf7cfb2f6ea29b57d3032717d8789bf/5B34242E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [externalUrl] => https://p.ier.re/
    [mediaCount] => 30
    [hasNextPage] =>
    [medias] => Array
        (
        )

)
```

### Retrieve media data only

```php
$api = new Api();

// for media, userId is mandatory!
$api->setUserId(184263228);

$api->retrieveMediaData(true);

$feed = $api->getFeed();

print_r($feed);

```

```php
Instagram\Hydrator\Feed Object
(
    [id] =>
    [userName] =>
    [fullName] =>
    [biography] =>
    [isVerified] =>
    [followers] => 0
    [following] => 0
    [profilePicture] =>
    [profilePictureHd] =>
    [externalUrl] =>
    [mediaCount] => 0
    [hasNextPage] => 1
    [medias] => Array
        (
            [0] => Instagram\Hydrator\Media Object
                (
                    [id] => 1676900800864278214
                    [typeName] => GraphImage
                    [height] => 1080
                    [width] => 1080
                    [thumbnailSrc] => https://scontent-cdg2-1.cdninstagram.com/vp/90b54127c36ce17fefee861606db228e/5B430967/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg
                    [thumbnailResources] => Array
                        (
                            [0] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/9f2fd42a43d9a8540db2a413b6663e66/5B42D463/t51.2885-15/s150x150/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 150
                                    [height] => 150
                                )

                            [1] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/9a66fc162a7fece72d26c7de2fb51b01/5B32FE5C/t51.2885-15/s240x240/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 240
                                    [height] => 240
                                )

                            [2] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/5e70d6b0e034320a39d5357a8398484d/5B458D24/t51.2885-15/s320x320/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 320
                                    [height] => 320
                                )

                            [3] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/cb231fb56464841daf64935bd1551707/5B3F4AA2/t51.2885-15/s480x480/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 480
                                    [height] => 480
                                )

                            [4] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/90b54127c36ce17fefee861606db228e/5B430967/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 640
                                    [height] => 640
                                )

                        )

                    [link] => https://www.instagram.com/p/BdFjGTPFVbG/
                    [code] => BdFjGTPFVbG
                    [date] => DateTime Object
                        (
                            [date] => 2017-12-24 14:29:34.000000
                            [timezone_type] => 3
                            [timezone] => Europe/Paris
                        )

                    [displaySrc] => https://scontent-cdg2-1.cdninstagram.com/vp/89ddb8f8c3466e7436c29d041ece4300/5B4AF306/t51.2885-15/e35/25024600_726096737595175_9198105573181095936_n.jpg
                    [caption] => 🎄🎅💸🙃 #casino #monaco
                    [comments] => 0
                    [likes] => 29
                )
                ...
        )
)
```

### Retrieve all data

```php
$api = new Api();

// for user data, userName is mandatory!
$api->setUserName('pgrimaud');

// for media, userId is mandatory!
$api->setUserId(184263228);

$api->retrieveUserData(true);
$api->retrieveMediaData(true);

$feed = $api->getFeed();

print_r($feed);

```

```php
Instagram\Hydrator\Feed Object
(
    [id] => 184263228
    [userName] => pgrimaud
    [fullName] => Pierre G
    [biography] => Gladiator retired - ESGI 14
    [isVerified] =>
    [followers] => 337
    [following] => 113
    [profilePicture] => https://scontent-cdg2-1.cdninstagram.com/vp/faf7cfb2f6ea29b57d3032717d8789bf/5B34242E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [profilePictureHd] => https://scontent-cdg2-1.cdninstagram.com/vp/faf7cfb2f6ea29b57d3032717d8789bf/5B34242E/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [externalUrl] => https://p.ier.re/
    [mediaCount] => 30
    [hasNextPage] => 1
    [medias] => Array
        (
            [0] => Instagram\Hydrator\Media Object
                (
                    [id] => 1676900800864278214
                    [typeName] => GraphImage
                    [height] => 1080
                    [width] => 1080
                    [thumbnailSrc] => https://scontent-cdg2-1.cdninstagram.com/vp/90b54127c36ce17fefee861606db228e/5B430967/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg
                    [thumbnailResources] => Array
                        (
                            [0] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/9f2fd42a43d9a8540db2a413b6663e66/5B42D463/t51.2885-15/s150x150/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 150
                                    [height] => 150
                                )

                            [1] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/9a66fc162a7fece72d26c7de2fb51b01/5B32FE5C/t51.2885-15/s240x240/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 240
                                    [height] => 240
                                )

                            [2] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/5e70d6b0e034320a39d5357a8398484d/5B458D24/t51.2885-15/s320x320/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 320
                                    [height] => 320
                                )

                            [3] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/cb231fb56464841daf64935bd1551707/5B3F4AA2/t51.2885-15/s480x480/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 480
                                    [height] => 480
                                )

                            [4] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/vp/90b54127c36ce17fefee861606db228e/5B430967/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 640
                                    [height] => 640
                                )

                        )

                    [link] => https://www.instagram.com/p/BdFjGTPFVbG/
                    [code] => BdFjGTPFVbG
                    [date] => DateTime Object
                        (
                            [date] => 2017-12-24 14:29:34.000000
                            [timezone_type] => 3
                            [timezone] => Europe/Paris
                        )

                    [displaySrc] => https://scontent-cdg2-1.cdninstagram.com/vp/89ddb8f8c3466e7436c29d041ece4300/5B4AF306/t51.2885-15/e35/25024600_726096737595175_9198105573181095936_n.jpg
                    [caption] => 🎄🎅💸🙃 #casino #monaco
                    [comments] => 0
                    [likes] => 29
                )
                ...
        )
)        
```

### Paginate
If you want to use paginate for user media data, retrieve `endCursor` from previous call and add it to your next call.

```php
// First call :

$api = new Api();
$api->setUserId(184263228);
$api->retrieveMediaData(true);

$feed = $api->getFeed();

$endCursor = $feed->getEndCursor();

// Second call : 

$api = new Api();
$api->setUserId(184263228);
$api->retrieveMediaData(true);
$api->setEndCursor($endCursor);

$feed = $api->getFeed();

// And etc...
```


