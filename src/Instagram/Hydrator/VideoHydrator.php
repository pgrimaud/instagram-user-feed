<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\UserInfoHydrator;
use Instagram\Model\Video;
use Instagram\Utils\InstagramHelper;

class VideoHydrator
{
    /**
     * @var UserInfoHydrator
     */
    private $hydrateUser;

    public function __construct()
    {
        $this->hydrateUser = new UserInfoHydrator();
    }

    /**
     * @param \StdClass $node
     *
     * @return Video
     */
    public function videoBaseHydrator(\StdClass $node): Video
    {
        $video = new Video();

        $video->setId($node->pk);
        $video->setShortCode($node->code);
        $video->setLink(InstagramHelper::URL_BASE . "p/{$node->code}/");
        $video->setDate(\DateTime::createFromFormat('U', (string) $node->taken_at));
        $video->setLikes($node->like_count);
        $video->setIsLiked($node->has_liked);
        $video->setComments($node->comment_count);
        $video->setViews($node->view_count);
        $video->setDuration($node->video_duration);
        $video->setHeight($node->original_height);
        $video->setWidth($node->original_width);

        $video->setHasAudio($node->has_audio);

        $video->setImages(array_map(function ($node) {
            return $node;
        }, $node->image_versions2->candidates));

        $video->setVideos(array_map(function ($node) {
            return $node;
        }, $node->video_versions));

        if (property_exists($node, 'thumbnails')) {
            $video->setThumbnails((object) $node->thumbnails);
        }

        if (property_exists($node, 'caption')) {
            $video->setCaption($node->caption->text);
            $video->setHashtags(InstagramHelper::buildHashtags($node->caption->text));
        }

        if (property_exists($node, 'location')) {
            $video->setLocation($node->location);
        }

        if (property_exists($node, 'usertags')) {
            $userTags = [];
            foreach ($node->usertags->in as $user) {
                $userTags[] = $this->hydrateUser->userBaseHydrator($user->user);
            }

            $video->setUserTags($userTags);
        }

        $user = $this->hydrateUser->userBaseHydrator($node->user);
        $video->setUser($user);

        return $video;
    }
}
