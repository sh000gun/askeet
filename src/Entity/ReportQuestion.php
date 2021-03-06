<?php

namespace App\Entity;

use App\Entity\Base\ReportQuestion as BaseReportQuestion;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for representing a row from the 'ask_report_question' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ReportQuestion extends BaseReportQuestion
{
    public function save($con = null)
    {
        $con = Propel::getConnection();

        try {
            $con->beginTransaction();
            $this->doSave($con);

            $question = $this->getQuestion();
            $question->setReports($question->getReports() + 1);
            $question->save($con);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
}
