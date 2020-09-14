<?php

namespace App\Entity;

use App\Entity\Base\UserQuery as BaseUserQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserQuery extends BaseUserQuery
{
    public static function retrieveByNickname($nickname)
    {
        return UserQuery::create()
          ->filterByNickname($nickname)
          ->findOne();
    }

    public static function isModeratorCandidate($userId)
    {
        $count = UserQuery::create()
            ->where('User.Id = ?', $userId)
            ->where('User.IsModerator = ?', 1)
            ->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    public static function getModeratorCandidatesCount()
    {
        return UserQuery::create()
            ->where('User.IsModerator = ?', 1)
            ->count();
    }

    public static function getModeratorCandidates()
    {
        return UserQuery::create()
            ->where('User.IsModerator = ?', 1)
            ->find();
    }

    public static function getModerators()
    {
        return UserQuery::create()
            ->where('User.IsModerator = ?', 2)
            ->find();
    }

    public static function getAdministrators()
    {
        return UserQuery::create()
            ->filterByIsAdministrator(true)
            ->find();
    }

    public static function getProblematicUsers()
    {
        return UserQuery::create()
            ->where('User.Deletions > ?', 0)
            ->find();
    }

    public static function getProblematicUsersCount()
    {
        return UserQuery::create()
            ->where('User.Deletions > ?', 0)
            ->count();
    }


    public static function deleteUser($userId)
    {
        return UserQuery::create()
            ->filterById($userId)
            ->delete();
    }

    public static function refuseModerator($userId)
    {
         $user = UserQuery::create()
             ->filterById($userId)
            ->findOne();
        
        if ($user)
        {
            $user->setIsModerator(0);
            $user->save();

            return true;
        }

        return false;
    }

    public static function grantModerator($userId)
    {
         $user = UserQuery::create()
             ->filterById($userId)
            ->findOne();
        
        if ($user)
        {
            $user->setIsModerator(2);
            $user->save();

            return true;
        }

        return false;
    }

    public static function grantAdministrator($userId)
    {
         $user = UserQuery::create()
             ->filterById($userId)
            ->findOne();
        
        if ($user)
        {
            $user->setIsAdministrator(1);
            $user->save();

            return true;
        }

        return false;
    }

}
