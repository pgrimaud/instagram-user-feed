# Instagram user feed PHP

[![Build Status](https://travis-ci.org/pgrimaud/instagram-user-feed.svg?branch=master)](https://travis-ci.org/pgrimaud/instagram-user-feed)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Coverage Status](https://coveralls.io/repos/github/pgrimaud/instagram-user-feed/badge.svg)](https://coveralls.io/github/pgrimaud/instagram-user-feed)

[![Minimum PHP Version](https://img.shields.io/packagist/php-v/pgrimaud/instagram-user-feed.svg?maxAge=3600)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Last version](https://img.shields.io/packagist/v/pgrimaud/instagram-user-feed?maxAge=3600)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Total Downloads](https://poser.pugx.org/pgrimaud/instagram-user-feed/downloads)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Monthly Downloads](https://poser.pugx.org/pgrimaud/instagram-user-feed/d/monthly)](https://packagist.org/packages/pgrimaud/instagram-user-feed)

## Information
Easily fetch your or any Instagram feed without OAuth for PHP

## Features

- Fetch profile data of user
- Fetch medias of user
- Fetch stories of user
- Fetch highlights stories of user

**⚠️ Version ^5.0 is no more maintained.**

## Version ^6.0
This version can retrieve **ANY** Instagram feed using **web scrapping**.

- [Installation](#installation)
- [Usage](#usage)
- [Medias paginate](#paginate)
- [Stories](#stories)
- [Examples](https://github.com/pgrimaud/instagram-user-feed/tree/master/examples)

## Changelog

**2020-05-21 : Version 6.1 is released. New feature: fetch stories and highlights stories.**

**2020-05-21 : Version 6.0 is released. Please upgrade from ^5.0 for cookies session stability.** 

# Installation

```
composer require pgrimaud/instagram-user-feed
```

## Usage

**New in 6.0** Cache : This library implements PSR-6 for greatest interoperability.

```php
<?php

use Instagram\Api;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

$api = new Api($cachePool);
$api->login('username', 'password'); // mandatory
$profile = $api->getProfile('robertdowneyjr');

echo $profile->getUserName(); // robertdowneyjr

echo $profile->getFullName(); // Robert Downey Jr. Official
```

### Basic usage : 

```php
<?php

$api = new Api($cachePool);
$api->login('username', 'password');

$profile = $api->getProfile('robertdowneyjr');

print_r($profile);
```

```php
Instagram\Hydrator\Component\Feed Object
(
    [id] => 1518284433
    [userName] => robertdowneyjr
    [fullName] => Robert Downey Jr. Official
    [biography] => @officialfootprintcoalition @coreresponse
    [followers] => 46382057
    [following] => 50
    [profilePicture] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=h2zGWoshNjUAX9ze3jb&oh=cf6441cfc3f258da3bf4cfef29686c7d&oe=5EEEC338
    [externalUrl] => http://coreresponse.org/covid19
    [private] => 
    [verified] => 1
    [mediaCount] => 453
        (
            [0] => Instagram\Model\InstagramMedia Object
                (
                    [id] => 2307655221969878423
                    [typeName] => GraphImage
                    [height] => 1350
                    [width] => 1080
                    [thumbnailSrc] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/sh0.08/e35/c0.180.1440.1440a/s640x640/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=1b293215142d407faca46a2fd28eab71&oe=5EF0EBDF
                    [link] => https://www.instagram.com/p/CAGcDKplv2X/
                    [date] => DateTime Object
                        (
                            [date] => 2020-05-12 22:06:01.000000
                            [timezone_type] => 3
                            [timezone] => Europe/Paris
                        )

                    [displaySrc] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/e35/p1080x1080/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=6c19ddef96fdc07d7926b05e36cb2bed&oe=5EEED2CE
                    [caption] => The sweetest things are worth waiting for…Susan and I are producing a @Netflix original series, Sweet Tooth, based on the comic by @Jefflemire. Can’t wait to share it with you all. 🦌 👦 @NXonNetflix @warnerbrostv #SweetTooth
                    [comments] => 3308
                    [likes] => 687988
                    [thumbnails] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [src] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/e35/c0.180.1440.1440a/s150x150/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=24b300201afc0e0c82166c6288e0ed5b&oe=5EF00196
                                    [config_width] => 150
                                    [config_height] => 150
                                )

                            [1] => stdClass Object
                                (
                                    [src] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/e35/c0.180.1440.1440a/s240x240/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=203d0a3d01d77a2978739c96eb67e607&oe=5EEF6DE0
                                    [config_width] => 240
                                    [config_height] => 240
                                )

                            [2] => stdClass Object
                                (
                                    [src] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/e35/c0.180.1440.1440a/s320x320/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=7b9cee64460e1c9c501e59621e6ccfb2&oe=5EF18BE6
                                    [config_width] => 320
                                    [config_height] => 320
                                )

                            [3] => stdClass Object
                                (
                                    [src] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/e35/c0.180.1440.1440a/s480x480/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=f3d8c31eca2d3c3ab6653b3ed3ebe4f4&oe=5EEFEAC0
                                    [config_width] => 480
                                    [config_height] => 480
                                )

                            [4] => stdClass Object
                                (
                                    [src] => https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-15/sh0.08/e35/c0.180.1440.1440a/s640x640/96225997_178111910111734_5886065436455432375_n.jpg?_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_cat=1&_nc_ohc=GqcYpSEbz8gAX_GF1Ep&oh=1b293215142d407faca46a2fd28eab71&oe=5EF0EBDF
                                    [config_width] => 640
                                    [config_height] => 640
                                )

                        )

                    [location] => 
                    [video] => 
                    [videoViewCount] => 0
                )
        ...
        
    [endCursor:Instagram\Model\InstagramProfile:private] => QVFEblBGclVyOEtCMmRLZkVxUUdVbmhsYXNMZmMmplNWtZRkJnRnZOSUdMM1BDRmt3ZA==
)

```

## Paginate
If you want to use paginate on medias, just call `getMoreMedias` method.

```php
<?php

$api = new Api($cachePool);
$api->login($credentials->getLogin(), $credentials->getPassword());

$profile = $api->getProfile('twhiddleston');

print_r($profile->getMedias); // 12 first medias

do {
    $profile = $api->getMoreMedias($profile);
    print_r($profile->getMedias); // 12 more medias

    // avoid 429 Rate limit from Instagram
    sleep(1);
} while ($profile->hasMoreMedias());
```

## Stories

```php
<?php

use Instagram\Api;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$cachePool = new FilesystemAdapter('Instagram', 0, __DIR__ . '/../cache');

$api = new Api($cachePool);
$api->login('username', 'password'); // mandatory

$profile = $api->getProfile('starwars'); // we need instagram user id
sleep(1);
$feedStories = $api->getStories($profile->getId());

$stories = $feedStories->getStories();

print_r($stories);
```

# Feedback

You found a bug? You need a new feature? You can [create an issue](https://github.com/pgrimaud/horaires-ratp-api/issues) if needed or contact me on [Twitter](https://twitter.com/pgrimaud_).

# License

Licensed under the terms of the MIT License.
