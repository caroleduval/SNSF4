<?php

namespace App\UI\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{
    /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Mot de passe trop court (6 caractères minimum)"
     * )
     */
    protected $password;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}