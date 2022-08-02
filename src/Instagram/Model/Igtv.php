<?php

declare(strict_types=1);

namespace Instagram\Model;

class Igtv
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
    private $views;

    /**
     * @var float
     */
    private $duration;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @var boolean
     */
    private $isPostLive;

    /**
     * @var boolean
     */
    private $hasAudio;

    /**
     * @var string
     */
    private $subtitlesUrl;

    /**
     * @var \StdClass
     */
    private $thumbnails;

    /**
     * @var array
     */
    private $images = [];

    /**
     * @var array
     */
    private $videos = [];

    /**
     * @var string
     */
    private $title;

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
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getPlays(): int
    {
        return $this->plays;
    }

    /**
     * @param int $plays
     */
    public function setPlays(int $plays): void
    {
        $this->plays = $plays;
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
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
     * @return bool
     */
    public function isPostLive(): bool
    {
        return $this->isPostLive;
    }

    /**
     * @param bool $isPostLive
     */
    public function setIsPostLive(bool $isPostLive): void
    {
        $this->isPostLive = $isPostLive;
    }

    /**
     * @return bool
     */
    public function getHasAudio(): bool
    {
        return $this->hasAudio;
    }

    /**
     * @param bool $width
     */
    public function setHasAudio(bool $hasAudio): void
    {
        $this->hasAudio = $hasAudio;
    }

    /**
     * @return string
     */
    public function getSubtitlesUrl(): string
    {
        return $this->subtitlesUrl;
    }

    /**
     * @param string $subtitlesUrl
     */
    public function setSubtitlesUrl(string $subtitlesUrl): void
    {
        $this->subtitlesUrl = $subtitlesUrl;
    }

    /**
     * @return \StdClass
     */
    public function getThumbnails(): \StdClass
    {
        return $this->thumbnails;
    }

    /**
     * @param \StdClass $thumbnails
     */
    public function setThumbnails(\StdClass $thumbnails): void
    {
        $this->thumbnails = $thumbnails;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param array $video
     */
    public function setVideos(array $videos): void
    {
        $this->videos = $videos;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
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
