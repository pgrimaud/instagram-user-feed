<?php

namespace Instagram\Storage;

class Cache
{
    /**
     * @var string
     */
    public $rhxGis;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var array
     */
    public $cookie = [];

    /**
     * @return string
     */
    public function getRhxGis()
    {
        return $this->rhxGis;
    }

    /**
     * @param string $rhxGis
     */
    public function setRhxGis($rhxGis)
    {
        $this->rhxGis = $rhxGis;
    }

    /**
     * @return array
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @param array $cookie
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
