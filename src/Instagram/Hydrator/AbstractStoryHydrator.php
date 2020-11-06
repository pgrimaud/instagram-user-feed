<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\StoryMedia;

abstract class AbstractStoryHydrator
{
    /**
     * @param \StdClass $item
     *
     * @return StoryMedia
     */
    protected function hydrateStory(\StdClass $item): StoryMedia
    {
        $story = new StoryMedia();

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

        $hashtags  = [];
        $mentions  = [];
        $locations = [];

        // tappable objects
        foreach ($item->tappable_objects as $object) {
            switch ($object->__typename) {
                case 'GraphTappableHashtag':
                    $hashtags[] = $object->name;
                    break;
                case 'GraphTappableMention':
                    $mentions[] = $object->username;
                    break;
                case 'GraphTappableLocation':
                    $locations[] = $object->id;
                    break;
            }
        }

        $story->setHashtags($hashtags);
        $story->setMentions($mentions);
        $story->setLocations($locations);

        return $story;
    }
}
