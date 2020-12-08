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
        $variables = [
            'tag_name' => $hashtag,
            'first'    => InstagramHelper::PAGINATION_DEFAULT
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_HASHTAG . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->hashtag;
    }

    /**
     * @param string $hashtag
     * @param string $endCursor
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchMoreData(string $hashtag, string $endCursor): \StdClass
    {
        $variables = [
            'tag_name' => $hashtag,
            'first'    => InstagramHelper::PAGINATION_DEFAULT,
            'after'    => $endCursor
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_HASHTAG . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->hashtag;
    }
}
