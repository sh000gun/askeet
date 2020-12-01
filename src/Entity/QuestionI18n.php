<?php

namespace App\Entity;

use App\Entity\Base\QuestionI18n as BaseQuestionI18n;
use Michelf\Markdown;

/**
 * Skeleton subclass for representing a row from the 'ask_question_i18n' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class QuestionI18n extends BaseQuestionI18n
{
    public function setBody($v, $locale = NULL)
    {
        parent::setBody($v, $locale = NULL);

        $v = htmlentities($v, ENT_QUOTES, 'UTF-8');
        $this->setHtmlBody(Markdown::defaultTransform($v));
    }

}
