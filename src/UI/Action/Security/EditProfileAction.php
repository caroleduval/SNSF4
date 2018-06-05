<?php

namespace App\UI\Action\Security;

use App\Domain\Repository\UserManager;
use App\UI\Form\Type\ProfileType;
use App\UI\Form\Handler\EditProfileHandler;
use App\UI\Responder\Security\EditProfileResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/edit_profile/{username}", name="edit_profile", methods={"GET","POST"})
 */
class EditProfileAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EditProfileHandler
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
     * @param EditProfileHandler $handler
     * @param SessionInterface $session
     * @param UserManager $manager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EditProfileHandler $handler,
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
     * @param EditProfileResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        EditProfileResponder $responder): Response
    {
        $user=$this->manager
            ->findUserbyUsername($request->attributes->get('username'));

        $form = $this->formFactory
            ->create(ProfileType::class, $user)
            ->handleRequest($request);

        if ($this->handler->handle($form)) {
            $this->session->getFlashBag()->add("success", "Votre profil a été modifié.");
            return $responder(true, $form, $user);
        }

        return $responder(false, $form);
    }
}