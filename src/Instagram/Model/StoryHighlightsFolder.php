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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cover;

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
    public function getStories(): array
    {
        return array_reverse($this->stories);
    }

    public function orderStories(): void
    {
        $this->stories = array_reverse($this->stories);
    }
}
