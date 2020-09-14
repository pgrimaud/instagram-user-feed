<?php

declare(strict_types=1);

namespace Instagram;

use GuzzleHttp\{Client, ClientInterface};
use GuzzleHttp\Cookie\{SetCookie, CookieJar};
use Instagram\Auth\{Checkpoint\ImapClient, Login, Session};
use Instagram\Exception\InstagramException;
use Instagram\Hydrator\{MediaHydrator,
    StoriesHydrator,
    StoryHighlightsHydrator,
    ProfileHydrator,
    FollowerHydrator,
    FollowingHydrator
};
use Instagram\Model\{Media,
    MediaDetailed,
    Profile,
    ProfileStory,
    StoryHighlights,
    StoryHighlightsFolder,
    FollowerFeed,
    FollowingFeed
};
use Instagram\Transport\{HtmlProfileDataFeed,
    JsonMediaDetailedDataFeed,
    JsonMediasDataFeed,
    JsonProfileDataFeed,
    JsonStoriesDataFeed,
    JsonStoryHighlightsFoldersDataFeed,
    JsonStoryHighlightsStoriesDataFeed,
    JsonMediaDetailedByShortCodeDataFeed,
    JsonFollowerDataFeed,
    JsonFollowingDataFeed,
    FollowUnfollow
};
use Psr\Cache\CacheItemPoolInterface;

class Api
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Session
     */
    private $session = null;

    /**
     * @param CacheItemPoolInterface $cachePool
     * @param ClientInterface|null $client
     */
    public function __construct(CacheItemPoolInterface $cachePool, ClientInterface $client = null)
    {
        $this->cachePool = $cachePool;
        $this->client    = $client ?: new Client();
    }

    /**
     * @param string $username
     * @param string $password
     * @param ImapClient|null $imapClient
     *
     * @throws Exception\InstagramAuthException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function login(string $username, string $password, ?ImapClient $imapClient = null): void
    {
        $login = new Login($this->client, $username, $password, $imapClient);

        // fetch previous session an re-use it
        $sessionData = $this->cachePool->getItem(Session::SESSION_KEY . '.' . $username);
        $cookies     = $sessionData->get();

        if ($cookies instanceof CookieJar) {
            /** @var SetCookie */
            $session = $cookies->getCookieByName('sessionId');

            // Session expired (should never happened, Instagram TTL is ~ 1 year)
            if ($session->getExpires() < time()) {
                $this->logout($username);
                $this->login($username, $password, $imapClient);
            }

        } else {
            $cookies = $login->process();
            $sessionData->set($cookies);
            $this->cachePool->save($sessionData);
        }

        $this->session = new Session($cookies);
    }

    /**
     * @param string|null $username
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function logout(?string $username = ''): void
    {
        $this->cachePool->deleteItem(Session::SESSION_KEY . ($username !== '' ? '.' . $username : ''));
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
     * @param string $endCursor
     *
     * @return Profile
     *
     * @throws InstagramException
     */
    public function getMoreMediasWithCursor(int $userId, string $endCursor): Profile
    {
        $instagramProfile = new Profile();
        $instagramProfile->setId($userId);
        $instagramProfile->setEndCursor($endCursor);

        return $this->getMoreMedias($instagramProfile);
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

    /**
     * @param Media $media
     *
     * @return MediaDetailed
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getMediaDetailed(Media $media): MediaDetailed
    {
        $feed = new JsonMediaDetailedDataFeed($this->client, $this->session);
        $data = $feed->fetchData($media);

        $hydrator = new MediaHydrator();
        $media    = $hydrator->hydrateMediaDetailed($data->shortcode_media);

        return $media;
    }

    /**
     * @param Media $media
     *
     * @return MediaDetailed
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getMediaDetailedByShortCode(Media $media): MediaDetailed
    {
        $feed = new JsonMediaDetailedByShortCodeDataFeed($this->client, $this->session);
        $data = $feed->fetchData($media);

        $hydrator = new MediaHydrator();
        $media    = $hydrator->hydrateMediaDetailed($data->shortcode_media);

        return $media;
    }

    /**
     * @param int $id
     *
     * @return Profile
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     * @throws InstagramException
     */
    public function getProfileById(int $id): Profile
    {
        $feed     = new JsonProfileDataFeed($this->client, $this->session);
        $userName = $feed->fetchData($id);

        return $this->getProfile($userName);
    }

    /**
     * @param int $id
     *
     * @return FollowerFeed
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getFollowers(int $id): FollowerFeed
    {
        $feed = new JsonFollowerDataFeed($this->client, $this->session);
        $data = $feed->fetchData($id);

        $hydrator = new FollowerHydrator();
        $hydrator->hydrateFollowerFeed($data);
        $hydrator->hydrateUsers($data);

        return $hydrator->getFollowers();
    }

    /**
     * @param int $id
     * @param string $endCursor
     *
     * @return FollowerFeed
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getMoreFollowers(int $id, string $endCursor): FollowerFeed
    {
        $feed = new JsonFollowerDataFeed($this->client, $this->session);
        $data = $feed->fetchMoreData($id, $endCursor);

        $hydrator = new FollowerHydrator();
        $hydrator->hydrateFollowerFeed($data);
        $hydrator->hydrateUsers($data);

        return $hydrator->getFollowers();
    }

    /**
     * @param int $id
     *
     * @return FollowingFeed
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getFollowings(int $id): FollowingFeed
    {
        $feed = new JsonFollowingDataFeed($this->client, $this->session);
        $data = $feed->fetchData($id);

        $hydrator = new FollowingHydrator();
        $hydrator->hydrateFollowingFeed($data);
        $hydrator->hydrateUsers($data);

        return $hydrator->getFollowings();
    }

    /**
     * @param int $id
     * @param string $endCursor
     *
     * @return FollowingFeed
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function getMoreFollowings(int $id, string $endCursor): FollowingFeed
    {
        $feed = new JsonFollowingDataFeed($this->client, $this->session);
        $data = $feed->fetchMoreData($id, $endCursor);

        $hydrator = new FollowingHydrator();
        $hydrator->hydrateFollowingFeed($data);
        $hydrator->hydrateUsers($data);

        return $hydrator->getFollowings();
    }

    /**
     * @param int $accountId
     *
     * @return string
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function follow(int $accountId): string
    {
        $request = new FollowUnfollow($this->client, $this->session);
        return $request->follow($accountId);
    }

    /**
     * @param int $accountId
     *
     * @return string
     *
     * @throws Exception\InstagramAuthException
     * @throws Exception\InstagramFetchException
     */
    public function unfollow(int $accountId): string
    {
        $request = new FollowUnfollow($this->client, $this->session);
        return $request->unfollow($accountId);
    }
}
