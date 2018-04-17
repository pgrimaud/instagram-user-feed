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

## Warning

**2018-04-17 : Now fetching data with screen scraping (thanks [@cookieguru](https://github.com/cookieguru)), please upgrade to version ^5.0**

~~2018-04-16 : Now fetching data with access token, only for your account (thanks [@jannejava](https://github.com/jannejava)), please upgrade to version ^4.0~~

~~2018-04-08 : Due to changes of the Instagram API (again...), you must upgrade to version ^3.0~~

~~2018-03-16 : Due to changes of the Instagram API, you must upgrade to version ^2.1~~

## Usage

### Retrieve data

```php
$api = new Api();

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

### Setting a custom User Agent
Since this method is using screen scraping, it is recommended that you spoof a user agent:

```php
$client = new GuzzleHttp\Client(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0']);

$api = new Api($client);

$feed = $api->getFeed('pgrimaud');

// And etc...
```

