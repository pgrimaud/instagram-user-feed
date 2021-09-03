<?php

declare(strict_types=1);

namespace Instagram\Model;

class Reels
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $shortCode;

    /**
     * @var int
     */
    private $likes;

    /**
     * @var float
     */
    private $videoDuration;

    /**
     * @var int
     */
    private $viewCount;

    /**
     * @var int
     */
    private $playCount;

    /**
     * @var array
     */
    private $imageVersions = [];

    /**
     * @var array
     */
    private $videoVersions = [];

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $caption;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    /**
     * @param string $shortCode
     */
    public function setShortCode(string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
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
     * @return int
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    /**
     * @param int $viewCount
     */
    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    /**
     * @return int
     */
    public function getPlayCount(): int
    {
        return $this->playCount;
    }

    /**
     * @param int $playCount
     */
    public function setPlayCount(int $playCount): void
    {
        $this->playCount = $playCount;
    }

    /**
     * @return array
     */
    public function getImageVersions(): array
    {
        return $this->imageVersions;
    }

    /**
     * @param array $imageVersions
     */
    public function setImageVersions(array $imageVersions): void
    {
        $this->imageVersions = $imageVersions;
    }

    /**
     * @return array
     */
    public function getVideoVersions(): array
    {
        return $this->videoVersions;
    }

    /**
     * @param array $videoVersions
     */
    public function setVideoVersions(array $videoVersions): void
    {
        $this->videoVersions = $videoVersions;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @param string $caption
     */
    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }
}
