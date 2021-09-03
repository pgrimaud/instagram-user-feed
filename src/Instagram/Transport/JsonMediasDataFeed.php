<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\{Model\Profile, Utils\InstagramHelper};

class JsonMediasDataFeed extends AbstractDataFeed
{
    /**
     * @param Profile $instagramProfile
     * @param int     $limit
     * @param string  $queryHash
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(Profile $instagramProfile, int $limit, string $queryHash = InstagramHelper::QUERY_HASH_MEDIAS): \StdClass
    {
        // check if this method was called for medias or igtvs
        $after = $queryHash === InstagramHelper::QUERY_HASH_MEDIAS ? $instagramProfile->getEndCursor() : $instagramProfile->getEndCursorIgtvs();

        $variables = [
            'id'    => PHP_INT_SIZE === 4 ? $instagramProfile->getId32Bit() : $instagramProfile->getId(),
            'first' => $limit,
            'after' => $after,
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . $queryHash . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->user;
    }
}
