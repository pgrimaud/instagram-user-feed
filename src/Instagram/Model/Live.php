<?php

declare(strict_types=1);

namespace Instagram\Model;

class Live
{
    /**
     * @var int
     */
    private $broadcastId;

    /**
     * @var object
     */
    private $broadcastDict;

    /**
     * @return string
     */
    public function getBroadcastId(): int
    {
        return $this->broadcastId;
    }

    /**
     * @param string $broadcast_id
     */
    public function setBroadcastId(int $broadcast_id): void
    {
        $this->broadcastId = $broadcast_id;
    }

    /**
     * @return object
     */
    public function getBroadcastDict(): object
    {
        return $this->broadcastDict;
    }

    /**
     * @param object $broadcast_dict
     */
    public function setBroadcastDict(object $broadcast_dict): void
    {
        $this->broadcastDict = $broadcast_dict;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'broadcastId'   => $this->broadcastId,
            'broadcastDict' => $this->broadcastDict,
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