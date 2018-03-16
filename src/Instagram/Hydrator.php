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

        if (isset($this->mediaData['edge_owner_to_timeline_media'])) {
            foreach ($this->mediaData['edge_owner_to_timeline_media']['edges'] as $node) {
                $node = $node['node'];

                $media = new Media();

                $media->setId($node['id']);
                $media->setTypeName($node['__typename']);

                $caption = isset($node['edge_media_to_caption']['edges'][0]['node']['text']) ? $node['edge_media_to_caption']['edges'][0]['node']['text'] : null;
                $media->setCaption($caption);

                $media->setHeight($node['dimensions']['height']);
                $media->setWidth($node['dimensions']['width']);

                $media->setThumbnailSrc($node['thumbnail_src']);
                $media->setDisplaySrc($node['display_url']);

                $resources = [];

                foreach ($node['thumbnail_resources'] as $resource) {
                    $resources[] = [
                        'src'    => $resource['src'],
                        'width'  => $resource['config_width'],
                        'height' => $resource['config_height'],
                    ];
                }

                $media->setThumbnailResources($resources);

                $media->setCode($node['shortcode']);
                $media->setLink('https://www.instagram.com/p/' . $node['shortcode'] . '/');

                $date = new \DateTime();
                $date->setTimestamp($node['taken_at_timestamp']);

                $media->setDate($date);

                $media->setComments($node['edge_media_to_comment']['count']);
                $media->setLikes($node['edge_media_preview_like']['count']);

                $feed->addMedia($media);
            }

            $feed->setHasNextPage($this->mediaData['edge_owner_to_timeline_media']['page_info']['has_next_page']);
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
            $feed->setFullName($this->userData['full_name']);
            $feed->setBiography($this->userData['biography']);

            $feed->setIsVerified($this->userData['is_verified']);
            $feed->setFollowers($this->userData['edge_followed_by']['count']);
            $feed->setFollowing($this->userData['edge_follow']['count']);

            $feed->setProfilePicture($this->userData['profile_pic_url']);
            $feed->setProfilePictureHd($this->userData['profile_pic_url_hd']);
            $feed->setExternalUrl($this->userData['external_url']);

            $feed->setMediaCount($this->userData['edge_owner_to_timeline_media']['count']);
        }

        return $feed;
    }
}
