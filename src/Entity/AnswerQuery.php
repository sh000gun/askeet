<?php

namespace App\Entity;

use App\Entity\Base\AnswerQuery as BaseAnswerQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_answer' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class AnswerQuery extends BaseAnswerQuery
{
    private static function addPermanentTagToCriteria($query, $tag = null)
    {
        if ($tag) {
            $query->useQuestionTagQuery('a', 'left join')
                ->filterByNormalizedTag($tag)
                ->endUse();
        }

        return $query;
    }

    public static function getRecentPager($page, $maxPage = 2, $tag = null)
    {
        $query = AnswerQuery::create();

        if ($tag) {
            $query
            ->joinWith('Question')
            ->joinWith('Question.QuestionTag')
            ->where('QuestionTag.Tag = ?', $tag);
        }

        return $query->find();
    }

    public static function getReportCount()
    {
        return AnswerQuery::create()
            ->where('Answer.Reports > ?', 0)
            ->count();
    }

    public static function getReportPager()
    {
        return AnswerQuery::create()
            ->where('Answer.Reports > ?', 0)
            ->find();
    }

    public static function deleteAnswer($answerId)
    {
        return AnswerQuery::create()
            ->filterById($answerId)
            ->delete();
    }

}
