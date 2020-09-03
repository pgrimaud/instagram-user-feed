<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\Endpoints;

class FollowUnfollow extends AbstractDataFeed
{
    /**
     * @param int $accountId
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function createFriend(int $accountId): string
    {
        $endpoint = Endpoints::getFollowUrl($accountId);

        $data = $this->postJsonDataFeed($endpoint);

        if (!$data->status) {
            throw new InstagramFetchException('Whoops, looks like something went wrong!');
        }

        return $data->status;
    }

    /**
     * @param int $accountId
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function destroyFriend(int $accountId): string
    {
        $endpoint = Endpoints::getUnfollowUrl($accountId);

        $data = $this->postJsonDataFeed($endpoint);

        if (!$data->status) {
            throw new InstagramFetchException('Whoops, looks like something went wrong!');
        }

        return $data->status;
    }
}
