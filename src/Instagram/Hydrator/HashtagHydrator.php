<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Hashtag;

class HashtagHydrator
{
    /**
     * @var Hashtag
     */
    private $hashtag;

    /**
     * @var MediaHydrator
     */
    private $mediaHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     *
     * @param Hashtag|null $instagramHashtag
     */
    public function __construct(Hashtag $instagramHashtag = null)
    {
        $this->hashtag       = $instagramHashtag ?: new Hashtag();
        $this->mediaHydrator = new MediaHydrator();
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateHashtag(\StdClass $data): void
    {
        $this->hashtag->setId((int)$data->id);
        $this->hashtag->setName($data->name);
        $this->hashtag->setAllowFollowing($data->allow_following);
        $this->hashtag->setFollowing($data->is_following);
        $this->hashtag->setTopMediaOnly($data->is_top_media_only);
        $this->hashtag->setMediaCount($data->edge_hashtag_to_media->count);

        $profilePicture = '';

        if (property_exists($data, 'profile_pic_url')) {
            $profilePicture = $data->profile_pic_url;
        }

        if (property_exists($data, 'profile_pic_url_hd')) {
            $profilePicture = $data->profile_pic_url_hd;
        }

        $this->hashtag->setProfilePicture($profilePicture);
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateMedias(\StdClass $data): void
    {
        // reset medias
        $this->hashtag->setMedias([]);

        foreach ($data->edge_hashtag_to_media->edges as $item) {
            $media = $this->mediaHydrator->hydrateMediaFromProfile($item->node);
            $this->hashtag->addMedia($media);
        }

        $this->hashtag->setHasMoreMedias($data->edge_hashtag_to_media->page_info->end_cursor != null);
        $this->hashtag->setEndCursor($data->edge_hashtag_to_media->page_info->end_cursor);
    }

    /**
     * @return Hashtag
     */
    public function getHashtag(): Hashtag
    {
        return $this->hashtag;
    }
}
