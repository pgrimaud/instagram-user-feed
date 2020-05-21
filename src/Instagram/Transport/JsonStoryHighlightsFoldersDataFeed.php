<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\InstagramHelper;

class JsonStoryHighlightsFoldersDataFeed extends AbstractDataFeed
{
    /**
     * @param int $int
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(int $int): \StdClass
    {
        $variables = [
            'user_id'                   => (string)$int,
            'include_chaining'          => true,
            'include_reel'              => true,
            'include_suggested_users'   => false,
            'include_logged_out_extras' => false,
            'include_highlight_reels'   => true,
            'include_related_profiles'  => false,
            'include_live_status'       => true,
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_HIGHLIGHTS_FOLDERS . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);

        return $data->data->user->edge_highlight_reels;
    }
}
