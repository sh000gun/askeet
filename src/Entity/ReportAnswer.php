<?php

namespace App\Entity;

use App\Entity\Base\ReportAnswer as BaseReportAnswer;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for representing a row from the 'ask_report_answer' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ReportAnswer extends BaseReportAnswer
{
    public function save($con = null)
    {
        $con = Propel::getConnection();

        try {
            $con->beginTransaction();
            $this->doSave($con);

            $answer = $this->getAnswer();
            $answer->setReports($answer->getReports() + 1);
            $answer->save($con);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
}
