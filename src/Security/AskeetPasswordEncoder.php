<?php

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;

class AskeetPasswordEncoder extends BasePasswordEncoder
{
    private $ignorePasswordCase;

    /**
     * Constructor.
     *
     * @param Boolean $ignorePasswordCase Compare password case-insensitive
     */
    public function __construct($ignorePasswordCase = false)
    {
        $this->ignorePasswordCase = $ignorePasswordCase;
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        return sha1($salt.$raw);
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        $pass2 = sha1($salt.$raw);

        if (!$this->ignorePasswordCase) {
            return $this->comparePasswords($encoded, $pass2);
        }

        return $this->comparePasswords(strtolower($encoded), strtolower($pass2));
    }
}
