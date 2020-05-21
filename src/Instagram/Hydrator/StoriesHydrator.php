<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\ProfileStory;
use Instagram\Model\StoryMedia;

class StoriesHydrator extends AbstractStoryHydrator
{
    /**
     * @var ProfileStory
     */
    private $stories;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->stories = new ProfileStory();
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateStories(\StdClass $data): void
    {
        $this->stories->setOwner($data->owner);
        $this->stories->setAllowedToReply($data->can_reply);
        $this->stories->setReshareable($data->can_reshare);

        $expiringDate = new \DateTime();
        $expiringDate->setTimestamp($data->expiring_at);
        $this->stories->setExpiringDate($expiringDate);

        foreach ($data->items as $item) {
            $story = $this->hydrateStory($item);
            $this->stories->addStory($story);
        }
    }

    /**
     * @return ProfileStory
     */
    public function getStories(): ProfileStory
    {
        return $this->stories;
    }
}
