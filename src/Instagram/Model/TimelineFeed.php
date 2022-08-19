<?php

declare(strict_types=1);

namespace Instagram\Model;

class TimelineFeed
{
    /**
     * @var array
     */
    private $timeline = [];

    /**
     * @var null|string
     */
    private $maxId = null;

    /**
     * @var boolean
     */
    private $hasMoreTimeline = false;

    /**
     * @var boolean
     */
    private $newFeedPostExist = false;

    /**
     * @var \stdClass
     */
    private $additionalInfo;

    /**
     * @return array
     */
    public function getTimeline(): array
    {
        return $this->timeline;
    }

    /**
     * @param Timeline $timeline
     */
    public function addTimeline(Timeline $timeline): void
    {
        $this->timeline[] = $timeline;
    }

    /**
     * @return null|string
     */
    public function getMaxId(): ?string
    {
        return $this->maxId;
    }

    /**
     * @param null|string $maxId
     */
    public function setMaxId(?string $maxId): void
    {
        $this->maxId = $maxId;
    }

    /**
     * @return bool
     */
    public function hasMoreTimeline(): bool
    {
        return $this->hasMoreTimeline;
    }

    /**
     * @param bool $hasMoreTimeline
     */
    public function setHasMoreTimeline(bool $hasMoreTimeline): void
    {
        $this->hasMoreTimeline = $hasMoreTimeline;
    }

    /**
     * @return bool
     */
    public function getNewFeedPostExist(): bool
    {
        return $this->newFeedPostExist;
    }

    /**
     * @param bool $newFeedPostExist
     */
    public function setNewFeedPostExist(bool $newFeedPostExist): void
    {
        $this->newFeedPostExist = $newFeedPostExist;
    }

    /**
     * @return \stdClass
     */
    public function getAdditionalInfo(): stdClass
    {
        return $this->additionalInfo;
    }

    /**
     * @param \stdClass $additionalInfo
     */
    public function setAdditionalInfo(\stdClass $additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }
}
