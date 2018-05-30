<?php

namespace App\UI\Action\Security;

use App\UI\Form\Type\UserType;
use App\UI\Form\Handler\RegistrationHandler;
use App\UI\Responder\Security\RegistrationResponder;
use App\Domain\Model\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/register", name="user_registration", methods={"GET","POST"})
 */
class RegistrationAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RegistrationHandler
     */
    private $handler;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        FormFactoryInterface $formFactory,
        RegistrationHandler $handler,
        SessionInterface $session)
    {
        $this->formFactory=$formFactory;
        $this->handler=$handler;
        $this->session=$session;
    }

    /**
     * @param Request $request
     * @param RegistrationResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        RegistrationResponder $responder): Response
    {
        $user = new User();

        $form = $this->formFactory
            ->create(UserType::class, $user)
            ->handleRequest($request);

        if ($this->handler->handle($form)) {
            $this->session->getFlashBag()->add("success", "L'utilisateur a bien été ajouté.");
            return $responder(true, $form);
        }

        return $responder(false, $form);
    }
}