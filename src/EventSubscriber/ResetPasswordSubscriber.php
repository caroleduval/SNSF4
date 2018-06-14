<?php

namespace App\EventSubscriber;

use App\Domain\Model\User;
use App\Domain\Repository\UserManager;
use App\Events;
use Twig\Environment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\RouterInterface;

/**
 * Envoi un email avec un lien de mise à jour du mot de passe
 *
 */
class ResetPasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var RouterInterface $router
     */
    private $router;

    /**
     * @var
     */
    private $sender;

    /**
     * @var UserManager
     */
    private $manager;

    public function __construct(
        \Swift_Mailer $mailer,
        Environment $twig,
        RouterInterface $router,
        UserManager $manager,
        $sender)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->manager=$manager;
        $this->sender = $sender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::NEW_PASSWORD_REQUESTED => 'onNewPasswordRequested',
        ];
    }

    /**
     * @param GenericEvent $event
     * @throws \Exception
     */
    public function onNewPasswordRequested(GenericEvent $event): void
    {
        /**
         * @var User $user
         */
        $user = $event->getSubject();

//        $user->generateResetToken(new \DateInterval('PT15M'));
        $user->generateResetToken(new \DateInterval('P1D'));
        $this->manager->editUser();

        $message = (new \Swift_Message())
            ->setFrom($this->sender)
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                 'Mail/RequestPasswordMail.html.twig',[
                    'username' => $user->getUsername(),
                     'request_link'=>$this->router->generate('reset_password',
                        ['token' => $user->getResetToken()], true)]
                ),
                'text/html');

        $this->mailer->send($message);
    }
}