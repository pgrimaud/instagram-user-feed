<?php

declare(strict_types=1);

namespace Instagram\Model;

use Instagram\Utils\InstagramHelper;

class Timeline
{
    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';
    public const TYPE_REELS = 'reels';
    public const TYPE_IGTV = 'igtv';
    public const TYPE_CAROUSEL = 'carousel';

    public const MEDIA_TYPE_IMAGE = 1;
    public const MEDIA_TYPE_VIDEO = 2;
    public const MEDIA_TYPE_CAROUSEL = 8;

    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed Image|Carousel|Video|Reels|Igtv
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
     * @return mixed Image|Carousel|Video|Reels|Igtv
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed Image|Carousel|Video|Reels|Igtv $content
     */
    public function setContent($content): void
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
