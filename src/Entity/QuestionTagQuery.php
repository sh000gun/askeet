<?php

namespace App\Entity;

use App\Entity\Base\QuestionTagQuery as BaseQuestionTagQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_interest' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class QuestionTagQuery extends BaseQuestionTagQuery
{
    public static function getTags($questionId)
    {
        return QuestionTagQuery::create()
            ->filterByQuestionId($questionId)
            ->select('NormalizedTag')
            ->distinct()
            ->orderByNormalizedTag('asc')
            ->find();
    }
}
