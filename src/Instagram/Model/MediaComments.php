<?php

declare(strict_types=1);

namespace Instagram\Model;

class MediaComments
{
    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var array
     */
    private $comments = [];

    /**
     * @var bool
     */
    private $hasMoreComments = false;

    /**
     * @var string
     */
    private $endCursor = null;

    /**
     * @var int
     */
    private $mediaCount = 0;

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    public function setHasMoreComments(bool $hasMoreComments): void
    {
        $this->hasMoreComments = $hasMoreComments;
    }

    /**
     * @return bool
     */
    public function hasMoreComments(): bool
    {
        return $this->hasMoreComments;
    }

    /**
     * @param string|null $endCursor
     */
    public function setEndCursor(?string $endCursor): void
    {
        $this->endCursor = $endCursor;
    }

    /**
     * @return string|null
     */
    public function getEndCursor(): ?string
    {
        return $this->endCursor;
    }

    /**
     * @param int $mediaCount
     */
    public function setMediaCount(int $mediaCount): void
    {
        $this->mediaCount = $mediaCount;
    }

    /**
     * @return int
     */
    public function getMediaCount(): int
    {
        return $this->mediaCount;
    }
}
