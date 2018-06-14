<?php

namespace App\UI\Action\Security;

use App\UI\Form\Model\ResetPassword;
use App\Domain\Repository\UserManager;
use App\UI\Form\Type\ResetPasswordType;
use App\UI\Form\Handler\ResetPasswordHandler;
use App\UI\Responder\Security\LoginResponder;
use App\UI\Responder\Security\ResetPasswordResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reset_password", name="reset_password", methods={"GET","POST"})
 */
class ResetPasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetPasswordHandler
     */
    private $handler;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserManager
     */
    private $manager;

    /**
     * EditProfileAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param ResetPasswordHandler $handler
     * @param SessionInterface $session
     * @param UserManager $manager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ResetPasswordHandler $handler,
        SessionInterface $session,
        UserManager $manager)
    {
        $this->formFactory=$formFactory;
        $this->handler=$handler;
        $this->session=$session;
        $this->manager=$manager;
    }

    /**
     * @param Request $request
     * @param ResetPasswordResponder $responder
     * @param LoginResponder $loginResponder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        ResetPasswordResponder $responder,
        LoginResponder $loginResponder
    ): Response
    {
//        /reset_password?token=57c5e15ae333481c8cff63c091257cf7   =>KO
//        /reset_password?token=831df2109a1c4a2fb0c76e4eb5c08731   =>OK
        $token=$request->get('token');
        $user=$this->manager->findUserbyToken($token);

        try {
            $form = $this->formFactory
                ->create(ResetPasswordType::class, new ResetPassword())
                ->handleRequest($request);
            if ($this->handler->handle($form,$user)) {
                $this->session->getFlashBag()->add('info', "Votre mot de passe a été modifié.");
                return $responder(true, $form);
            }
            return $responder(false, $form);
        } catch (\Exception $ex) {
            $this->session->getFlashBag()->add('error', $ex->getMessage());
            return $loginResponder([]);
        }
    }
}