<?php

namespace Instagram\Hydrator\Component;

class Feed
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $fullName;

    /**
     * @var string
     */
    public $biography;

    /**
     * @var integer
     */
    public $followers;

    /**
     * @var integer
     */
    public $following;

    /**
     * @var string
     */
    public $profilePicture;

    /**
     * @var string
     */
    public $externalUrl;

    /**
     * @var integer
     */
    public $mediaCount = 0;

    /**
     * @var Media[]
     */
    public $medias = [];

    /**
     * @var string
     */
    public $endCursor = null;

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * @return string
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param string $followers
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
    }

    /**
     * @return string
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param string $following
     */
    public function setFollowing($following)
    {
        $this->following = $following;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * @param string $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;
    }

    /**
     * @return string
     */
    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    /**
     * @param string $externalUrl
     */
    public function setExternalUrl($externalUrl)
    {
        $this->externalUrl = $externalUrl;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getMediaCount()
    {
        return $this->mediaCount;
    }

    /**
     * @param int $mediaCount
     */
    public function setMediaCount($mediaCount)
    {
        $this->mediaCount = $mediaCount;
    }

    /**
     * @return Media[]
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param Media $media
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;
    }

    /**
     * @return string
     */
    public function getEndCursor()
    {
        return $this->endCursor;
    }

    /**
     * @param string $endCursor
     */
    public function setEndCursor($endCursor)
    {
        $this->endCursor = $endCursor;
    }
}
