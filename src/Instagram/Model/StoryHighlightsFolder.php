<?php

declare(strict_types=1);

namespace Instagram\Model;

class StoryHighlightsFolder
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cover;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $sharableUrl;

    /**
     * @var array
     */
    private $stories = [];

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * @param StoryMedia $story
     */
    public function addStory(StoryMedia $story): void
    {
        $this->stories[] = $story;
    }

    /**
     * @return StoryMedia[]
     */
    public function getStories(bool $oldestInFirst = true): array
    {
        if ($oldestInFirst) {
            return array_reverse($this->stories);
        } else {
            return $this->stories;
        }
    }

    public function orderStories(): void
    {
        $this->stories = array_reverse($this->stories);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getSharableUrl(): string
    {
        return $this->sharableUrl;
    }

    /**
     * @param string $sharableUrl
     */
    public function setSharableUrl(string $sharableUrl): void
    {
        $this->sharableUrl = $sharableUrl;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
