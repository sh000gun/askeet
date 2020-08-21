<?php

namespace App\Lib;

use Symfony\Component\Validator\Constraints as Assert;

class myQuestionTagValidator
{
    public $question_id;

    /**
     * @Assert\NotBlank(message="tag.not_blank")
     * @var string
     */
    public $tag;
}
