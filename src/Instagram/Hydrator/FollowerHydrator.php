<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\FollowerFeed;

class FollowerHydrator
{
    /**
     * @var FollowerFeed
     */
    private $followerFeed;

    /**
     * @var UserHydrator
     */
    private $userHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->followerFeed = new FollowerFeed();
        $this->userHydrator = new UserHydrator();
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateFollowerFeed(\StdClass $node): void
    {
        $this->followerFeed->setCount((int)$node->edge_followed_by->count);
        $this->followerFeed->setHasNextPage($node->edge_followed_by->page_info->has_next_page);
        $this->followerFeed->setEndCursor($node->edge_followed_by->page_info->end_cursor);
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateUsers(\StdClass $node): void
    {
        // reset users
        $this->followerFeed->setUsers([]);

        foreach ($node->edge_followed_by->edges as $item) {
            $user = $this->userHydrator->userBaseHydrator($item->node);
            $this->followerFeed->addUsers($user);
        }
    }

    /**
     * @return FollowerFeed
     */
    public function getFollowers(): FollowerFeed
    {
        return $this->followerFeed;
    }
}
