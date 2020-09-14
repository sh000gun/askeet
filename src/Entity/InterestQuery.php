<?php

namespace App\Entity;

use App\Entity\Base\InterestQuery as BaseInterestQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_interest' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InterestQuery extends BaseInterestQuery
{
    public static function isQuestionInterestedByUser($questionId, $userId)
    {
        $count = InterestQuery::create()
            ->filterByPrimaryKey([$questionId, $userId])
            ->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }
}
