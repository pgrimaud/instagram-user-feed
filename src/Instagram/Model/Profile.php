<?php

declare(strict_types=1);

namespace Instagram\Model;

class Profile
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $biography;

    /**
     * @var int
     */
    private $followers;

    /**
     * @var int
     */
    private $following;

    /**
     * @var string
     */
    private $profilePicture;

    /**
     * @var string
     */
    private $externalUrl;

    /**
     * @var bool
     */
    private $private;

    /**
     * @var bool
     */
    private $verified;

    /**
     * @var int
     */
    private $mediaCount = 0;

    /**
     * @var Media[]
     */
    private $medias = [];

    /**
     * @var bool
     */
    private $hasMoreMedias = false;

    /**
     * @var string
     */
    private $endCursor = null;

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getBiography(): string
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     */
    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    /**
     * @return int
     */
    public function getFollowers(): int

    {
        return $this->followers;
    }

    /**
     * @param int $followers
     */
    public function setFollowers(int $followers): void
    {
        $this->followers = $followers;
    }

    /**
     * @return int
     */
    public function getFollowing(): int
    {
        return $this->following;
    }

    /**
     * @param int $following
     */
    public function setFollowing(int $following): void
    {
        $this->following = $following;
    }

    /**
     * @return string
     */
    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     */
    public function setProfilePicture(string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * @return string
     */
    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    /**
     * @param string $externalUrl
     */
    public function setExternalUrl(?string $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }

    /**
     * @param bool $private
     */
    public function setPrivate(bool $private): void
    {
        $this->private = $private;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     */
    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getMediaCount(): int
    {
        return $this->mediaCount;
    }

    /**
     * @param int $mediaCount
     */
    public function setMediaCount(int $mediaCount): void
    {
        $this->mediaCount = $mediaCount;
    }

    /**
     * @return Media[]
     */
    public function getMedias(): array
    {
        return $this->medias;
    }

    /**
     * @param Media $media
     */
    public function addMedia(Media $media): void
    {
        $this->medias[] = $media;
    }

    public function setHasMoreMedias(bool $hasMoreMedias): void
    {
        $this->hasMoreMedias = $hasMoreMedias;
    }

    /**
     * @return bool
     */
    public function hasMoreMedias(): bool
    {
        return $this->hasMoreMedias;
    }

    /**
     * @param string|null $endCursor
     */
    public function setEndCursor(?string $endCursor): void
    {
        $this->endCursor = $endCursor;
    }

    /**
     * @return string|null
     */
    public function getEndCursor(): ?string
    {
        return $this->endCursor;
    }

    /**
     * @param Media[] $medias
     */
    public function setMedias(array $medias): void
    {
        $this->medias = $medias;
    }
}
