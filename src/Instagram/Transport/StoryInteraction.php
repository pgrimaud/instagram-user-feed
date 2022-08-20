<?php

declare(strict_types=1);

namespace Instagram\Transport;

use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\Endpoints;
use Instagram\Utils\OptionHelper;

class StoryInteraction extends AbstractDataFeed
{
    /**
     * @param int $storyId
     * @param int $ownerId
     * @param int $takenAt
     * @param int $seenAt
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function seen(int $storyId, int $ownerId, int $takenAt, int $seenAt): string
    {
        $params = [
            'reelMediaId'      => $storyId,
            'reelMediaOwnerId' => $ownerId,
            'reelId'           => $ownerId,
            'reelMediaTakenAt' => $takenAt,
            'viewSeenAt'       => $seenAt
        ];

        return $this->postData(Endpoints::SEEN_STORY_URL, $params);
    }

    /**
     * @param int $storyId
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function like(int $storyId): string
    {
        $params['media_id'] = $storyId;
        return $this->postData(Endpoints::LIKE_STORY_URL, $params);
    }

    /**
     * @param int $storyId
     *
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function unlike(int $storyId): string
    {
        $params['media_id'] = $storyId;
        return $this->postData(Endpoints::UNLIKE_STORY_URL, $params);
    }

    /**
     * @param string $endpoint
     * @param string $formParameters
     * 
     * @return string
     *
     * @throws InstagramFetchException
     */
    public function postData(string $endpoint, array $formParameters = []): string
    {
        $csrfToken = '';

        if (!empty($this->session->getCookies()->getCookieByName("csrftoken"))) {
            $csrfToken = $this->session->getCookies()->getCookieByName("csrftoken")->getValue();
        }

        $options = [
            'headers' => [
                'user-agent'      => OptionHelper::$USER_AGENT,
                'accept-language' => OptionHelper::$LOCALE,
                'x-csrftoken'     => $csrfToken,
                'x-ig-app-id'     => 1217981644879628,
            ],
            'cookies' => $this->session->getCookies(),
        ];

        if (count($formParameters) > 0) {
            $options = array_merge($options, [
                'form_params' => $formParameters,
            ]);
        }

        try {
            $res = $this->client->request('POST', $endpoint, $options);
        } catch (ClientException $exception) {
            throw new InstagramFetchException("StoryInteraction error, {$exception->getMessage()}");
        }

        $data = (string) $res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramAuthException('StoryInteraction error, Unable to get JSON data!');
        }

        return $data->status;
    }
}
