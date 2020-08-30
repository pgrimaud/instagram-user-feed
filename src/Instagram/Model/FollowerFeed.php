<?php

declare(strict_types=1);

namespace Instagram\Model;

class FollowerFeed
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
    private $users = [];

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
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @param bool $hasNextPage
     */
    public function setHasNextPage(bool $hasNextPage): void
    {
        $this->hasNextPage = $hasNextPage;
    }

    /**
     * @return string
     */
    public function getEndCursor(): ?string
    {
        return $this->endCursor;
    }

    /**
     * @param string $endCursor
     */
    public function setEndCursor(?string $endCursor): void
    {
        $this->endCursor = $endCursor;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUsers(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @param User[] $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'count'       => $this->count,
            'hasNextPage' => $this->hasNextPage,
            'endCursor'   => $this->endCursor,
            'users'       => $this->users,
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
