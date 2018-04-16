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

        if (isset($this->userData)) {
            foreach ($this->userData as $node) {
                //$node = $node['images'];

                $media = new Media();

                //$media->setId($node['id']);
                //$media->setTypeName($node['__typename']);

                //$caption = isset($node['edge_media_to_caption']['edges'][0]['node']['text']) ? $node['edge_media_to_caption']['edges'][0]['node']['text'] : null;
                //$media->setCaption($caption);

                $media->setHeight($node['images']['standard_resolution']['height']);
                $media->setWidth($node['images']['standard_resolution']['width']);

                $media->setThumbnailSrc($node['images']['thumbnail']['url']);
                $media->setDisplaySrc($node['images']['standard_resolution']['url']);

                /*
                $resources = [];

                foreach ($node['thumbnail_resources'] as $resource) {
                    $resources[] = [
                        'src'    => $resource['src'],
                        'width'  => $resource['config_width'],
                        'height' => $resource['config_height'],
                    ];
                }


                $media->setThumbnailResources($resources);
                */
                //$media->setCode($node['shortcode']);
                $media->setLink($node['link']);

                $date = new \DateTime();
                $date->setTimestamp($node['created_time']);

                $media->setDate($date);

                //$media->setComments($node['edge_media_to_comment']['count']);
                //$media->setLikes($node['edge_media_preview_like']['count']);

                $feed->addMedia($media);
            }

            $feed->setHasNextPage($this->mediaData['edge_owner_to_timeline_media']['page_info']['has_next_page']);
            $feed->setEndCursor($this->mediaData['edge_owner_to_timeline_media']['page_info']['end_cursor']);
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
            $total = count($this->userData);

            if ($total > 0) {
                $feed->setId($this->userData[0]['id']);
                $feed->setUserName($this->userData[0]['user']['username']);
                $feed->setFullName($this->userData[0]['user']['full_name']);
                $feed->setProfilePicture($this->userData[0]['user']['profile_picture']);
                $feed->setMediaCount($total);
            }
        }

        return $feed;
    }
}
