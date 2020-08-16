<?php

namespace App\Entity;

use App\Entity\Base\Question as BaseQuestion;
use App\Lib\myTools;

use Michelf\Markdown;

/**
 * Skeleton subclass for representing a row from the 'ask_question' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Question extends BaseQuestion
{
    public function setTitle($v)
    {
        parent::setTitle($v);
 
        $this->setStrippedTitle(myTools::stripText($v));
    }

    public function setBody($v)
    {
        parent::setBody($v);

        $v = htmlentities($v, ENT_QUOTES, 'UTF-8');
        $this->setHtmlBody(Markdown::defaultTransform($v));
  }
}
