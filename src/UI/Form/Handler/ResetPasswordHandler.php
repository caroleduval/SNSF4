<?php

namespace App\UI\Form\Handler;

use App\Domain\Repository\UserManager;
use App\Domain\Model\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResetPasswordHandler
{
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * EditPasswordHandler constructor.
     * @param UserManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserManager $manager,
        UserPasswordEncoderInterface $encoder,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage)
    {
        $this->manager=$manager;
        $this->encoder=$encoder;
        $this->session=$session;
        $this->tokenStorage=$tokenStorage;
    }

    public function handle(FormInterface $form, User $user): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $password=$form->getData()->getPassword();
//            dump($password);die;

            $password = $this->encoder->encodePassword($user,$password);
//            dump($password);die;
            $user->setPassword($password);
            $user->clearResetToken();

            $this->manager->editUser();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', serialize($token));

            return true;
        } else {
            return false;
        }
    }
}