<?php

namespace App\UI\Form\Handler;

use App\Domain\Repository\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegistrationHandler
{
    /**
     * @var UserManager
     */
    private $userManager;

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
     * RegistrationHandler constructor.
     * @param UserManager $userManager
     * @param UserPasswordEncoderInterface $encoder
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserManager $userManager,
        UserPasswordEncoderInterface $encoder,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage)
    {
        $this->userManager=$userManager;
        $this->encoder=$encoder;
        $this->session=$session;
        $this->tokenStorage=$tokenStorage;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $password = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $user->setRoles(['ROLE_USER']);

            $this->userManager->addUser($user);

            $token = new UsernamePasswordToken($user->getUsername(), null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', serialize($token));
            return true;
        } else {
            return false;
        }
    }
}
