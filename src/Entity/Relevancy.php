<?php

namespace App\Entity;

use App\Entity\Base\Relevancy as BaseRelevancy;
use Propel\Runtime\Propel;
use App\Entity\Answer;

/**
 * Skeleton subclass for representing a row from the 'ask_relevancy' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Relevancy extends BaseRelevancy
{
  public function save($con = null)
  {
    $con = Propel::getConnection();

    try
    {
      $con->beginTransaction();

      $this->doSave($con);

      // update relevrancy in answer table
      $answer = $this->getAnswer();
      if ($this->getScore() == 1)
      {
        $answer->setRelevancyUp($answer->getRelevancyUp() + 1);
      }
      else
      {

        $answer->setRelevancyDown($answer->getRelevancyDown() + 1);
      }
      $answer->save($con);

      $con->commit();
    }
    catch (Exception $e)
    {
      $con->rollback();
      throw $e;
    }
  }
}
