<?php

declare(strict_types=1);

namespace Instagram\Model;

class Following
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var boolean
     */
    private $hasNextPage;

    /**
     * @var string
     */
    private $endCursor;

    /**
     * @var array
     */
    private $friends = [];

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return boolean
     */
    public function getHasNextPage(): boolean
    {
        return $this->hasNextPage;
    }

    /**
     * @param boolean $hasNextPage
     */
    public function setHasNextPage(bool $hasNextPage): void
    {
        $this->hasNextPage = $hasNextPage;
    }

    /**
     * @return string
     */
    public function getEndCursor(): string
    {
        return $this->endCursor;
    }

    /**
     * @param string $endCursor
     */
    public function setEndCursor(string $endCursor): void
    {
        $this->endCursor = $endCursor;
    }

    /**
     * @return Friend[]
     */
    public function getFriends(): array
    {
        return $this->friends;
    }

    /**
     * @return array
     */
    public function addFriends(Friend $friend): void
    {
        $this->friends[] = $friend;
    }

    /**
     * @param Friend[] $friends
     */
    public function setFriends(array $friends): void
    {
        $this->friends = $friends;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'count'         => $this->count,
            'hasNextPage'   => $this->hasNextPage,
            'endCursor'     => $this->endCursor,
            'friends'       => $this->friends,
        ];
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }
}
