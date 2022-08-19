<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\Endpoints;

class StoryInteraction extends AbstractDataFeed
{
    /**
     * @param int $storyId
     * @param int $ownerId
     * @param int $takenAt
     * @param int $seenAt
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function seen(int $storyId, int $ownerId, int $takenAt, int $seenAt): string
    {
        $formParams = [
            'reelMediaId'      => $storyId,
            'reelMediaOwnerId' => $ownerId,
            'reelId'           => $ownerId,
            'reelMediaTakenAt' => $takenAt,
            'viewSeenAt'       => $seenAt
        ];

        $data = $this->postJsonDataFeed(Endpoints::SEEN_STORY_URL, $formParams);

        if (!$data->status) {
            throw new InstagramFetchException('Whoops, looks like something went wrong!');
        }

        return $data->status;
    }
}
