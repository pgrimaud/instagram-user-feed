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
    public function follow(int $accountId): string
    {
        $endpoint = Endpoints::getFollowUrl($accountId);
        return $this->fetchData($endpoint);
    }

    /**
     * @param int $accountId
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function unfollow(int $accountId): string
    {
        $endpoint = Endpoints::getUnfollowUrl($accountId);
        return $this->fetchData($endpoint);
    }

    /**
     * @param string $endpoint
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    private function fetchData(string $endpoint): string
    {
        $data = $this->postJsonDataFeed($endpoint);

        if (!$data->status) {
            throw new InstagramFetchException('Whoops, looks like something went wrong!');
        }

        return $data->status;
    }
}
