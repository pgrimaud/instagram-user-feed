<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\Endpoints;

class CommentPost extends AbstractDataFeed
{
    /**
     * @param int    $postId
     * @param string $message
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function comment(int $postId, string $message): string
    {
        $endpoint = Endpoints::getCommentUrl($postId);
        return $this->fetchData($endpoint, $message);
    }

    /**
     * @param string $endpoint
     * @param string $message
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    private function fetchData(string $endpoint, string $message): string
    {
        $data = $this->postJsonDataFeed($endpoint, [
            'comment_text'          => $message,
            'replied_to_comment_id' => '',
        ]);

        if (!$data->status) {
            throw new InstagramFetchException('Whoops, looks like something went wrong!');
        }

        return $data->status;
    }
}