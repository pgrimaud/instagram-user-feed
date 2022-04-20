<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Reels;

class ReelsHydrator
{
    /**
     * @param \StdClass $item
     *
     * @return Reels
     */
    public function hydrateReels(\StdClass $item): Reels
    {
        $reels = new Reels();

        $reels->setId($item->id);
        $reels->setShortCode($item->code);

        if (property_exists($item, 'caption')) {
            $reels->setCaption($item->caption->text);
        }

        $reels->setLikes($item->like_count);
        $reels->setVideoDuration((float) $item->video_duration);
        $reels->setViewCount($item->view_count);
        $reels->setPlayCount($item->play_count);
        $reels->setDate(\DateTime::createFromFormat('U', (string) $item->taken_at));

        $reels->setImageVersions(array_map(function ($item) {
            return (array) $item;
        }, $item->image_versions2->candidates));

        $reels->setVideoVersions(array_map(function ($item) {
            return (array) $item;
        }, $item->video_versions));

        return $reels;
    }
}
