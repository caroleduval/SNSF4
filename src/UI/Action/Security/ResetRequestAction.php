<?php

namespace App\UI\Action\Security;

use App\Domain\Repository\UserManager;
use App\UI\Form\Model\Identifier;
use App\UI\Form\Type\ResetRequestType;
use App\UI\Form\Handler\ResetRequestHandler;
use App\UI\Responder\Security\ResetRequestResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reset_request", name="reset_request", methods={"GET","POST"})
 */
class ResetRequestAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetRequestHandler
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
     * @param ResetRequestHandler $handler
     * @param SessionInterface $session
     * @param UserManager $manager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ResetRequestHandler $handler,
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
     * @param ResetRequestResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        ResetRequestResponder $responder
    ): Response
    {
                $identifier = new Identifier();

                $form = $this->formFactory
                    ->create(ResetRequestType::class, $identifier)
                    ->handleRequest($request);

                if ($this->handler->handle($form)) {
                    $this->session->getFlashBag()->add("info", "Un mail de réinitialisation vous a été adressé.");
                    return $responder(true, $form);
                }

                return $responder(false, $form);
    }
}