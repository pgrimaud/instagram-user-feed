<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\{Model\Media, Model\Profile};

class JsonMediaDetailedDataFeed extends AbstractDataFeed
{
    /**
     * @param Media $media
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(Media $media): \StdClass
    {
        $data = $this->fetchJsonDataFeed($media->getLink() . '?__a=1&__d=dis');

        return current($data->items);
    }
}
