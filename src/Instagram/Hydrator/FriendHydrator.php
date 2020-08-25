<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\Friend;

class FriendHydrator
{
    /**
     * @param \StdClass $node
     *
     * @return Friend
     */
    public function friendBaseHydrator(\StdClass $node): Friend
    {
        $friend = new Friend();
        $friend->setId((int)$node->id);
        $friend->setUserName($node->username);
        $friend->setFullName($node->full_name);
        $friend->setProfilePicUrl($node->profile_pic_url);
        $friend->setIsPrivate($node->is_private);
        $friend->setIsVerified($node->is_verified);
        $friend->setFollowedByViewer($node->followed_by_viewer);
        $friend->setRequestedByViewer($node->requested_by_viewer);

        return $friend;
    }
}
