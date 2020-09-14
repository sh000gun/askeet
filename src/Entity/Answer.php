<?php

namespace App\Entity;

use App\Entity\Base\Answer as BaseAnswer;

/**
 * Skeleton subclass for representing a row from the 'ask_answer' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Answer extends BaseAnswer
{
    public function getRelevancyUpPercent()
    {
        $total = $this->getRelevancyUp() + $this->getRelevancyDown();

        return $total ? sprintf('%.0f', $this->getRelevancyUp() * 100 / $total) : 0;
    }

    public function getRelevancyDownPercent()
    {
        $total = $this->getRelevancyUp() + $this->getRelevancyDown();

        return $total ? sprintf('%.0f', $this->getRelevancyDown() * 100 / $total) : 0;
    }
}
