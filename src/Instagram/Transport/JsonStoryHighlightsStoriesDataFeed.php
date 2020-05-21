<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\StoryHighlightsFolder;
use Instagram\Utils\InstagramHelper;

class JsonStoryHighlightsStoriesDataFeed extends AbstractDataFeed
{
    /**
     * @param StoryHighlightsFolder $folder
     *
     * @return \StdClass
     *
     * @throws InstagramFetchException
     */
    public function fetchData(StoryHighlightsFolder $folder): \StdClass
    {
        $variables = [
            'reel_ids'                    => [],
            'tag_names'                   => [],
            'location_ids'                => [],
            'highlight_reel_ids'          => [(string)$folder->getId()],
            'precomposed_overlay'         => false,
            'show_story_viewer_list'      => true,
            'story_viewer_fetch_count'    => 50,
            'story_viewer_cursor'         => '',
            'stories_video_dash_manifest' => false,
        ];

        $endpoint = InstagramHelper::URL_BASE . 'graphql/query/?query_hash=' . InstagramHelper::QUERY_HASH_HIGHLIGHTS_STORIES . '&variables=' . json_encode($variables);

        $data = $this->fetchJsonDataFeed($endpoint);
        
        return $data->data->reels_media[0];
    }
}
