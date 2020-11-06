<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Location;

class LocationHydrator
{
    /**
     * @var Location
     */
    private $location;

    public function __construct()
    {
        $this->location = new Location();
    }

    public function hydrateLocation(\StdClass $data): void
    {
        $this->location->setId((int)$data->id);
        $this->location->setName($data->name);
        $this->location->setHasPublicPage($data->has_public_page);
        $this->location->setLatitude($data->lat);
        $this->location->setLongitude($data->lng);
        $this->location->setSlug($data->slug);
        $this->location->setDescription($data->blurb);
        $this->location->setWebsite($data->website);
        $this->location->setPhone($data->phone);
        $this->location->setFacebookAlias($data->primary_alias_on_fb);
        $this->location->setAddress(json_decode($data->address_json, true));
        $this->location->setProfilePicture($data->profile_pic_url);
        $this->location->setTotalMedia($data->edge_location_to_media->count);
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
}
