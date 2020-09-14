<?php

namespace App\Entity;

use App\Entity\Base\ReportAnswerQuery as BaseReportAnswerQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'ask_report_answer' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ReportAnswerQuery extends BaseReportAnswerQuery
{
    public static function isAnswerReportedByUser($answerId, $userId)
    {
        $count = ReportAnswerQuery::create()
            ->filterByPrimaryKey([$answerId, $userId])
            ->count();

        if ($count > 0) {
            return true;
        }

        return false;
    }

    public static function resetReport($answerId)
    {
        $answer = ReportAnswerQuery::create()
            ->filterByAnswerId($answerId)
            ->findOne()->getAnswer();

        $answer->setReports(0);
        $answer->save();

        return ReportAnswerQuery::create()
            ->filterByAnswerId($answerId)
            ->delete();
    }

}
