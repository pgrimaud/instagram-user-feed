<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Exception\InstagramFetchException;
use Instagram\Hydrator\UserInfoHydrator;
use Instagram\Model\{Media, Carousel, Image};
use Instagram\Utils\InstagramHelper;

class CarouselHydrator
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
     * @return Carousel
     */
    public function carouselBaseHydrator(\StdClass $node): Carousel
    {
        $carousel = new Carousel();

        $carousel->setId($node->pk);
        $carousel->setShortCode($node->code);
        $carousel->setLink(InstagramHelper::URL_BASE . "p/{$node->code}/");
        $carousel->setDate(\DateTime::createFromFormat('U', (string) $node->taken_at));
        $carousel->setLikes($node->like_count);
        $carousel->setIsLiked($node->has_liked);
        $carousel->setComments($node->comment_count);
        $carousel->setHeight($node->carousel_media[0]->original_height);
        $carousel->setWidth($node->carousel_media[0]->original_width);

        $carouselMedia = $this->carouselMediaHydrator($node->carousel_media);
        $carousel->setCarousel($carouselMedia);

        if (property_exists($node, 'caption')) {
            $carousel->setCaption($node->caption->text);
            $carousel->setHashtags(InstagramHelper::buildHashtags($node->caption->text));
        }

        if (property_exists($node, 'location')) {
            $carousel->setLocation($node->location);
        }

        if (property_exists($node, 'usertags')) {
            $userTags = [];
            foreach ($node->usertags->in as $user) {
                $userTags[] = $this->hydrateUser->userBaseHydrator($user->user);
            }

            $carousel->setUserTags($userTags);
        }

        $user = $this->hydrateUser->userBaseHydrator($node->user);
        $carousel->setUser($user);

        return $carousel;
    }

    /**
     * @param array $carouselItems
     *
     * @return array
     */
    public function carouselMediaHydrator(array $carouselItems): array
    {
        $carouselMedias = [];
        foreach ($carouselItems as $carouselItem) {
            $carouselType = $this->getCarouselType($carouselItem->media_type);

            $carouselMedia = [
                'id'                   => $carouselItem->pk,
                'parentId'             => $carouselItem->carousel_parent_id,
                'type'                 => $carouselType,
                'width'                => $carouselItem->original_width,
                'height'               => $carouselItem->original_height,
            ];
    
            if (property_exists($carouselItem, 'image_versions2')) {
                $carouselMedia['image'] = $carouselItem->image_versions2->candidates;
            }

            if (property_exists($carouselItem, 'video_versions')) {
                $carouselMedia['video'] = $carouselItem->video_versions;
            }

            if (property_exists($carouselItem, 'video_duration')) {
                $carouselMedia['duration'] = $carouselItem->video_duration;
            }
    
            if (property_exists($carouselItem, 'accessibility_caption')) {
                $carouselMedia['accessibilityCaption'] = $carouselItem->accessibility_caption;
            }
    
            if (property_exists($carouselItem, 'number_of_qualities')) {
                $carouselMedia['quality'] = $carouselItem->number_of_qualities;
            }

            $carouselMedias[] = (object) $carouselMedia;
        }

        return $carouselMedias;
    }

    /**
     * @param int $media_type
     * 
     * @return string
     * @throws InstagramFetchException
     */
    private function getCarouselType(int $media_type): string
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
}
