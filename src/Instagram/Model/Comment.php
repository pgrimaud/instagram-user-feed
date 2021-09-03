<?php

declare(strict_types=1);

namespace Instagram\Model;

class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var \StdClass
     */
    private $owner;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * @param string|null $caption
     */
    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
    }

    /**
     * @return \StdClass
     */
    public function getOwner(): \StdClass
    {
        return $this->owner;
    }

    /**
     * @param \StdClass $owner
     */
    public function setOwner(\StdClass $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'                   => $this->id,
            'date'                 => $this->date,
            'caption'              => $this->caption,
            'owner'                => $this->owner
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
