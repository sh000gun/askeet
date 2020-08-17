<?php

namespace App\Lib;

use Symfony\Component\Validator\Constraints as Assert;

class myAnswerValidator
{
     public $question_id;

    /**
     * @Assert\NotBlank(message="answer.body.not_blank")
     * @Assert\Length(min="10", minMessage="answer.body.length")
     * @var string
     */
    public $body;
}
