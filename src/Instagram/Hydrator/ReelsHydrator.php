<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Reels;
use Instagram\Model\ReelsFeed;

class ReelsHydrator
{
    /**
     * @var ReelsFeed
     */
    private $reelsFeed;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->reelsFeed = new ReelsFeed();
    }

    /**
     * @param \StdClass $feed
     *
     * @return void
     */
    public function hydrateReels(\StdClass $feed): void
    {
        // get paginate cursor
        if ($feed->paging_info->more_available) {
            $this->reelsFeed->setMaxId($feed->paging_info->max_id);
        }

        foreach ($feed->items as $item) {
            $reels = new Reels();
            $reels->setId($item->media->id);
            $reels->setShortCode($item->media->code);

            if (property_exists($item->media, 'caption')) {
                $reels->setCaption($item->media->caption->text);
            }

            $reels->setLikes($item->media->like_count);
            $reels->setVideoDuration((float) $item->media->video_duration);
            $reels->setViewCount($item->media->view_count);
            $reels->setPlayCount($item->media->play_count);
            $reels->setDate(\DateTime::createFromFormat('U', (string) $item->media->taken_at));

            $reels->setImageVersions(array_map(function ($item) {
                return (array) $item;
            }, $item->media->image_versions2->candidates));

            $reels->setVideoVersions(array_map(function ($item) {
                return (array) $item;
            }, $item->media->video_versions));

            $this->reelsFeed->addReels($reels);
        }
    }

    /**
     * @return ReelsFeed
     */
    public function getReelsFeed(): ReelsFeed
    {
        return $this->reelsFeed;
    }
}
