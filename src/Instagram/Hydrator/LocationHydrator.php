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

    /**
     * @var MediaHydrator
     */
    private $mediaHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     *
     * @param Location|null $instagramLocation
     */
    public function __construct(Location $instagramLocation = null)
    {
        $this->location      = $instagramLocation ?: new Location();
        $this->mediaHydrator = new MediaHydrator();
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
     * @param \StdClass $data
     */
    public function hydrateMedias(\StdClass $data): void
    {
        // reset medias
        $this->location->setMedias([]);

        foreach ($data->edge_location_to_media->edges as $item) {
            $media = $this->mediaHydrator->hydrateMediaFromProfile($item->node);
            $this->location->addMedia($media);
        }

        $this->location->setHasMoreMedias($data->edge_location_to_media->page_info->end_cursor != null);
        $this->location->setEndCursor($data->edge_location_to_media->page_info->end_cursor);
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
}
