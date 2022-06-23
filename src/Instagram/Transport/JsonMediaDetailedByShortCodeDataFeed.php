<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Media;
use Instagram\Utils\InstagramHelper;

class JsonMediaDetailedByShortCodeDataFeed extends AbstractDataFeed
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
        $data = $this->fetchJsonDataFeed(InstagramHelper::URL_MEDIA_DETAILED . $media->getShortCode() . '/?__a=1&__d=dis');

        return current($data->items);
    }
}
