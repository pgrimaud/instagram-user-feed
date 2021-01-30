<?php

declare(strict_types=1);

namespace Instagram\Model;

class Location
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $hasPublicPage = false;

    /**
     * @var float
     */
    private $latitude = 0;

    /**
     * @var float
     */
    private $longitude = 0;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $facebookAlias;

    /**
     * @var array
     */
    private $address;

    /**
     * @var string
     */
    private $profilePicture;

    /**
     * @var int
     */
    private $totalMedia = 0;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function hasPublicPage(): bool
    {
        return $this->hasPublicPage;
    }

    /**
     * @param bool $hasPublicPage
     */
    public function setHasPublicPage(bool $hasPublicPage): void
    {
        $this->hasPublicPage = $hasPublicPage;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getFacebookAlias(): string
    {
        return $this->facebookAlias;
    }

    /**
     * @param string $facebookAlias
     */
    public function setFacebookAlias(string $facebookAlias): void
    {
        $this->facebookAlias = $facebookAlias;
    }

    /**
     * @return array
     */
    public function getAddress(): array
    {
        return $this->address;
    }

    /**
     * @param array $address
     */
    public function setAddress(array $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getProfilePicture(): ?string
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
     * @return int
     */
    public function getTotalMedia(): int
    {
        return $this->totalMedia;
    }

    /**
     * @param int $totalMedia
     */
    public function setTotalMedia(int $totalMedia): void
    {
        $this->totalMedia = $totalMedia;
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
