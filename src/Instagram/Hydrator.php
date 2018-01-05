<?php
namespace Instagram;

use Instagram\Hydrator\Feed;
use Instagram\Hydrator\Media;

class Hydrator
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Feed
     */
    public function getHydratedData()
    {
        $feed = $this->generateFeed();

        foreach ($this->data['media']['nodes'] as $node) {
            $media = new Media();

            $media->setId($node['id']);
            $media->setTypeName($node['__typename']);
            $media->setCaption($node['caption']);

            $media->setHeight($node['dimensions']['height']);
            $media->setWidth($node['dimensions']['width']);

            $media->setThumbnailSrc($node['thumbnail_src']);
            $media->setDisplaySrc($node['display_src']);

            $resources = [];

            foreach ($node['thumbnail_resources'] as $resource) {
                $resources[] = [
                    'src'    => $resource['src'],
                    'width'  => $resource['config_width'],
                    'height' => $resource['config_height'],
                ];
            }

            $media->setThumbnailResources($resources);

            $media->setCode($node['code']);
            $media->setLink('https://www.instagram.com/p/' . $node['code'] . '/');

            $date = new \DateTime();
            $date->setTimestamp($node['date']);

            $media->setDate($date);

            $media->setComments($node['comments']['count']);
            $media->setLikes($node['likes']['count']);

            $feed->addMedia($media);
        }

        return $feed;
    }

    /**
     * @return Feed
     */
    private function generateFeed()
    {
        $feed = new Feed();

        $feed->setId($this->data['id']);
        $feed->setUserName($this->data['username']);
        $feed->setFullName($this->data['full_name']);
        $feed->setBiography($this->data['biography']);

        $feed->setIsVerified($this->data['is_verified']);
        $feed->setFollowers($this->data['followed_by']['count']);
        $feed->setFollowing($this->data['follows']['count']);

        $feed->setProfilePicture($this->data['profile_pic_url']);
        $feed->setProfilePictureHd($this->data['profile_pic_url_hd']);
        $feed->setExternalUrl($this->data['external_url']);

        $feed->setHasNextPage($this->data['media']['page_info']['has_next_page']);
        $feed->setMediaCount($this->data['media']['count']);

        return $feed;
    }
}
