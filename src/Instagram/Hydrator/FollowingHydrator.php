<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Following;

class FollowingHydrator
{
    /**
     * @var Following
     */
    private $following;

    /**
     * @var FriendHydrator
     */
    private $friendHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->following = new Following();
        $this->friendHydrator = new FriendHydrator();
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateFollowing(\StdClass $node): void
    {
        $this->following->setCount((int)$node->edge_follow->count);
        $this->following->setHasNextPage($node->edge_follow->page_info->has_next_page);
        $this->following->setEndCursor($node->edge_follow->page_info->end_cursor);
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateFriend(\StdClass $node): void
    {
        // reset friends
        $this->following->setFriends([]);
        foreach ($node->edge_follow->edges as $item) {
            $friend = $this->friendHydrator->friendBaseHydrator($item->node);
            $this->following->addFriends($friend);
        }
    }

    /**
     * @return Followers
     */
    public function getFollowings(): Following
    {
        return $this->following;
    }
}
