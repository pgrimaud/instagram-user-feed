<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\UserInfoHydrator;
use Instagram\Model\Reels;
use Instagram\Utils\InstagramHelper;

class ReelsHydrator
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
     * @return Reels
     */
    public function reelsBaseHydrator(\StdClass $node): Reels
    {
        $reels = new Reels();

        $reels->setId($node->pk);
        $reels->setShortCode($node->code);
        $reels->setLink(InstagramHelper::URL_BASE . "reel/{$node->code}/");
        $reels->setDate(\DateTime::createFromFormat('U', (string) $node->taken_at));
        $reels->setLikes($node->like_count);
        $reels->setIsLiked($node->has_liked);
        $reels->setViews($node->view_count);
        $reels->setPlays($node->play_count);
        $reels->setDuration($node->video_duration);
        $reels->setHeight($node->original_height);
        $reels->setWidth($node->original_width);
        $reels->setHasAudio($node->has_audio);

        $reels->setImages(array_map(function ($node) {
            return $node;
        }, $node->image_versions2->candidates));

        $reels->setVideos(array_map(function ($node) {
            return $node;
        }, $node->video_versions));

        if (property_exists($node, 'comment_count')) {
            $reels->setComments($node->comment_count);
        }

        if (property_exists($node, 'caption')) {
            if (!empty($node->caption)) {
                $reels->setCaption($node->caption->text);
                $reels->setHashtags(InstagramHelper::buildHashtags($node->caption->text));
            }
        }

        if (property_exists($node, 'usertags')) {
            $userTags = [];
            foreach ($node->usertags->in as $user) {
                $userTags[] = $this->hydrateUser->userBaseHydrator($user->user);
            }

            $reels->setUserTags($userTags);
        }

        $user = $this->hydrateUser->userBaseHydrator($node->user);
        $reels->setUser($user);

        return $reels;
    }
}
