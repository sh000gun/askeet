<?php

namespace App\Lib;

use Symfony\Component\Validator\Constraints as Assert;

class myLoginValidator
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2")
     *
     * @var string
     */
    public $nickname;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     */
    public $password;

    public $_target_path;
}
