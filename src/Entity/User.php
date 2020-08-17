<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

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
class User extends BaseUser implements UserInterface
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

     return $this;
  }

    public function isInterestedIn($question)
    {
        $interest = new Interest();
        $interest->setQuestion($question);
        $interest->setUserId($this->getId());
        $interest->save();
    }
  
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->getNickname();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_SUBSCRIBER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->getSha1Password();
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
