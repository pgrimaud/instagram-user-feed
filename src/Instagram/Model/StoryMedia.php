<?php

declare(strict_types=1);

namespace Instagram\Model;

class StoryMedia
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $typeName;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @var string
     */
    private $displayUrl;

    /**
     * @var array
     */
    private $displayResources;

    /**
     * @var \DateTime
     */
    private $takenAtDate;

    /**
     * @var \DateTime
     */
    private $expiringAtDate;

    /**
     * @var float
     */
    private $videoDuration = 0;

    /**
     * @var array
     */
    private $videoResources = [];

    /**
     * @var bool
     */
    private $audio = false;

    /**
     * @var string
     */
    private $ctaUrl;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getDisplayUrl(): string
    {
        return $this->displayUrl;
    }

    /**
     * @param string $displayUrl
     */
    public function setDisplayUrl(string $displayUrl): void
    {
        $this->displayUrl = $displayUrl;
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
     * @return \DateTime
     */
    public function getTakenAtDate(): \DateTime
    {
        return $this->takenAtDate;
    }

    /**
     * @param \DateTime $takenAtDate
     */
    public function setTakenAtDate(\DateTime $takenAtDate): void
    {
        $this->takenAtDate = $takenAtDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpiringAtDate(): \DateTime
    {
        return $this->expiringAtDate;
    }

    /**
     * @param \DateTime $expiringAtDate
     */
    public function setExpiringAtDate(\DateTime $expiringAtDate): void
    {
        $this->expiringAtDate = $expiringAtDate;
    }

    /**
     * @return float
     */
    public function getVideoDuration(): float
    {
        return $this->videoDuration;
    }

    /**
     * @param float $videoDuration
     */
    public function setVideoDuration(float $videoDuration): void
    {
        $this->videoDuration = $videoDuration;
    }

    /**
     * @return array
     */
    public function getVideoResources(): array
    {
        return $this->videoResources;
    }

    /**
     * @param array $videoResources
     */
    public function setVideoResources(array $videoResources): void
    {
        $this->videoResources = $videoResources;
    }

    /**
     * @return bool
     */
    public function isAudio(): bool
    {
        return $this->audio;
    }

    /**
     * @param bool $audio
     */
    public function setAudio(bool $audio): void
    {
        $this->audio = $audio;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @param string $typeName
     */
    public function setTypeName(string $typeName): void
    {
        $this->typeName = $typeName;
    }

    /**
     * @return string
     */
    public function getCtaUrl(): string
    {
        return $this->ctaUrl;
    }

    /**
     * @param string $ctaUrl
     */
    public function setCtaUrl(?string $ctaUrl): void
    {
        $this->ctaUrl = $ctaUrl;
    }
}
