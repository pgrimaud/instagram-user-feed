<?php

declare(strict_types=1);

namespace Instagram\Model;

class StoryHighlights
{
    /**
     * @var array
     */
    private $folders = [];

    /**
     * @return StoryHighlightsFolder[]
     */
    public function getFolders(): array
    {
        return $this->folders;
    }

    /**
     * @param StoryHighlightsFolder $folder
     */
    public function addFolder(StoryHighlightsFolder $folder): void
    {
        $this->folders[] = $folder;
    }


}
