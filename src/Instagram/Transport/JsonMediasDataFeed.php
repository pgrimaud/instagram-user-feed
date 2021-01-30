<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\{Model\Profile, Utils\InstagramHelper};

class JsonMediasDataFeed extends AbstractDataFeed
{
    /**
     * @param Profile $instagramProfile
     * @param int $limit
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(Profile $instagramProfile, int $limit): \StdClass
    {
        $variables = [
            'id'    => PHP_INT_SIZE === 4 ? $instagramProfile->getId32Bit() : $instagramProfile->getId(),
            'first' => $limit,
            'after' => $instagramProfile->getEndCursor()
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_MEDIAS . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->user;
    }
}
