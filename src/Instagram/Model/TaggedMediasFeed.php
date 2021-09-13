<?php

declare(strict_types=1);

namespace Instagram\Model;

class TaggedMediasFeed
{
    /**
     * @var array
     */
    private $medias = [];

    /**
     * @var boolean
     */
    private $hasNextPage;

    /**
     * @var string
     */
    private $endCursor;

    /**
     * @return array
     */
    public function getMedias(): array
    {
        return $this->medias;
    }

    /**
     * @param Media $media
     */
    public function addMedia(Media $media): void
    {
        $this->medias[] = $media;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @param bool $hasNextPage
     */
    public function setHasNextPage(bool $hasNextPage): void
    {
        $this->hasNextPage = $hasNextPage;
    }

    /**
     * @return string
     */
    public function getEndCursor(): string
    {
        return $this->endCursor;
    }

    /**
     * @param string $endCursor
     */
    public function setEndCursor(string $endCursor): void
    {
        $this->endCursor = $endCursor;
    }
}