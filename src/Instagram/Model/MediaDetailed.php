<?php

namespace Instagram\Model;

class MediaDetailed extends Media
{
    /**
     * @var string
     */
    private $videoUrl;

    /**
     * @var bool
     */
    private $hasAudio = false;

    /**
     * @var array
     */
    private $taggedUsers = [];

    /**
     * @var array
     */
    private $sideCarItems = [];

    /**
     * @var array
     */
    private $displayResources = [];

    /**
     * @return string
     */
    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    /**
     * @param string $videoUrl
     */
    public function setVideoUrl(string $videoUrl): void
    {
        $this->videoUrl = $videoUrl;
    }

    /**
     * @return array
     */
    public function getTaggedUsers(): array
    {
        return $this->taggedUsers;
    }

    /**
     * @param array $taggedUsers
     */
    public function setTaggedUsers(array $taggedUsers): void
    {
        $this->taggedUsers = $taggedUsers;
    }

    /**
     * @return MediaDetailed[]
     */
    public function getSideCarItems(): array
    {
        return $this->sideCarItems;
    }

    /**
     * @param array $sideCarItems
     */
    public function setSideCarItems(array $sideCarItems): void
    {
        $this->sideCarItems = $sideCarItems;
    }

    /**
     * @return array
     */
    public function getDisplayResources(): array
    {
        return $this->displayResources;
    }

    /**
     * @param array $displayResources
     */
    public function setDisplayResources(array $displayResources): void
    {
        $this->displayResources = $displayResources;
    }

    /**
     * @return bool
     */
    public function hasAudio(): bool
    {
        return $this->hasAudio;
    }

    /**
     * @param bool $hasAudio
     */
    public function setHasAudio(bool $hasAudio): void
    {
        $this->hasAudio = $hasAudio;
    }
}
