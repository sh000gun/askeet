<?php

namespace App\Entity;

use App\Entity\Base\QuestionTag as BaseQuestionTag;
use App\Lib\Tag;

/**
 * Skeleton subclass for representing a row from the 'ask_user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class QuestionTag extends BaseQuestionTag
{
    public function setTag($v)
    {
        parent::setTag($v);

        $this->setNormalizedTag(Tag::normalize($v));
    }
}
