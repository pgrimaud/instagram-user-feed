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

## Usage

```php
$api = new Api();
$api->setUserName('pgrimaud');

// for paginate
//$api->setMaxId(1676900800864278214);

$feed = $api->getFeed();

print_r($feed);

```

```
Instagram\Hydrator\Feed Object
(
    [id] => 184263228
    [userName] => pgrimaud
    [fullName] => Pierre G
    [biography] => Gladiator retired - ESGI 14'
    [isVerified] => 
    [followers] => 336
    [following] => 110
    [profilePicture] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
    [profilePictureHd] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-19/10483606_1498368640396196_604136733_a.jpg
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
                    [thumbnailSrc] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg
                    [thumbnailResources] => Array
                        (
                            [0] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/s150x150/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 150
                                    [height] => 150
                                )

                            [1] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/s240x240/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 240
                                    [height] => 240
                                )

                            [2] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/s320x320/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 320
                                    [height] => 320
                                )

                            [3] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/s480x480/e35/25024600_726096737595175_9198105573181095936_n.jpg
                                    [width] => 480
                                    [height] => 480
                                )

                            [4] => Array
                                (
                                    [src] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/25024600_726096737595175_9198105573181095936_n.jpg
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

                    [displaySrc] => https://scontent-cdg2-1.cdninstagram.com/t51.2885-15/e35/25024600_726096737595175_9198105573181095936_n.jpg
                    [caption] => 🎄🎅💸🙃 #casino #monaco
                    [comments] => 0
                    [likes] => 28
                )

            ...

        )

)

```
