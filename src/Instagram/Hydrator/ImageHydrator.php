<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Hydrator\UserInfoHydrator;
use Instagram\Model\Image;
use Instagram\Utils\InstagramHelper;

class ImageHydrator
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
     * @return Image
     */
    public function imageBaseHydrator(\StdClass $node): Image
    {
        $image = new Image();

        $image->setId($node->pk);
        $image->setShortCode($node->code);
        $image->setLink(InstagramHelper::URL_BASE . "p/{$node->code}/");
        $image->setDate(\DateTime::createFromFormat('U', (string) $node->taken_at));
        $image->setLikes($node->like_count);
        $image->setIsLiked($node->has_liked);
        
        $image->setHeight($node->original_height);
        $image->setWidth($node->original_width);

        $image->setImage(array_map(function ($node) {
            return $node;
        }, $node->image_versions2->candidates));

        if (property_exists($node, 'comment_count')) {
            $image->setComments($node->comment_count);
        }

        if (property_exists($node, 'caption')) {
            if (!empty($node->caption)) {
                $image->setCaption($node->caption->text);
                $image->setHashtags(InstagramHelper::buildHashtags($node->caption->text));
            }
        }

        if (property_exists($node, 'accessibility_caption')) {
            $image->setAccessibilityCaption($node->accessibility_caption);
        }

        if (property_exists($node, 'location')) {
            $image->setLocation($node->location);
        }

        if (property_exists($node, 'usertags')) {
            $userTags = [];
            foreach ($node->usertags->in as $user) {
                $userTag = $this->hydrateUser->userBaseHydrator($user->user);
                $userTags[] = $userTag;
            }

            $image->setUserTags($userTags);
        }

        $user = $this->hydrateUser->userBaseHydrator($node->user);
        $image->setUser($user);

        return $image;
    }
}
