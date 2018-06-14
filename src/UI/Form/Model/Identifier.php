<?php

namespace App\UI\Form\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class Identifier
{
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var
     */
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    function getUser()
    {
        return $this->user;
    }

    function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
}