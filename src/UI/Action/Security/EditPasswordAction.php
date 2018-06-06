<?php

namespace App\UI\Action\Security;

use App\UI\Form\Model\ChangePassword;
use App\Domain\Repository\UserManager;
use App\UI\Form\Type\EditPasswordType;
use App\UI\Form\Handler\EditPasswordHandler;
use App\UI\Responder\Security\EditPasswordResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/change_password/{username}", name="edit_password", methods={"GET","POST"})
 */
class EditPasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EditPasswordHandler
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
     * @param EditPasswordHandler $handler
     * @param SessionInterface $session
     * @param UserManager $manager
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EditPasswordHandler $handler,
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
     * @param EditPasswordResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        EditPasswordResponder $responder): Response
    {
        $newChangePassword = new ChangePassword();

        $user=$this->manager
            ->findUserbyUsername($request->attributes->get('username'));

        $form = $this->formFactory
            ->create(EditPasswordType::class, $newChangePassword)
            ->handleRequest($request);

        if ($this->handler->handle($form, $user)) {
            $this->session->getFlashBag()->add("success", "Votre mot de passe a été modifié.");
            return $responder(true, $form, $user);
        }

        return $responder(false, $form);
    }
}