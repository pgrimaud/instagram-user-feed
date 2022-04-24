<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\TimelineHydrator;
use Instagram\Model\TimelineFeed;

class TimelineFeedHydrator
{
    /**
     * @var TimelineHydrator
     */
    private $timelineHydrator;

    /**
     * @var TimelineFeed
     */
    private $timelineFeed;

    public function __construct()
    {
        $this->timelineHydrator = new TimelineHydrator();
        $this->timelineFeed     = new TimelineFeed();
    }

    /**
     * @param \StdClass $feed
     *
     * @return void
     */
    public function hydrateTimelineFeed(\StdClass $feed): void
    {
        // get new feed post exist
        if ($feed->new_feed_posts_exist) {
            $this->timelineFeed->setNewFeedPostExist($feed->new_feed_posts_exist);
        }

        // get more timeline available
        if ($feed->more_available) {
            $this->timelineFeed->setHasMoreTimeline($feed->more_available);
        }

        // get paginate cursor
        if ($feed->next_max_id) {
            $this->timelineFeed->setMaxId($feed->next_max_id);
        }

        foreach ($feed->feed_items as $feedItem) {
            $timeline = $this->timelineHydrator->timelineBaseHydrator($feedItem->media_or_ad);
            $this->timelineFeed->addTimeline($timeline);
        }
    }

    /**
     * @return TimelineFeed
     */
    public function getTimelineFeed(): TimelineFeed
    {
        return $this->timelineFeed;
    }
}
