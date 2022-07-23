<?php

namespace Instagram\Hydrator;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\Media;
use Instagram\Model\MediaDetailed;
use Instagram\Utils\InstagramHelper;

class MediaDetailedHydrator
{
    /**
     * @param MediaDetailed $media
     * @param \StdClass $node
     *
     * @return MediaDetailed
     * @throws InstagramFetchException
     */
    public function mediaDetailedHydrator(MediaDetailed $media, \StdClass $node): MediaDetailed
    {
        return $this->isCarousel($node)
            ? $this->mediaDetailedCarouselHydrator($media, $node)
            : $this->mediaDetailedDataHydrator($media, $node);
    }

    /**
     * @param Media $media
     * @param \StdClass $node
     *
     * @return Media|MediaDetailed
     * @throws InstagramFetchException
     */
    public function mediaBaseHydrator(Media $media, \StdClass $node): Media
    {
        $media->setId((int) $node->pk);
        $media->setShortCode($node->code);

        $media->setTypeName($this->getTypeName($node->media_type));

        if ($node->caption) {
            $media->setCaption($node->caption->text);
            $media->setHashtags(InstagramHelper::buildHashtags($node->caption->text));

            $date = new \DateTime();
            $date->setTimestamp($node->caption->created_at);

            $media->setDate($date);
        } else {
            if ($node->taken_at) {
                $date = new \DateTime();
                $date->setTimestamp($node->taken_at);
                $media->setDate($date);
            }
        }

        $thumbnailSrc = $displaySrc = '';
        if (property_exists($node, 'image_versions2')) {
            foreach ($node->image_versions2->candidates as $img) {
                if ($img->width == 640)
                    $thumbnailSrc = $img->url;

                if ($img->width == $node->original_width)
                    $displaySrc = $img->url;
            }
        }

        $media->setThumbnailSrc($thumbnailSrc);
        $media->setDisplaySrc($displaySrc);

        if (property_exists($node, 'comment_count')) {
            $media->setComments($node->comment_count);
        }

        if (property_exists($node, 'like_count')) {
            $media->setLikes($node->like_count);
        }

        $media->setLink(InstagramHelper::URL_BASE . "p/{$node->code}/");

        if (isset($node->location)) {
            $media->setLocation($node->location);
        }

        $media->setVideo($this->isVideo($node));

        if (property_exists($node, 'video_versions')) {
            foreach ($node->video_versions as $video) {
                if ($video->type == 101) {
                    $videoSrc = $video->url;
                }
            }
            $media->setVideoUrl($videoSrc ?? null);
        }

        if (property_exists($node, 'view_count')) {
            $media->setVideoViewCount((int) $node->view_count);
        }

        if (property_exists($node, 'product_type')) {
            $media->setIgtv($node->product_type === 'igtv');
        }

        $media->setOwnerId((int) $node->user->pk);

        return $media;
    }


    /**
     * @param MediaDetailed $media
     * @param \StdClass     $node
     *
     * @return MediaDetailed
     */
    private function mediaDetailedDataHydrator(MediaDetailed $media, \StdClass $node): Media
    {
        $media->setDisplayResources($node->image_versions2->candidates);

        $media->setHeight($node->original_height);
        $media->setWidth($node->original_width);

        if ($this->isVideo($node)) {
            $media->setHasAudio($node->has_audio);
        }

        if (property_exists($node, 'usertags')) {
            $taggedUsers = [];
            foreach ($node->usertags->in as $user) {
                $taggedUsers[] = $user->user;
            }

            $media->setTaggedUsers($taggedUsers);
        }

        return $media;
    }

    /**
     * @param MediaDetailed $media
     * @param \StdClass $node
     *
     * @return MediaDetailed
     * @throws InstagramFetchException
     */
    private function mediaDetailedCarouselHydrator(MediaDetailed $media, \StdClass $node): Media
    {
        $scItems = [];
        foreach ($node->carousel_media as $key => $item) {
            $scItem = new MediaDetailed();
            $scItem->setId((int) $item->pk);
            $scItem->setShortCode($node->code);
            $scItem->setHeight($item->original_height);
            $scItem->setWidth($item->original_width);
            $scItem->setTypeName($this->getTypeName($item->media_type));
            $scItem->setDisplayResources($item->image_versions2->candidates);

            $scItem->setVideo($this->isVideo($node));

            if (property_exists($item, 'video_versions')) {
                foreach ($item->video_versions as $video) {
                    if ($video->type == 101) {
                        $videoSrc = $video->url;
                    }
                }
                $scItem->setVideoUrl($videoSrc ?? null);
            }

            $scItems[] = $scItem;

            if ($key == 0)
                $media->setDisplayResources($item->image_versions2->candidates);
        }

        $media->setSideCarItems($scItems);

        return $media;
    }

    /**
     * @param int $media_type
     * @return string
     * @throws InstagramFetchException
     */
    private function getTypeName(int $media_type): string
    {
        switch ($media_type) {
            case Media::MEDIA_TYPE_IMAGE:
                $type = Media::TYPE_IMAGE;
                break;
            case Media::MEDIA_TYPE_VIDEO:
                $type = Media::TYPE_VIDEO;
                break;
            case Media::MEDIA_TYPE_CAROUSEL:
                $type = Media::TYPE_CAROUSEL;
                break;
            default:
                throw new InstagramFetchException('Media type ' . $media_type . ' not found');
        }

        return $type;
    }

    /**
     * @param \StdClass $node
     * @return bool
     */
    private function isCarousel(\StdClass $node): bool
    {
        return $node->media_type === Media::MEDIA_TYPE_CAROUSEL;
    }

    /**
     * @param \StdClass $node
     * @return bool
     */
    private function isVideo(\StdClass $node): bool
    {
        return $node->media_type === Media::MEDIA_TYPE_VIDEO;
    }
}
