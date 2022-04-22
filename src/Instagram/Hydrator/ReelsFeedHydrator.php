<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\ReelsHydrator;
use Instagram\Model\ReelsFeed;

class ReelsFeedHydrator
{
    /**
     * @var ReelsHydrator
     */
    private $reelsHydrator;

    /**
     * @var ReelsFeed
     */
    private $reelsFeed;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->reelsHydrator = new ReelsHydrator();
        $this->reelsFeed     = new ReelsFeed();
    }

    /**
     * @param \StdClass $feed
     *
     * @return void
     */
    public function hydrateReelsFeed(\StdClass $feed): void
    {
        // get paginate cursor
        if ($feed->paging_info->more_available) {
            $this->reelsFeed->setMaxId($feed->paging_info->max_id);
        }

        foreach ($feed->items as $item) {
            $reels = $this->reelsHydrator->reelsBaseHydrator($item->media);
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
