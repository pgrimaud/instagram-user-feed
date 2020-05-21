<?php

declare(strict_types=1);

namespace Instagram;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\{SetCookie, CookieJar};
use Instagram\Auth\{Login, Session};
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\{StoriesHydrator, StoryHighlightsHydrator, ProfileHydrator};
use Instagram\Model\{Profile, ProfileStory, StoryHighlights, StoryHighlightsFolder};
use Instagram\Transport\{HtmlProfileDataFeed,
    JsonMediasDataFeed,
    JsonStoriesDataFeed,
    JsonStoryHighlightsFoldersDataFeed,
    JsonStoryHighlightsStoriesDataFeed
};
use Psr\Cache\CacheItemPoolInterface;

class Api
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Session
     */
    private $session = null;

    /**
     * @param CacheItemPoolInterface $cachePool
     * @param Client|null            $client
     */
    public function __construct(CacheItemPoolInterface $cachePool, Client $client = null)
    {
        $this->cachePool = $cachePool;
        $this->client    = $client ?: new Client();
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @throws Exception\InstagramAuthException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function login(string $username, string $password): void
    {
        $login = new Login($this->client, $username, $password);

        // fetch previous session an re-use it
        $sessionData = $this->cachePool->getItem(Session::SESSION_KEY);
        $cookies     = $sessionData->get();

        if ($cookies instanceof CookieJar) {
            /** @var SetCookie */
            $session = $cookies->getCookieByName('sessionId');

            // Session expired (should never happened, Instagram TTL is ~ 1 year)
            if ($session->getExpires() < time()) {
                $this->logout();
                $this->login($username, $password);
            }

        } else {
            $cookies = $login->process();
            $sessionData->set($cookies);
            $this->cachePool->save($sessionData);
        }

        $this->session = new Session($cookies);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function logout(): void
    {
        $this->cachePool->deleteItem(Session::SESSION_KEY);
    }

    /**
     * @param string $user
     *
     * @return Profile
     *
     * @throws InstagramException
     */
    public function getProfile(string $user): Profile
    {
        $feed = new HtmlProfileDataFeed($this->client, $this->session);
        $data = $feed->fetchData($user);

        $hydrator = new ProfileHydrator();
        $hydrator->hydrateProfile($data);
        $hydrator->hydrateMedias($data);

        return $hydrator->getProfile();
    }

    /**
     * @param Profile $instagramProfile
     *
     * @return Profile
     *
     * @throws InstagramException
     */
    public function getMoreMedias(Profile $instagramProfile): Profile
    {
        $feed = new JsonMediasDataFeed($this->client, $this->session);
        $data = $feed->fetchData($instagramProfile);

        $hydrator = new ProfileHydrator($instagramProfile);
        $hydrator->hydrateMedias($data);

        return $hydrator->getProfile();
    }

    /**
     * @param int $userId
     *
     * @return ProfileStory
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getStories(int $userId): ProfileStory
    {
        $feed = new JsonStoriesDataFeed($this->client, $this->session);
        $data = $feed->fetchData($userId);

        $hydrator = new StoriesHydrator();
        $hydrator->hydrateStories($data);

        return $hydrator->getStories();
    }

    /**
     * @param int $userId
     *
     * @return StoryHighlights
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getStoryHighlightsFolder(int $userId): StoryHighlights
    {
        $feed = new JsonStoryHighlightsFoldersDataFeed($this->client, $this->session);
        $data = $feed->fetchData($userId);

        $hydrator = new StoryHighlightsHydrator();
        $hydrator->hydrateFolders($data);

        return $hydrator->getHighlights();
    }

    /**
     * @param StoryHighlightsFolder $folder
     *
     * @return StoryHighlightsFolder
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getStoriesOfHighlightsFolder(StoryHighlightsFolder $folder): StoryHighlightsFolder
    {
        $feed = new JsonStoryHighlightsStoriesDataFeed($this->client, $this->session);
        $data = $feed->fetchData($folder);

        $hydrator = new StoryHighlightsHydrator();
        $hydrator->hydrateHighLights($folder, $data);

        return $hydrator->getFolder();
    }
}
