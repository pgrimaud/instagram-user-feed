<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\{Model\Profile, Utils\InstagramHelper};

class JsonMediasDataFeed extends AbstractDataFeed
{
    /**
     * @param Profile $instagramProfile
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(Profile $instagramProfile): \StdClass
    {
        $variables = [
            'id'    => $instagramProfile->getId(),
            'first' => InstagramHelper::PAGINATION_DEFAULT,
            'after' => $instagramProfile->getEndCursor(),
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_MEDIAS . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->user;
    }
}
