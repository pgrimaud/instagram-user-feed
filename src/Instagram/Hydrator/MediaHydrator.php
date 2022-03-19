<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\{Media, MediaDetailed, TaggedMediasFeed};

class MediaHydrator
{
    /**
     * @var MediaProfileHydrator
     */
    private $hydrateProfile;
    /**
     * @var MediaDetailedHydrator
     */
    private $hydrateDetailed;
    /**
     * @var ProfileAlternativeHydrator
     */
    private $hydrateAlternateProfile;

    public function __construct()
    {
        $this->hydrateProfile = new MediaProfileHydrator();
        $this->hydrateAlternateProfile = new ProfileAlternativeHydrator();
        $this->hydrateDetailed = new MediaDetailedHydrator();
    }

    /**
     * @param \StdClass $node
     *
     * @return Media
     */
    public function hydrateMediaFromProfile(\StdClass $node): Media
    {
        $media = new Media();
        return $this->hydrateProfile->mediaBaseHydrator($media, $node);
    }

    /**
     * @param \StdClass $node
     *
     * @return MediaDetailed
     * @throws InstagramFetchException
     */
    public function hydrateMediaDetailed(\StdClass $node): MediaDetailed
    {
        $media = new MediaDetailed();
        $media = $this->hydrateDetailed->mediaBaseHydrator($media, $node);

        $this->hydrateAlternateProfile->hydrateProfile($node->user);
        $media->setProfile($this->hydrateAlternateProfile->getProfile());

        return $this->hydrateDetailed->mediaDetailedHydrator($media, $node);
    }

    /**
     * @param \StdClass $node
     *
     * @return TaggedMediasFeed
     */
    public function hydrateTaggedMedias(\StdClass $node): TaggedMediasFeed
    {
        $feed = new TaggedMediasFeed();
        $feed->setHasNextPage($node->edge_user_to_photos_of_you->page_info->has_next_page);
        $feed->setEndCursor($node->edge_user_to_photos_of_you->page_info->end_cursor);

        foreach ($node->edge_user_to_photos_of_you->edges as $node) {
            $media = $this->hydrateProfile->mediaBaseHydrator(new Media, $node->node);
            $feed->addMedia($media);
        }

        return $feed;
    }
}
