<?php

namespace Instagram\Storage;

use Instagram\Exception\CacheException;

class CacheManager
{
    /**
     * @var string
     */
    private $cacheDir = null;

    /**
     * CacheManager constructor.
     * @param $cacheDir
     */
    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @param $userId
     * @return string
     */
    private function getCacheFile($userId)
    {
        return $this->cacheDir . $userId . '.cache';
    }

    /**
     * @param $userId
     * @return Cache|mixed
     */
    public function getCache($userId)
    {
        if (is_file($this->getCacheFile($userId))) {
            $handle = fopen($this->getCacheFile($userId), 'r');
            $data   = fread($handle, filesize($this->getCacheFile($userId)));
            $cache  = unserialize($data);

            fclose($handle);

            if ($cache instanceof Cache) {
                return $cache;
            }
        }

        return new Cache();
    }

    /**
     * @param Cache $cache
     * @param $userName
     * @throws CacheException
     */
    public function set(Cache $cache, $userName)
    {
        if (!is_writable(dirname($this->getCacheFile($userName)))) {
            throw new CacheException('Cache folder is not writable');
        }

        $data   = serialize($cache);
        $handle = fopen($this->getCacheFile($userName), 'w+');

        fwrite($handle, $data);
        fclose($handle);
    }
}
