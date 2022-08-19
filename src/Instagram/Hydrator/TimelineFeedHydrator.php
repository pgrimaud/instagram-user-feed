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
        if (property_exists($feed, 'next_max_id')) {
            $this->timelineFeed->setMaxId($feed->next_max_id);
        }

        foreach ($feed->feed_items as $feedItem) {
            if (property_exists($feedItem, 'media_or_ad')) {
                // Timeline Feed
                if (!property_exists($feedItem->media_or_ad, 'label')) {
                    $timeline = $this->timelineHydrator->timelineBaseHydrator($feedItem->media_or_ad);
                    $this->timelineFeed->addTimeline($timeline);
                }
                
                // Sponsored Feed
                if (!property_exists($feedItem, 'label')) {
                }
            }
            
            // Suggested User Feed
            if (property_exists($feedItem, 'suggested_users')) {
            }
        }

        // get additionals info
        $additionalInfo = [];
        $additionalInfo['pullToRefresh'] = $feed->pull_to_refresh_window_ms;
        $additionalInfo['preloadDistance'] = $feed->preload_distance;
        $additionalInfo['lastHeadLoad'] = \DateTime::createFromFormat('U', (string) $feed->last_head_load_ms);
        $additionalInfo['hideLikeAndViewCounts'] = boolval($feed->hide_like_and_view_counts);
        $additionalInfo['clientFeedChangelistApplied'] = $feed->client_feed_changelist_applied;
        $this->timelineFeed->setAdditionalInfo((object) $additionalInfo);
    }

    /**
     * @return TimelineFeed
     */
    public function getTimelineFeed(): TimelineFeed
    {
        return $this->timelineFeed;
    }
}
