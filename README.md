# Instagram user feed PHP

[![Build Status](https://travis-ci.org/pgrimaud/instagram-user-feed.svg?branch=master)](https://travis-ci.org/pgrimaud/instagram-user-feed)
[![Packagist](https://img.shields.io/badge/packagist-install-brightgreen.svg)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Coverage Status](https://coveralls.io/repos/github/pgrimaud/instagram-user-feed/badge.svg)](https://coveralls.io/github/pgrimaud/instagram-user-feed)

[![Total Downloads](https://poser.pugx.org/pgrimaud/instagram-user-feed/downloads)](https://packagist.org/packages/pgrimaud/instagram-user-feed)
[![Monthly Downloads](https://poser.pugx.org/pgrimaud/instagram-user-feed/d/monthly)](https://packagist.org/packages/pgrimaud/instagram-user-feed)

## Version ^4.0 is now shutdown ([see](https://support.pixelunion.net/hc/en-us/articles/360041460554-Important-notice-Instagram-feed-removal)). Please upgrade to ^5.0.

## Information
This library offers 2 packages to retrieve your or any Instagram feed without OAuth for PHP.

## Version ^5.0
This version can retrieve **ANY** Instagram feed using **web scrapping**.

- [Installation](#installation-of-version-50)
- [Usage](#usage-of-version-50)
- [Paginate](#paginate-for-version-50)

## Changelog

**2020-02-23 : Version ^4.0 is now shutdown ([see](https://support.pixelunion.net/hc/en-us/articles/360041460554-Important-notice-Instagram-feed-removal)). Please upgrade to ^5.0.** 

~~2018-04-20 : Release of version ^5.0 in parallel of version ^4.0 which still working. (Kudos for [@jannejava](https://github.com/jannejava) and [@cookieguru](https://github.com/cookieguru)**)~~

~~2018-04-17 : Now fetching data with screen scraping (thanks [@cookieguru](https://github.com/cookieguru)), please upgrade to version ^5.0~~

~~2018-04-16 : Now fetching data with access token, only for your account (thanks [@jannejava](https://github.com/jannejava)), please upgrade to version ^4.0~~

~~2018-04-08 : Due to changes of the Instagram API (again...), you must upgrade to version ^3.0~~

~~2018-03-16 : Due to changes of the Instagram API, you must upgrade to version ^2.1~~

# Installation of version ^5.0

```
composer require pgrimaud/instagram-user-feed
```

## Usage of version ^5.0

**New in 5.3** : CacheManager is no more mandatory is you want to retrieve only Instagram profile. (no pagination)

```php
$api = new Instagram\Api();
$api->login('username', 'password'); // optional, may be required on shared hosting
$api->setUserName('robertdowneyjr');

$feed = $api->getFeed();

echo $feed->getUserName();
// robertdowneyjr

echo $feed->getFullName();
// Robert Downey Jr. Official

```

### Basic usage : 


```php
$cache = new Instagram\Storage\CacheManager('/path/to/your/cache/folder');
$api   = new Instagram\Api($cache);
$api->setUserName('robertdowneyjr');

$feed = $api->getFeed();

print_r($feed);

```

```php
Instagram\Hydrator\Component\Feed Object
(
    [id] => 1518284433
    [userName] => robertdowneyjr
    [fullName] => Robert Downey Jr. Official
    [biography] => @officialfootprintcoalition @coreresponse
    [followers] => 46338039
    [following] => 50
    [profilePicture] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-19/s320x320/72702032_542075739927421_3928117925747097600_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_ohc=hGF8upBhWgcAX_7ks82&oh=a9cdb3ed313d5c4c9712b52b7d3ceb3f&oe=5EE2E5B8
    [externalUrl] => http://coreresponse.org/covid19
    [private] => 
    [verified] => 1
    [mediaCount] => 452
    [medias] => Array
        (
            [0] => Instagram\Hydrator\Component\Media Object
                (
                    [id] => 2306047234549362565
                    [typeName] => GraphImage
                    [height] => 1080
                    [width] => 1080
                    [thumbnailSrc] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/sh0.08/e35/s640x640/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=bbd50d5437b7ca52f8e9ba241c358508&oe=5EE38623
                    [link] => https://www.instagram.com/p/CAAub3qli-F/
                    [date] => DateTime Object
                        (
                            [date] => 2020-05-10 16:51:14.000000
                            [timezone_type] => 3
                            [timezone] => Europe/Paris
                        )

                    [displaySrc] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/e35/s1080x1080/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=5afbf701b8374062f15b309247367fc4&oe=5EE51716
                    [caption] => This Mother’s Day, more than ever, let’s honor the women that raise the children that inherit the Earth #happymothersday
                    [comments] => 7096
                    [likes] => 3134975
                    [thumbnails] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [src] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/e35/s150x150/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=d64a87ae9a06f1ae2ac08db593204042&oe=5EE33A86
                                    [config_width] => 150
                                    [config_height] => 150
                                )

                            [1] => stdClass Object
                                (
                                    [src] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/e35/s240x240/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=76c8f9337cecbe15615ac46a20973fed&oe=5EE41850
                                    [config_width] => 240
                                    [config_height] => 240
                                )

                            [2] => stdClass Object
                                (
                                    [src] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/e35/s320x320/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=050d57f21816dfc2f9eb50942eb237af&oe=5EE583F6
                                    [config_width] => 320
                                    [config_height] => 320
                                )

                            [3] => stdClass Object
                                (
                                    [src] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/e35/s480x480/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=dd6e527f2eba91b1d7fc6e0798d49a85&oe=5EE3FE30
                                    [config_width] => 480
                                    [config_height] => 480
                                )

                            [4] => stdClass Object
                                (
                                    [src] => https://scontent-lhr8-1.cdninstagram.com/v/t51.2885-15/sh0.08/e35/s640x640/96326668_170992687583571_3686185583583090082_n.jpg?_nc_ht=scontent-lhr8-1.cdninstagram.com&_nc_cat=1&_nc_ohc=2qpfvDrHHtwAX9B8mkO&oh=bbd50d5437b7ca52f8e9ba241c358508&oe=5EE38623
                                    [config_width] => 640
                                    [config_height] => 640
                                )

                        )

                    [location] => 
                    [video] => 
                    [videoViewCount] => 0
                )
        
        ...
        
    [endCursor] => QVFEMFd3cklmZ3NkZmNjZlA4aTc3LVVOZHpMN1AzZnNBTUF3U3Fjd01KcWVUc25qak40b0Z2UlUzRWVCTzktYU5yOTBLdkduZWR4SC1QTUFQcm93eUtxXw==
)

```

## Paginate for version ^5.0
If you want to use paginate, retrieve `endCursor` from previous call and add it to your next call.

```php
// Initialization

$cache = new Instagram\Storage\CacheManager('/path/to/your/cache/folder');
$api   = new Instagram\Api($cache);
$api->setUserName('robertdowneyjr');

// First call :

$feed = $api->getFeed();

// Second call : 

$endCursor = $feed->getEndCursor();
$api->setEndCursor($endCursor);
$feed = $api->getFeed();

// And etc...
```
