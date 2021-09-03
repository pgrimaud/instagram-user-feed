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
        $this->profile->setId32Bit($data->id);
        $this->profile->setUserName($data->username);
        $this->profile->setFullName($data->full_name);
        $this->profile->setFollowers($data->edge_followed_by->count);
        $this->profile->setPrivate($data->is_private);
        $this->profile->setVerified($data->is_verified);
        $this->profile->setMediaCount($data->edge_owner_to_timeline_media->count);

        if (property_exists($data, 'biography')) {
            $this->profile->setBiography($data->biography);
        }

        if (property_exists($data, 'external_url')) {
            $this->profile->setExternalUrl($data->external_url);
        }

        if (property_exists($data, 'edge_follow')) {
            $this->profile->setFollowing($data->edge_follow->count);
        }

        if (property_exists($data, 'profile_pic_url_hd')) {
            $this->profile->setProfilePicture($data->profile_pic_url_hd);
        } elseif (property_exists($data, 'profile_pic_url')) {
            $this->profile->setProfilePicture($data->profile_pic_url);
        }
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

    public function hydrateIgtvs(\StdClass $data): void
    {
        // reset igtvs
        $this->profile->setIGTV([]);

        foreach ($data->edge_felix_video_timeline->edges as $item) {
            $igtv = $this->mediaHydrator->hydrateMediaFromProfile($item->node);
            $this->profile->addIGTV($igtv);
        }

        $this->profile->setHasMoreIgtvs($data->edge_felix_video_timeline->page_info->end_cursor != null);
        $this->profile->setEndCursorIgtvs($data->edge_felix_video_timeline->page_info->end_cursor);
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }
}
