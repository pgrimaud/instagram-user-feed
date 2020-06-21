<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\{Model\Media, Model\Profile};

class JsonMediaDetailedByShortCodeDataFeed extends AbstractDataFeed
{
    const PREFIX = 'https://www.instagram.com/p/';

    /**
     * @param Media $media
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(Media $media): \StdClass
    {
        $data = $this->fetchJsonDataFeed(self::PREFIX.$media->getShortCode() . '/?__a=1');

        return $data->graphql;
    }
}
