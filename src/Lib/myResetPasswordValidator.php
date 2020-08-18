<?php

namespace App\Lib;

use Symfony\Component\Validator\Constraints as Assert;

class myResetPasswordValidator
{

    /**
     * @Assert\NotBlank(message="resetPassword.email.not_blank")
     * @Assert\Email(message="resetPassword.email.valid")
     * @var string
     */
    public $email;
}
