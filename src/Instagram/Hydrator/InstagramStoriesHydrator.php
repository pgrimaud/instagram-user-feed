<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\InstagramStories;
use Instagram\Model\InstagramStory;

class InstagramStoriesHydrator
{
    /**
     * @var InstagramStories
     */
    private $stories;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->stories = new InstagramStories();
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
            $story = new InstagramStory();

            $story->setId((int)$item->id);
            $story->setTypeName($item->__typename);

            $story->setHeight($item->dimensions->height);
            $story->setWidth($item->dimensions->width);
            $story->setDisplayUrl($item->display_url);
            $story->setDisplayResources($item->display_resources);
            $story->setCtaUrl($item->story_cta_url);

            $takenAtDate = new \DateTime();
            $takenAtDate->setTimestamp($item->taken_at_timestamp);
            $story->setTakenAtDate($takenAtDate);

            $expiringAtDate = new \DateTime();
            $expiringAtDate->setTimestamp($item->expiring_at_timestamp);
            $story->setExpiringAtDate($expiringAtDate);

            if ($item->__typename === 'GraphStoryVideo') {
                $story->setVideoDuration($item->video_duration);
                $story->setVideoResources($item->video_resources);
                $story->setAudio($item->has_audio);
            }

            $this->stories->addStory($story);
        }
    }

    /**
     * @return InstagramStories
     */
    public function getStories(): InstagramStories
    {
        return $this->stories;
    }
}
