<?php

declare(strict_types=1);

namespace Instagram\Model;

use Instagram\Utils\InstagramHelper;

class Timeline
{
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_REELS = 'reels';
    const TYPE_IGTV = 'igtv';
    const TYPE_CAROUSEL = 'carousel';

    const MEDIA_TYPE_IMAGE = 1;
    const MEDIA_TYPE_VIDEO = 2;
    const MEDIA_TYPE_CAROUSEL = 8;

    /**
     * @var string
     */
    private $type;

    /**
     * @var Image|Carousel|Video|Reels|Igtv
     */
    private $content;

    /**
     * @var User
     */
    private $user;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Image|Carousel|Video|Reels|Igtv
     */
    public function getContent(): Image|Carousel|Video|Reels|Igtv
    {
        return $this->content;
    }

    /**
     * @param Image|Carousel|Video|Reels|Igtv $content
     */
    public function setContent(Image|Carousel|Video|Reels|Igtv $content): void
    {
        $this->content = $content;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'user' => $this->user,
            'content' => $this->content
        ];
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }
}
