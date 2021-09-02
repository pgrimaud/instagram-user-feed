<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Live;

class LiveHydrator
{
    private $live;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     *
     * @param Live|null $instagramLive
     */
    public function __construct(Live $instagramLive = null)
    {
        $this->live = $instagramLive ?: new Live();
    }

    /**
     * @param \StdClass $node
     *
     * @return void
     */
    public function liveBaseHydrator(\StdClass $node): void
    {
        $this->live->setBroadcastId((int) $node->broadcast_id);
        $this->live->setBroadcastDict($node->broadcast_dict);
    }

    /**
     * @return Live
     */
    public function getLive(): Live
    {
        return $this->live;
    }
}
