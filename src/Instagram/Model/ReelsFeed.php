<?php

declare(strict_types=1);

namespace Instagram\Model;

class ReelsFeed
{
    /**
     * @var array
     */
    private $reels = [];

    /**
     * @var null|string
     */
    private $maxId = null;

    /**
     * @param Reels $reels
     */
    public function addReels(Reels $reels): void
    {
        $this->reels[] = $reels;
    }

    /**
     * @return array
     */
    public function getReels(): array
    {
        return $this->reels;
    }

    /**
     * @return string
     */
    public function getMaxId(): ?string
    {
        return $this->maxId;
    }

    /**
     * @param string $maxId
     */
    public function setMaxId(string $maxId): void
    {
        $this->maxId = $maxId;
    }

    public function hasMaxId(): bool
    {
        return $this->getMaxId() !== null;
    }
}