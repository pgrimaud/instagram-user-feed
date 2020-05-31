<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Profile;

class ProfileHydrator
{
    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var MediaHydrator
     */
    private $mediaHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     *
     * @param Profile|null $instagramProfile
     */
    public function __construct(Profile $instagramProfile = null)
    {
        $this->profile       = $instagramProfile ?: new Profile();
        $this->mediaHydrator = new MediaHydrator();
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateProfile(\StdClass $data): void
    {
        $this->profile->setId((int)$data->id);
        $this->profile->setUserName($data->username);
        $this->profile->setFullName($data->full_name);
        $this->profile->setBiography($data->biography);
        $this->profile->setExternalUrl($data->external_url);
        $this->profile->setFollowers($data->edge_followed_by->count);
        $this->profile->setFollowing($data->edge_follow->count);
        $this->profile->setProfilePicture($data->profile_pic_url_hd);
        $this->profile->setPrivate($data->is_private);
        $this->profile->setVerified($data->is_verified);
        $this->profile->setMediaCount($data->edge_owner_to_timeline_media->count);
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateMedias(\StdClass $data): void
    {
        // reset medias
        $this->profile->setMedias([]);

        foreach ($data->edge_owner_to_timeline_media->edges as $item) {
            $media = $this->mediaHydrator->hydrateMediaFromProfile($item->node);
            $this->profile->addMedia($media);
        }

        $this->profile->setHasMoreMedias($data->edge_owner_to_timeline_media->page_info->end_cursor != null);
        $this->profile->setEndCursor($data->edge_owner_to_timeline_media->page_info->end_cursor);
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }
}
