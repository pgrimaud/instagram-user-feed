<?php

declare(strict_types=1);

namespace Instagram\Utils;

use Instagram\Exception\InstagramDownloadException;

class MediaDownloadHelper
{
    /**
     * @param string $url URL of the content to be downloaded
     * @param string $folder Directory where the content will be downloaded (default directory is "assets" folder in the dependency folder)
     *
     * @throws InstagramDownloadException
     */
    public static function downloadMedia(string $url, string $folder = __DIR__ . '/../../../assets'): string
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InstagramDownloadException('Media url is not valid');
        }

        $fileName = substr(str_replace('/', '-', parse_url($url, PHP_URL_PATH)), 1);
        $content = file_get_contents($url);
        file_put_contents($folder . '/' . $fileName, $content);

        return $fileName;
    }
}