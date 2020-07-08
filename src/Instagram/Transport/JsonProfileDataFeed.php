<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\InstagramHelper;

class JsonProfileDataFeed extends AbstractDataFeed
{
    /**
     * @param int $id
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function fetchData(int $id): string
    {
        $variables = [
            'user_id'                   => $id,
            'include_chaining'          => false,
            'include_reel'              => true,
            'include_suggested_users'   => false,
            'include_logged_out_extras' => false,
            'include_highlight_reels'   => false,
            'include_related_profiles'  => false,
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_PROFILE . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        if (!$data->data->user) {
            throw new InstagramFetchException('Instagram id ' . $id . ' does not exist.');
        }

        return $data->data->user->reel->user->username;
    }
}
