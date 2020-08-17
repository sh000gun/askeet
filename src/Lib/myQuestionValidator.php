<?php

namespace App\Lib;

use Symfony\Component\Validator\Constraints as Assert;

class myQuestionValidator
{

    /**
     * @Assert\NotBlank(message="question.title.not_blank")
     * @var string
     */
    public $title;

    /**
     * @Assert\NotBlank(message="question.body.not_blank")
     * @Assert\Length(min="10", minMessage="question.body.length")
     * @var string
     */
    public $body;
}
