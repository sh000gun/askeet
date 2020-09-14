<?php

namespace App\Entity;

use App\Entity\Base\ReportQuestionQuery as BaseReportQuestionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_report_question' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ReportQuestionQuery extends BaseReportQuestionQuery
{
    public static function isQuestionReportedByUser($questionId, $userId)
    {
        $count = ReportQuestionQuery::create()
            ->filterByPrimaryKey([$questionId, $userId])
            ->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    public static function resetReport($questionId)
    {
        $question = ReportQuestionQuery::create()
            ->filterByQuestionId($questionId)
            ->findOne()->getQuestion();

        $question->setReports(0);
        $question->save();

        return ReportQuestionQuery::create()
            ->filterByQuestionId($questionId)
            ->delete();
    }
}
