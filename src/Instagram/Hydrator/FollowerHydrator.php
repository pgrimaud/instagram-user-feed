<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Follower;

class FollowerHydrator
{
    /**
     * @var Follower
     */
    private $follower;

    /**
     * @var FriendHydrator
     */
    private $friendHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->follower = new Follower();
        $this->friendHydrator = new FriendHydrator();
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateFollower(\StdClass $node): void
    {
        $this->follower->setCount((int)$node->edge_followed_by->count);
        $this->follower->setHasNextPage($node->edge_followed_by->page_info->has_next_page);
        $this->follower->setEndCursor($node->edge_followed_by->page_info->end_cursor);
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateFriend(\StdClass $node): void
    {
        // reset friends
        $this->follower->setFriends([]);
        foreach ($node->edge_followed_by->edges as $item) {
            $friend = $this->friendHydrator->friendBaseHydrator($item->node);
            $this->follower->addFriends($friend);
        }
    }

    /**
     * @return Followers
     */
    public function getFollowers(): Follower
    {
        return $this->follower;
    }
}
