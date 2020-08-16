<?php

namespace App\Entity;

use App\Entity\Base\UserQuery as BaseUserQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_user' table.
 *
 *
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
}
