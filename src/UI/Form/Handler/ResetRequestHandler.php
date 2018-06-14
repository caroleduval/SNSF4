<?php

namespace App\UI\Form\Handler;

use App\Domain\Repository\UserManager;
use App\Events;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ResetRequestHandler
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
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * RegistrationHandler constructor.
     * @param UserManager $userManager
     * @param UserPasswordEncoderInterface $encoder
     * @param SessionInterface $session
     * @param EventDispatcherInterface $dispatcher
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserManager $userManager,
        UserPasswordEncoderInterface $encoder,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher,
        TokenStorageInterface $tokenStorage)
    {
        $this->userManager=$userManager;
        $this->encoder=$encoder;
        $this->session=$session;
        $this->dispatcher=$dispatcher;
        $this->tokenStorage=$tokenStorage;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData()->getUser();

            $event = new GenericEvent($user);
            $this->dispatcher->dispatch(Events::NEW_PASSWORD_REQUESTED, $event);

            return true;
        } else {
            return false;
        }
    }
}
