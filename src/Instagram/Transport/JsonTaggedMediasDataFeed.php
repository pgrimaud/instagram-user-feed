<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\InstagramHelper;

class JsonTaggedMediasDataFeed extends AbstractDataFeed
{
    /**
     * @param int    $id
     * @param string $endCursor
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(int $id, string $endCursor = ''): \StdClass
    {
        $variables = [
            'id'    => $id,
            'first' => 12,
            'after' => $endCursor,
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_TAGGED_MEDIAS . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->user;
    }
}
