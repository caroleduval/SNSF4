<?php

namespace App\Actions\Trick;

use App\Domain\DTO\NewTrickDTO;
use App\Domain\Model\Trick;
use App\Domain\Repository\TrickManager;
use App\Form\Type\TrickType;
use App\Form\Handler\AddTrickHandler;
use App\Responder\AddTrickResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/ajouter_un_trick", name="trick_add", methods={"GET","POST"})
 */
final class AddTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var AddTrickHandler
     */
    private $handler;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TrickManager
     */
    private $manager;

    /**
     * AddTrickAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param AddTrickHandler $handler
     * @param SessionInterface $session
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        AddTrickHandler $handler,
        SessionInterface $session,
        TrickManager $manager)
    {
        $this->formFactory=$formFactory;
        $this->handler=$handler;
        $this->session=$session;
        $this->manager=$manager;
    }

    /**
     * @param Request $request
     * @param AddTrickResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        AddTrickResponder $responder): Response
    {
        $dto = new NewTrickDTO();

        $form = $this->formFactory
            ->create(TrickType::class, $dto)
            ->handleRequest($request);

        if ($this->handler->handle($form)){
            $this->session->getFlashBag()->add("info", "Le trick a bien été enregistré.");
            $trick=$this->manager->findLastTrick();
            return $responder(true, $form, $trick);
        }

        return $responder(false, $form);
    }
}
