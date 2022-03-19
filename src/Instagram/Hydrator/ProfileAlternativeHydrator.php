<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Profile;

class ProfileAlternativeHydrator
{
    /**
     * @var Profile
     */
    private $profile;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->profile = new Profile();
    }

    /**
     * @param \StdClass $data
     */
    public function hydrateProfile(\StdClass $data): void
    {
        $this->profile->setId($data->pk);
        $this->profile->setUserName($data->username);
        $this->profile->setFullName($data->full_name);
        $this->profile->setFollowers($data->follower_count ?? 0);
        $this->profile->setPrivate($data->is_private);
        $this->profile->setVerified($data->is_verified);
        $this->profile->setMediaCount($data->media_count ?? 0);

        if (property_exists($data, 'biography')) {
            $this->profile->setBiography($data->biography);
        }

        if (property_exists($data, 'external_url')) {
            $this->profile->setExternalUrl($data->external_url);
        }

        if (property_exists($data, 'following_count')) {
            $this->profile->setFollowing($data->following_count);
        }

        if (property_exists($data, 'hd_profile_pic_url_info')) {
            $this->profile->setProfilePicture($data->hd_profile_pic_url_info->url);
        }
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }
}
