<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\ProfileStory;

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
        if (count($data->reels_media)) {
            $medias = current($data->reels_media);

            $this->stories->setOwner($medias->owner);
            $this->stories->setAllowedToReply($medias->can_reply);
            $this->stories->setReshareable($medias->can_reshare);

            $expiringDate = new \DateTime();
            $expiringDate->setTimestamp($medias->expiring_at);
            $this->stories->setExpiringDate($expiringDate);

            foreach ($medias->items as $item) {
                $story = $this->hydrateStory($item);
                $this->stories->addStory($story);
            }
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
