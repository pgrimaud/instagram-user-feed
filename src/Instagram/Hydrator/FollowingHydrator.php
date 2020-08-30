<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\FollowingFeed;

class FollowingHydrator
{
    /**
     * @var FollowingFeed
     */
    private $followingFeed;

    /**
     * @var UserHydrator
     */
    private $userHydrator;

    /**
     * Hydration is made manually to avoid shitty Instagram variable names
     */
    public function __construct()
    {
        $this->followingFeed = new FollowingFeed();
        $this->userHydrator  = new UserHydrator();
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateFollowingFeed(\StdClass $node): void
    {
        $this->followingFeed->setCount((int)$node->edge_follow->count);
        $this->followingFeed->setHasNextPage($node->edge_follow->page_info->has_next_page);
        $this->followingFeed->setEndCursor($node->edge_follow->page_info->end_cursor);
    }

    /**
     * @param \StdClass $node
     */
    public function hydrateUsers(\StdClass $node): void
    {
        // reset users
        $this->followingFeed->setUsers([]);

        foreach ($node->edge_follow->edges as $item) {
            $user = $this->userHydrator->userBaseHydrator($item->node);
            $this->followingFeed->addUser($user);
        }
    }

    /**
     * @return FollowingFeed
     */
    public function getFollowings(): FollowingFeed
    {
        return $this->followingFeed;
    }
}
