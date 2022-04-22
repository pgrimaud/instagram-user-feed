<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\UserInfoHydrator;
use Instagram\Model\Igtv;
use Instagram\Utils\InstagramHelper;

class IgtvHydrator
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
     * @return Igtv
     */
    public function igtvBaseHydrator(\StdClass $node): Igtv
    {
        $igtv = new Igtv();

        $igtv->setId($node->pk);
        $igtv->setShortCode($node->code);
        $igtv->setLink(InstagramHelper::URL_BASE . "tv/{$node->code}/");
        $igtv->setDate(\DateTime::createFromFormat('U', (string) $node->taken_at));
        $igtv->setLikes($node->like_count);
        $igtv->setIsLiked($node->has_liked);
        $igtv->setComments($node->comment_count);
        $igtv->setViews($node->view_count);
        $igtv->setDuration($node->video_duration);
        $igtv->setHeight($node->original_height);
        $igtv->setWidth($node->original_width);
        $igtv->setIsPostLive($node->is_post_live);
        $igtv->setHasAudio($node->has_audio);

        $igtv->setImages(array_map(function ($node) {
            return $node;
        }, $node->image_versions2->candidates));

        $igtv->setVideos(array_map(function ($node) {
            return $node;
        }, $node->video_versions));

        if (property_exists($node, 'thumbnails')) {
            $igtv->setThumbnails($node->thumbnails);
        }

        if (property_exists($node, 'title')) {
            $igtv->setTitle($node->title);
        }

        if (property_exists($node, 'video_subtitles_uri')) {
            $igtv->setSubtitlesUrl($node->video_subtitles_uri);
        }

        if (property_exists($node, 'caption')) {
            $igtv->setCaption($node->caption->text);
            $igtv->setHashtags(InstagramHelper::buildHashtags($node->caption->text));
        }

        if (property_exists($node, 'location')) {
            $igtv->setLocation($node->location);
        }

        if (property_exists($node, 'usertags')) {
            $userTags = [];
            foreach ($node->usertags->in as $user) {
                $userTags[] = $this->hydrateUser->userBaseHydrator($user->user);
            }

            $igtv->setUserTags($userTags);
        }

        $user = $this->hydrateUser->userBaseHydrator($node->user);
        $igtv->setUser($user);

        return $igtv;
    }
}
