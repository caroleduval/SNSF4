<?php

namespace App\UI\Form\Handler;

use App\Domain\Repository\UserManager;
use Symfony\Component\Form\FormInterface;

class EditProfileHandler
{
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * EditTrickHandler constructor.
     * @param UserManager $manager
     */
    public function __construct(
        UserManager $manager
    )
    {
        $this->manager=$manager;
    }

    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $this->manager->editUser();
            return true;
        } else {
            return false;
        }
    }
}
