<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\{ImageHydrator, CarouselHydrator, ReelsHydrator, UserInfoHydrator};
use Instagram\Model\{Timeline, Image, Carousel, Video, Reels, Igtv, User};
use Instagram\Exception\InstagramFetchException;

class TimelineHydrator
{
    /**
     * @param \StdClass $feed
     *
     * @return Timeline
     */
    public function timelineBaseHydrator(\StdClass $node): Timeline
    {
        $timeline = new Timeline();

        $type = $this->getTypeName($node->media_type, $node->product_type);
        $timeline->setType($type);

        $timelineContent = $this->hydrateTimelineMedia($type, $node);
        $timeline->setContent($timelineContent);

        if (property_exists($node, 'user')) {
            $user = new UserInfoHydrator();
            $user = $user->userBaseHydrator($node->user);

            $timeline->setUser($user);
        }

        return $timeline;
    }

    /**
     * @param string $mediaType
     * 
     * @return mixed Image|Carousel|Video|Reels|Igtv
     */
    public function hydrateTimelineMedia(string $mediaType, \StdClass $node)
    {
        $timeline;

        if ($mediaType == Timeline::TYPE_IMAGE) {
            $image = new ImageHydrator();
            $image = $image->imageBaseHydrator($node);
            $timeline = $image;
        }

        if ($mediaType == Timeline::TYPE_CAROUSEL) {
            $carousel = new CarouselHydrator();
            $carousel = $carousel->carouselBaseHydrator($node);
            $timeline = $carousel;
        }

        if ($mediaType == Timeline::TYPE_VIDEO) {
            $video = new VideoHydrator();
            $video = $video->videoBaseHydrator($node);
            $timeline = $video;
        }

        if ($mediaType == Timeline::TYPE_REELS) {
            $reels = new ReelsHydrator();
            $reels = $reels->reelsBaseHydrator($node);
            $timeline = $reels;
        }

        if ($mediaType == Timeline::TYPE_IGTV) {
            $igtv = new IgtvHydrator();
            $igtv = $igtv->igtvBaseHydrator($node);
            $timeline = $igtv;
        }

        return $timeline;
    }

    /**
     * @param int    $media_type
     * @param string $product_type
     * 
     * @return string
     * @throws InstagramFetchException
     */
    private function getTypeName(int $media_type, string $product_type): string
    {
        if ($media_type == Timeline::MEDIA_TYPE_IMAGE && $product_type == 'feed') {
            $type = Timeline::TYPE_IMAGE;
        } elseif ($media_type == Timeline::MEDIA_TYPE_VIDEO && $product_type == 'feed') {
            $type = Timeline::TYPE_VIDEO;
        } elseif ($media_type == Timeline::MEDIA_TYPE_VIDEO && $product_type == 'clips') {
            $type = Timeline::TYPE_REELS;
        } elseif ($media_type == Timeline::MEDIA_TYPE_VIDEO && $product_type == 'igtv') {
            $type = Timeline::TYPE_IGTV;
        } elseif ($media_type == Timeline::MEDIA_TYPE_CAROUSEL && $product_type == 'carousel_container') {
            $type = Timeline::TYPE_CAROUSEL;
        } else {
            throw new InstagramFetchException('Media type ' . $media_type . '(' . $product_type . ') not found');
        }

        return $type;
    }
}
