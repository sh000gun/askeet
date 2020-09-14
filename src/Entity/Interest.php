<?php

namespace App\Entity;

use App\Entity\Base\Interest as BaseInterest;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for representing a row from the 'ask_interest' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Interest extends BaseInterest
{
    public function save($con = null)
    {
        $con = Propel::getConnection();

        try {
            $con->beginTransaction();

            $this->doSave($con);

            // update interested_users in question table
            $question = $this->getQuestion();
            $interested_users = $question->getInterestedUsers();
            $question->setInterestedUsers($interested_users + 1);
            $question->save($con);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }
}
