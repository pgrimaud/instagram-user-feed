<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Comment;

class CommentHydrator
{
    /**
     * @param \StdClass $node
     *
     * @return Comment
     */
    public function hydrateComment(\StdClass $node): Comment
    {
        $comment = new Comment();

        $comment->setId((int)$node->id);
        $comment->setCaption($node->text);
        $comment->setOwner($node->owner);

        $date = new \DateTime();
        $date->setTimestamp($node->created_at);
        $comment->setDate($date);

        return $comment;
    }
}
