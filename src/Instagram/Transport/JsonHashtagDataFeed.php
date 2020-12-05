<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\InstagramHelper;

class JsonHashtagDataFeed extends AbstractDataFeed
{
    /**
     * @param string $hashtag
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(string $hashtag): \StdClass
    {
        $endpoint = InstagramHelper::URL_BASE . 'explore/tags/' . $hashtag . '/?__a=1';

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->graphql->hashtag;
    }

    /**
     * @param string $hashtag
     * @param string $maxId
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchMoreData(string $hashtag, int $maxId): \StdClass
    {
        $endpoint = InstagramHelper::URL_BASE . 'explore/tags/' . $hashtag . '/?__a=1&max_id=' . $maxId;

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->graphql->hashtag;
    }
}
