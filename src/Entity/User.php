<?php

namespace App\Entity;

use App\Entity\Base\User as BaseUser;

/**
 * Skeleton subclass for representing a row from the 'ask_user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class User extends BaseUser
{
  public function __toString()
  {
    return $this->getFirstName().' '.$this->getLastName();
  }

  public function setPassword($password)
  {
    $salt = md5(rand(100000, 999999).$this->getNickname().$this->getEmail());
    $this->setSalt($salt);
    $this->setSha1Password(sha1($salt.$password));
  }
}
