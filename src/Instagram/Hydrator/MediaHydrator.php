<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\{Media, MediaDetailed};
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
     */
    public function hydrateMediaDetailed(\StdClass $node): MediaDetailed
    {
        $media = new MediaDetailed();
        $media = $this->mediaBaseHydrator($media, $node);

        return $this->mediaDetailedHydrator($media, $node);
    }

    /**
     * @param Media     $media
     * @param \StdClass $node
     *
     * @return Media|MediaDetailed
     */
    private function mediaBaseHydrator(Media $media, \StdClass $node): Media
    {
        $media->setId((int)$node->id);
        $media->setTypeName($node->__typename);

        if ($node->edge_media_to_caption->edges) {
            $media->setCaption($node->edge_media_to_caption->edges[0]->node->text);
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

        $media->setVideo((bool)$node->is_video);

        if (property_exists($node, 'video_view_count')) {
            $media->setVideoViewCount((int)$node->video_view_count);
        }

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
        $media->setDisplayResources($node->display_resources);

        if (property_exists($node, 'video_url')) {
            $media->setVideoUrl($node->video_url);
            $media->setHasAudio($node->has_audio);
        }

        $taggedUsers = [];
        foreach ($node->edge_media_to_tagged_user->edges as $user) {
            $taggedUsers[] = $user->node->user;
        }

        $media->setTaggedUsers($taggedUsers);

        if ($node->__typename === 'GraphSidecar') {
            $scItems = [];
            foreach ($node->edge_sidecar_to_children->edges as $item) {
                $scItem = new MediaDetailed();
                $scItem->setId((int)$item->node->id);
                $scItem->setHeight($item->node->dimensions->height);
                $scItem->setWidth($item->node->dimensions->height);
                $scItem->setTypeName($item->node->__typename);
                $scItem->setDisplayResources($item->node->display_resources);

                $scItem->setVideo((bool)$item->node->is_video);

                if (property_exists($item->node, 'video_view_count')) {
                    $scItem->setVideoViewCount((int)$item->node->video_view_count);
                }
                if (property_exists($item->node, 'video_url')) {
                    $scItem->setVideoUrl($item->node->video_url);
                    $scItem->setHasAudio($item->node->has_audio);
                }

                $scItems[] = $scItem;
            }

            $media->setSideCarItems($scItems);

        }

        return $media;
    }
}
