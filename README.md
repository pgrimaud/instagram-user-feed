# Instagram user feed PHP
[![Build Status](https://github.com/pgrimaud/instagram-user-feed/actions/workflows/main.yaml/badge.svg)](https://github.com/pgrimaud/instagram-user-feed/actions)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Coverage Status](https://coveralls.io/repos/github/pgrimaud/instagram-user-feed/badge.svg?branch=master)](https://coveralls.io/github/pgrimaud/instagram-user-feed?branch=master)

[![Minimum PHP Version](https://img.shields.io/packagist/php-v/pgrimaud/instagram-user-feed.svg?maxAge=3600)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Last version](https://img.shields.io/packagist/v/pgrimaud/instagram-user-feed?maxAge=3600)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Total Downloads](https://poser.pugx.org/pgrimaud/instagram-user-feed/downloads)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Visitor badge](https://visitor-badge.glitch.me/badge?page_id=pgrimaud.instagram-user-feed)](https://github.com/pgrimaud/instagram-user-feed)

<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-28-orange.svg)](#contributors)
<!-- ALL-CONTRIBUTORS-BADGE:END -->

> **Warning**
> 2022-11-09 - Instagram changed his DOM AGAIN... Please update to this version 6.16.4

> **Warning**
> 2022-06-01 - Instagram changed his DOM content and profile data loading. Please update to this version 6.15.0

## Information
Easily fetch any Instagram feed and more without OAuth for PHP.

If you like or use this package, please share your love by starring this repository, follow [@pgrimaud](https://github.com/pgrimaud) or [become a sponsor](https://github.com/sponsors/pgrimaud). 🙏💓

## Features

- Fetch profile data of user
- Fetch medias of user
- Fetch stories of user
- Fetch highlights stories of user
- Fetch detailed post of user
- Fetch feed of followers
- Fetch feed of followings
- Follow or unfollow users
- Like or unlike posts
- Fetch posts of hashtag
- Fetch comments of a post
- Fetch live-streaming info
- Fetch Reels
- Fetch IGTV
- Fetch medias where a user has been tagged on
- Fetch HD profile picture
- Post a message to a media
- **NEW: Login with cookies 🎉**
- **NEW: Fetch connected user timeline 🎉**

This version can retrieve **ANY** Instagram feed using **web scraping**.

- [Installation](#installation)
- [Usage](#usage)
- [Medias paginate](#paginate)
- [Stories](#stories)
- [Examples](https://github.com/pgrimaud/instagram-user-feed/tree/master/examples)

**⚠️ Version ^5.0 is no more maintained. ⚠️**

# Installation

```
composer require pgrimaud/instagram-user-feed
```

# Changelog

**v6.16** - 2022-08-02:

- Login with cookies. Thanks to [nsmle](https://github.com/nsmle) (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/login-with-cookies.php)) 🎉.
- Fetch connected user timeline. Thanks to [nsmle](https://github.com/nsmle) (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/timeline.php)) 🎉.
- Add full support for Symfony 6
- Add checks on missing properties to avoid errors

**v6.15** - 2022-06-02:

- ⚠️ ⚠️ Fix profile endpoint. Please update to this last version!

**v6.12** - 2021-09-20:

- Add method to post message on a media (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/post-comment.php)) 🎉.
- Add method to get HD profile picture (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/profile-alternative.php)) 🎉.

**v6.11** - 2021-09-13:

- Add method to fetch medias where a user has been tagged on (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/tagged-medias.php)) 🎉.

**v6.10** - 2021-09-03:

- Add method to fetch IGTV (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/igtv.php)) 🎉.
- Add method to fetch Reels (example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/reels.php)) 🎉.
- Split dirty tests in multiple files.

**v6.9** - 2021-09-02:

- Add method to fetch Instagram live-streaming info. Thanks to [David-Kurniawan](https://github.com/David-Kurniawan)
- Minor fixes.

**[v6.8](#version-68-strict-origin-when-cross-origin)** - 2021-07-08:

- Add video url in media feed. Thanks to [@Nispeon](https://github.com/Nispeon)
- Add helper to download media (ref to [#205 : strict-origin-when-cross-origin](https://github.com/pgrimaud/instagram-user-feed/issues/205)). Thanks to [@Nispeon](https://github.com/Nispeon)
- Minor changes and improvements.

**v6.7** - 2021-01-31:

- New feature: get **post** of **hashtag**.
- New feature: get **comments** of **post**.
- Add support for PHP 8.
- Minor changes and improvements.

**v6.6** - 2020-10-06: 

- New feature: **like** and **unlike** posts. 
- Retrieval **#hashtags** and **@mentions** from stories.
- Minor changes and improvements.

**v6.5** - 2020-09-14: 

- New feature: follow and unfollow users. Thanks to [@David-Kurniawan](https://github.com/David-Kurniawan))
- Minor fixes.

**v6.4** - 2020-08-30: 

- New feature: fetch followers and followings feeds. Thanks to [@David-Kurniawan](https://github.com/David-Kurniawan))
- Minor improvements.

**[v6.3](#version-63-checkpoint-challenge-bypass)** -2020-07-03: 

- Add checkpoint challenge bypass using IMAP configuration.

**v6.2** - 2020-06-01: 

- Improve medias crawling && cache constraints.

**v6.1** - 2020-05-21: 

- New feature: Fetch stories and highlights stories.

**[v6.0](#version-60-login)** - 2020-05-20

- Please upgrade from ^5.0 for cookies session stability.

## Version ^6.8: strict-origin-when-cross-origin

Facebook added a new CORS policy, and you can't display the data directly.

You can now download media on your storage or server to serve it directly on your website. You can find an example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/medias-download.php).

**Example:**

```php
<?php

// include vendor & classes

// random picture from instagram
$url = 'https://scontent-cdt1-1.cdninstagram.com/v/t51.2885-19/s150x150/156309873_1632221153646196_1273891214497323498_n.jpg?tp=1&_nc_ht=scontent-cdt1-1.cdninstagram.com&_nc_ohc=vbxGNZrjPmUAX8CIDdC&edm=ABfd0MgBAAAA&ccb=7-4&oh=ae5811c07f0e73b945eb203cd58c2101&oe=60EDD1EE&_nc_sid=7bff83'

// define directory
$downloadDir = __DIR__ . '/../assets'; // change it

$fileName = MediaDownloadHelper::downloadMedia($url, $downloadDir);
// file was downloaded here : __DIR__ . '/../assets/v-t51.2885-19-s150x150-156309873_1632221153646196_1273891214497323498_n.jpg
```

## Version ^6.3: Checkpoint challenge bypass

Some people may have trouble to login with this library. It happens for "old" Instagram accounts or if you're using it on some shared hosting (not all, I don't know why...).

You can now automatically bypass the checkpoint challenge. (email verification with code). You can find an example [here](https://github.com/pgrimaud/instagram-user-feed/blob/master/examples/checkpoint-challenge.php).

**Tips: you should create a dummy instagram account using a dummy e-mailbox to use this feature.**

### How it works?

1. The lib will try to login
2. Got 400 error "checkpoint_required"
3. Trigger email verification
4. Connect to your email inbox using IMAP credentials
5. Wait for Instagram verification email
6. Parse verification code from email
7. Make a request to instagram with this code to complete verification
8. Verification is done, then **save session automatically***

*Saving session with cache driver is very important here. The Instagram session is valid for... **1 YEAR**. So in theory, using a cache driver and one account will trigger only one real login to Instagram then reusing session for a long time.

Thanks to [@ibnux](https://github.com/ibnux) and [@eldark](https://github.com/eldark) for help 🎉

## Version ^6.0: Login

In version ^6.0, login is now **mandatory**, it will save cookies (session) to simulate "real" requests to Instagram.

They improve their bot detection and without real session data in the headers requests, your IP could be easily soft-ban by Instagram.

Then, you can't fetch a lot of data without login.

**Tips: you just have to create or use a dummy account to use easily this package.**

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

print_r($profile->getMedias()); // 12 first medias

do {
    $profile = $api->getMoreMedias($profile);
    print_r($profile->getMedias()); // 12 more medias

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

$profile = $api->getProfile('starwars'); // we need instagram username
sleep(1);
$feedStories = $api->getStories($profile->getId());

$stories = $feedStories->getStories();

print_r($stories);
```

# Contributors

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center"><a href="https://github.com/pgrimaud"><img src="https://avatars1.githubusercontent.com/u/1866496?v=4?s=100" width="100px;" alt="Pierre Grimaud"/><br /><sub><b>Pierre Grimaud</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=pgrimaud" title="Code">💻</a></td>
      <td align="center"><a href="https://janostlund.com"><img src="https://avatars3.githubusercontent.com/u/543616?v=4?s=100" width="100px;" alt="Jan Östlund"/><br /><sub><b>Jan Östlund</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=jannejava" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/cookieguru"><img src="https://avatars0.githubusercontent.com/u/1888809?v=4?s=100" width="100px;" alt="Tim Bond"/><br /><sub><b>Tim Bond</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=cookieguru" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/Dlinny"><img src="https://avatars0.githubusercontent.com/u/1443580?v=4?s=100" width="100px;" alt="Dlinny"/><br /><sub><b>Dlinny</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/issues?q=author%3ADlinny" title="Bug reports">🐛</a></td>
      <td align="center"><a href="https://github.com/renedekat"><img src="https://avatars0.githubusercontent.com/u/8975204?v=4?s=100" width="100px;" alt="René"/><br /><sub><b>René</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/issues?q=author%3Arenedekat" title="Bug reports">🐛</a></td>
      <td align="center"><a href="https://t.me/ikiselev1989"><img src="https://avatars1.githubusercontent.com/u/22061871?v=4?s=100" width="100px;" alt="ikiselev1989"/><br /><sub><b>ikiselev1989</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/issues?q=author%3Aikiselev1989" title="Bug reports">🐛</a></td>
      <td align="center"><a href="http://pezhvak.imvx.org/"><img src="https://avatars1.githubusercontent.com/u/3134479?v=4?s=100" width="100px;" alt="Pezhvak"/><br /><sub><b>Pezhvak</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=Pezhvak" title="Code">💻</a></td>
    </tr>
    <tr>
      <td align="center"><a href="https://1up.io"><img src="https://avatars3.githubusercontent.com/u/754921?v=4?s=100" width="100px;" alt="David Greminger"/><br /><sub><b>David Greminger</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=bytehead" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/kumamidori"><img src="https://avatars0.githubusercontent.com/u/384567?v=4?s=100" width="100px;" alt="Nana YAMANE"/><br /><sub><b>Nana YAMANE</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=kumamidori" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/David-Kurniawan"><img src="https://avatars1.githubusercontent.com/u/7419157?v=4?s=100" width="100px;" alt="David Kurniawan"/><br /><sub><b>David Kurniawan</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=David-Kurniawan" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/gtapps"><img src="https://avatars0.githubusercontent.com/u/16778396?v=4?s=100" width="100px;" alt="gtapps"/><br /><sub><b>gtapps</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=gtapps" title="Code">💻</a></td>
      <td align="center"><a href="https://peterli.website/"><img src="https://avatars2.githubusercontent.com/u/9021747?v=4?s=100" width="100px;" alt="Chun-Sheng, Li"/><br /><sub><b>Chun-Sheng, Li</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=peter279k" title="Tests">⚠️</a></td>
      <td align="center"><a href="https://github.com/schelmo"><img src="https://avatars1.githubusercontent.com/u/47602?v=4?s=100" width="100px;" alt="schelmo"/><br /><sub><b>schelmo</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=schelmo" title="Code">💻</a></td>
      <td align="center"><a href="https://axentmedia.com/"><img src="https://avatars.githubusercontent.com/u/5845453?v=4?s=100" width="100px;" alt="Mojtaba"/><br /><sub><b>Mojtaba</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=mnajafzadeh" title="Code">💻</a></td>
    </tr>
    <tr>
      <td align="center"><a href="https://t.me/goosebumpsos"><img src="https://avatars.githubusercontent.com/u/32247640?v=4?s=100" width="100px;" alt="Georgy Gusev"/><br /><sub><b>Georgy Gusev</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=GooseBumpsOS" title="Code">💻</a></td>
      <td align="center"><a href="https://jibran.github.io/"><img src="https://avatars.githubusercontent.com/u/2111106?v=4?s=100" width="100px;" alt="Jibran Ijaz"/><br /><sub><b>Jibran Ijaz</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=jibran" title="Code">💻</a></td>
      <td align="center"><a href="https://www.nispeon.tk"><img src="https://avatars.githubusercontent.com/u/37938250?v=4?s=100" width="100px;" alt="Julien Cousin-Alliot"/><br /><sub><b>Julien Cousin-Alliot</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=Nispeon" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/n-richaud"><img src="https://avatars.githubusercontent.com/u/10332682?v=4?s=100" width="100px;" alt="n-richaud"/><br /><sub><b>n-richaud</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/issues?q=author%3An-richaud" title="Bug reports">🐛</a></td>
      <td align="center"><a href="https://github.com/Afonin"><img src="https://avatars.githubusercontent.com/u/3139269?v=4?s=100" width="100px;" alt="Dmitry Afonin"/><br /><sub><b>Dmitry Afonin</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=Afonin" title="Code">💻</a></td>
      <td align="center"><a href="http://about.me/andreifiroiu"><img src="https://avatars.githubusercontent.com/u/4728003?v=4?s=100" width="100px;" alt="Andrei Firoiu"/><br /><sub><b>Andrei Firoiu</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/issues?q=author%3Aandreifiroiu" title="Bug reports">🐛</a></td>
      <td align="center"><a href="http://nsmle.com"><img src="https://avatars.githubusercontent.com/u/55952660?v=4?s=100" width="100px;" alt="Fiki Pratama"/><br /><sub><b>Fiki Pratama</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=nsmle" title="Code">💻</a></td>
    </tr>
    <tr>
      <td align="center"><a href="https://github.com/jigexiansen"><img src="https://avatars.githubusercontent.com/u/48269019?v=4?s=100" width="100px;" alt="Carl"/><br /><sub><b>Carl</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/issues?q=author%3Ajigexiansen" title="Bug reports">🐛</a></td>
      <td align="center"><a href="https://github.com/peachesontour"><img src="https://avatars.githubusercontent.com/u/67612358?v=4?s=100" width="100px;" alt="Peter Kampjes"/><br /><sub><b>Peter Kampjes</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=peachesontour" title="Code">💻</a></td>
      <td align="center"><a href="https://turboweb.co.nz/"><img src="https://avatars.githubusercontent.com/u/2483482?v=4?s=100" width="100px;" alt="Bob Brown"/><br /><sub><b>Bob Brown</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=gurubobnz" title="Documentation">📖</a></td>
      <td align="center"><a href="https://github.com/nagajoomlapro"><img src="https://avatars.githubusercontent.com/u/108052176?v=4?s=100" width="100px;" alt="nagajoomlapro"/><br /><sub><b>nagajoomlapro</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=nagajoomlapro" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/cleveng"><img src="https://avatars.githubusercontent.com/u/32574915?v=4?s=100" width="100px;" alt="cleveng"/><br /><sub><b>cleveng</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=cleveng" title="Code">💻</a></td>
      <td align="center"><a href="https://github.com/BMRG14"><img src="https://avatars.githubusercontent.com/u/2771148?v=4?s=100" width="100px;" alt="BMRG14"/><br /><sub><b>BMRG14</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=BMRG14" title="Code">💻</a></td>
      <td align="center"><a href="https://simpliers.com"><img src="https://avatars.githubusercontent.com/u/44243313?v=4?s=100" width="100px;" alt="Sinan Portakal"/><br /><sub><b>Sinan Portakal</b></sub></a><br /><a href="https://github.com/pgrimaud/instagram-user-feed/commits?author=sportakal" title="Code">💻</a></td>
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!

# Feedback

You found a bug? You need a new feature? You can [create an issue](https://github.com/pgrimaud/instagram-user-feed/issues) if needed or contact me on [Twitter](https://twitter.com/pgrimaud_).

# License

Licensed under the terms of the MIT License.
