<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\MediaComments;
use stdClass;

class MediaCommentsHydrator extends AbstractStoryHydrator
{
    /**
     * @var MediaComments
     */
    private $comments;

    /**
     * @var CommentHydrator
     */
    private $commentHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->comments        = new MediaComments();
        $this->commentHydrator = new CommentHydrator();
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateMediaComments(\StdClass $data): void
    {
        $this->comments->setMediaCount($data->count);

        if (property_exists($data, 'edges')) {
            foreach ($data->edges as $item) {
                $comment = $this->commentHydrator->hydrateComment($item->node);
                $this->comments->addComment($comment);
            }
        }

        if (property_exists($data, 'page_info')) {
            $this->comments->setHasMoreComments($data->page_info->end_cursor != null);
            $this->comments->setEndCursor($data->page_info->end_cursor);
        }
    }

    /**
     * @return MediaComments
     */
    public function getMediaComments(): MediaComments
    {
        return $this->comments;
    }
}
