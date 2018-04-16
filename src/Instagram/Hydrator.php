<?php

namespace Instagram;

use Instagram\Hydrator\Feed;
use Instagram\Hydrator\Media;

class Hydrator
{
    /**
     * @var array
     */
    private $userData;

    /**
     * @var array
     */
    private $mediaData;

    /**
     * @param array $userData
     */
    public function setUserData($userData)
    {
        $this->userData = $userData;
    }

    /**
     * @param array $mediaData
     */
    public function setMediaData($mediaData)
    {
        $this->mediaData = $mediaData;
    }

    /**
     * @return Feed
     */
    public function getHydratedData()
    {
        $feed = $this->generateFeed();

        if (isset($this->mediaData['data'][0])) {
            foreach ($this->mediaData['data'] as $node) {
                $media = new Media();

                $media->setId($node['id']);
                $media->setTypeName($node['type']);

                $media->setCaption($node['caption']['text']);

                $media->setHeight($node['images']['standard_resolution']['height']);
                $media->setWidth($node['images']['standard_resolution']['width']);

                $media->setThumbnailSrc($node['images']['thumbnail']['url']);
                $media->setDisplaySrc($node['images']['standard_resolution']['url']);

                $media->setLink($node['link']);

                $date = new \DateTime();
                $date->setTimestamp($node['created_time']);

                $media->setDate($date);

                $media->setComments($node['comments']['count']);
                $media->setLikes($node['likes']['count']);

                $feed->addMedia($media);
            }

            $feed->setHasNextPage(isset($this->mediaData['pagination']['next_max_id']));
            $feed->setMaxId(isset($this->mediaData['pagination']['next_max_id']) ? $this->mediaData['pagination']['next_max_id'] : null);
        }

        return $feed;
    }

    /**
     * @return Feed
     */
    private function generateFeed()
    {
        $feed = new Feed();

        if ($this->userData) {
            $feed->setId($this->userData['id']);
            $feed->setUserName($this->userData['username']);
            $feed->setBiography($this->userData['bio']);
            $feed->setFullName($this->userData['full_name']);
            $feed->setProfilePicture($this->userData['profile_picture']);
            $feed->setMediaCount($this->userData['counts']['media']);
            $feed->setFollowers($this->userData['counts']['followed_by']);
            $feed->setFollowing($this->userData['counts']['follows']);
            $feed->setExternalUrl($this->userData['website']);
        }

        return $feed;
    }
}
