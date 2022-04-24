<?php

declare(strict_types=1);

namespace Instagram\Hydrator;

use Instagram\Model\User;

class UserInfoHydrator
{
    /**
     * @param \StdClass $node
     *
     * @return User
     */
    public function userBaseHydrator(\StdClass $node): User
    {
        $user = new User();
        $user->setId((int)$node->pk);
        $user->setUserName($node->username);
        $user->setFullName($node->full_name);
        $user->setProfilePicUrl($node->profile_pic_url);
        $user->setIsPrivate($node->is_private);
        $user->setIsVerified($node->is_verified);

        return $user;
    }
}
