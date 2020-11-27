<?php

namespace App\Lib;

use Symfony\Component\Validator\Constraints as Assert;

class myQuestionSearchValidator
{
    /**
     * @Assert\NotBlank(message="search.not_blank")
     *
     * @var string
     */
    public $search;

    public $search_all;
}
