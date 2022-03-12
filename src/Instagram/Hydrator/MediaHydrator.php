<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Exception\InstagramFetchException;
use Instagram\Model\{Media, MediaDetailed, TaggedMediasFeed};
use Instagram\Utils\InstagramHelper;

class MediaHydrator
{
    /**
     * @param \StdClass $node
     *
     * @return Media
     */
    public function hydrateMediaFromProfile(\StdClass $node): Media
    {
        $media = new Media();
        return $this->mediaBaseHydrator($media, $node);
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
        $media = $this->mediaBaseHydratorV2($media, $node);

        return $this->isCarusel($node)
            ? $this->mediaDetailedCarouselHydrator($media, $node)
            : $this->mediaDetailedHydrator($media, $node);
    }

    /**
     * @param Media     $media
     * @param \StdClass $node
     *
     * @return Media|MediaDetailed
     */
    private function mediaBaseHydrator(Media $media, \StdClass $node): Media
    {
        $media->setId((int) $node->id);
        $media->setShortCode($node->shortcode);
        if (property_exists($node, '__typename')) {
            $media->setTypeName($node->__typename);
        }

        if ($node->edge_media_to_caption->edges) {
            $media->setCaption($node->edge_media_to_caption->edges[0]->node->text);
            $media->setHashtags(InstagramHelper::buildHashtags($node->edge_media_to_caption->edges[0]->node->text));
        }

        $media->setHeight($node->dimensions->height);
        $media->setWidth($node->dimensions->width);

        $thumbnailSrc = property_exists($node, 'thumbnail_src') ? $node->thumbnail_src : $node->display_url;

        $media->setThumbnailSrc($thumbnailSrc);
        $media->setDisplaySrc($node->display_url);

        $date = new \DateTime();
        $date->setTimestamp($node->taken_at_timestamp);

        $media->setDate($date);

        if (property_exists($node, 'edge_media_to_comment')) {
            $commentsCount = $node->edge_media_to_comment->count;
        } else {
            $commentsCount = $node->edge_media_to_parent_comment->count;
        }

        $media->setComments($commentsCount);
        $media->setLikes($node->edge_media_preview_like->count);

        $media->setLink(InstagramHelper::URL_BASE . "p/{$node->shortcode}/");

        $thumbNails = [];
        if (property_exists($node, 'thumbnail_resources')) {
            $thumbNails = $node->thumbnail_resources;
        }

        $media->setThumbnails($thumbNails);

        if (isset($node->location)) {
            $media->setLocation($node->location);
        }

        $media->setVideo((bool) $node->is_video);

        if (property_exists($node, 'video_url')) {
            $media->setVideoUrl($node->video_url);
        }

        if (property_exists($node, 'video_view_count')) {
            $media->setVideoViewCount((int) $node->video_view_count);
        }

        if (property_exists($node, 'accessibility_caption')) {
            $media->setAccessibilityCaption($node->accessibility_caption);
        }

        if (property_exists($node, 'product_type')) {
            $media->setIgtv($node->product_type === 'igtv');
        }

        if (property_exists($node, 'owner')) {
            $media->setOwnerId((int) $node->owner->id);
        }

        return $media;
    }

    /**
     * @param Media     $media
     * @param \StdClass $node
     *
     * @return Media|MediaDetailed
     */
    private function mediaBaseHydratorV2(Media $media, \StdClass $node): Media
    {
        $media->setId((int) $node->pk);
        $media->setShortCode($node->code);

        $media->setTypeName($this->getTypeName($node->media_type));

        if ($node->caption) {
            $media->setCaption($node->caption->text);
            $media->setHashtags(InstagramHelper::buildHashtags($node->caption->text));
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

        $date = new \DateTime();
        $date->setTimestamp($node->caption->created_at);

        $media->setDate($date);

        $media->setComments($node->comment_count);
        $media->setLikes($node->like_count);

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

        $media->setOwnerId((int) $node->caption->user_id);

        return $media;
    }

    /**
     * @param MediaDetailed $media
     * @param \StdClass     $node
     *
     * @return MediaDetailed
     */
    private function mediaDetailedHydrator(MediaDetailed $media, \StdClass $node): Media
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
            $media = $this->mediaBaseHydrator(new Media, $node->node);
            $feed->addMedia($media);
        }

        return $feed;
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
    private function isCarusel(\StdClass $node): bool
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
