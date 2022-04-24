<?php

declare(strict_types=1);

namespace Instagram\Model;

class Carousel
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $shortCode;

    /**
     * @var string
     */
    private $link;

    /**
     * @var int
     */
    private $likes;

    /**
     * @var boolean
     */
    private $isLiked = false;

    /**
     * @var int
     */
    private $comments;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @var array
     */
    private $carousel = [];

    /**
     * @var string
     */
    private $caption;

    /**
     * @var mixed
     */
    private $location;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var array
     */
    private $hashtags = [];

    /**
     * @var array
     */
    private $userTags = [];

    /**
     * @var User
     */
    private $user;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    /**
     * @param string $shortCode
     */
    public function setShortCode(string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
    }

    /**
     * @return bool
     */
    public function isLiked(): bool
    {
        return $this->isLiked;
    }

    /**
     * @param bool $isLiked
     */
    public function setIsLiked(bool $isLiked): void
    {
        $this->isLiked = $isLiked;
    }

    /**
     * @return int
     */
    public function getComments(): int
    {
        return $this->comments;
    }

    /**
     * @param int $comments
     */
    public function setComments(int $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return array
     */
    public function getCarousel(): array
    {
        return $this->carousel;
    }

    /**
     * @param array $carousel
     */
    public function setCarousel(array $carousel): void
    {
        $this->carousel = $carousel;
    }

    /**
     * @param string $caption
     */
    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \StdClass $location
     */
    public function setLocation(\StdClass $location): void
    {
        $this->location = $location;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function getHashtags(): array
    {
        return $this->hashtags;
    }

    /**
     * @param array $hashtags
     */
    public function setHashtags(array $hashtags): void
    {
        $this->hashtags = $hashtags;
    }

    /**
     * @return array
     */
    public function getUserTags(): array
    {
        return $this->userTags;
    }

    /**
     * @param array $userTags
     */
    public function setUserTags(array $userTags): void
    {
        $this->userTags = $userTags;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
