<?php

namespace Instagram\Hydrator;

use Instagram\Model\Media;
use Instagram\Model\MediaDetailed;
use Instagram\Utils\InstagramHelper;

class MediaProfileHydrator
{
    /**
     * @param Media     $media
     * @param \StdClass $node
     *
     * @return Media|MediaDetailed
     */
    public function mediaBaseHydrator(Media $media, \StdClass $node): Media
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
}