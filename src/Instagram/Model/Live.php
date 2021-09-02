<?php

declare(strict_types=1);

namespace Instagram\Model;

class Live
{
    /**
     * @var string
     */
    private $broadcast_id;

    /**
     * @var object
     */
    private $broadcast_dict;

    /**
     * @return string
     */
    public function getBroadcastId(): string
    {
        return $this->broadcast_id;
    }

    /**
     * @param string $broadcast_id
     */
    public function setBroadcastId(string $broadcast_id): void
    {
        $this->broadcast_id = $broadcast_id;
    }

    /**
     * @return object
     */
    public function getBroadcastDict(): object
    {
        return $this->broadcast_dict;
    }

    /**
     * @param object $broadcast_dict
     */
    public function setBroadcastDict(object $broadcast_dict): void
    {
        $this->broadcast_dict = $broadcast_dict;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'broadcast_id'      => $this->broadcast_id,
            'broadcast_dict'    => $this->broadcast_dict,
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