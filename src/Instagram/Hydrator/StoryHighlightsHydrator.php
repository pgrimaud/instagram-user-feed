<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\StoryHighlights;
use Instagram\Model\StoryHighlightsFolder;

class StoryHighlightsHydrator extends AbstractStoryHydrator
{
    /**
     * @var StoryHighlights
     */
    private $highlights;

    /**
     * @var StoryHighlightsFolder
     */
    private $folder;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->highlights = new StoryHighlights();
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateFolders(\StdClass $data): void
    {
        foreach ($data->edges as $highLight) {
            $folder = new StoryHighlightsFolder();

            $folder->setId((int)$highLight->node->id);
            $folder->setName($highLight->node->title);
            $folder->setCover($highLight->node->cover_media_cropped_thumbnail->url);

            $this->highlights->addFolder($folder);
        }
    }

    /**
     * @param StoryHighlightsFolder $folder
     * @param \StdClass             $data
     */
    public function hydrateHighLights(StoryHighlightsFolder $folder, \StdClass $data): void
    {
        $this->folder = $folder;

        foreach ($data->items as $item) {
            $story = $this->hydrateStory($item);
            $this->folder->addStory($story);
        }

        $this->folder->orderStories();
    }

    /**
     * @return StoryHighlights
     */
    public function getHighlights(): StoryHighlights
    {
        return $this->highlights;
    }

    /**
     * @return StoryHighlightsFolder
     */
    public function getFolder(): StoryHighlightsFolder
    {
        return $this->folder;
    }
}
