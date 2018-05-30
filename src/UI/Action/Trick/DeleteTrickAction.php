<?php

namespace App\UI\Action\Trick;

use App\Domain\Repository\TrickManager;
use App\Service\FileUploader;
use App\UI\Responder\Trick\DeleteTrickResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/supprimer_un_trick/{slug}", name="trick_delete", methods={"GET","POST"})
 */
final class DeleteTrickAction
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TrickManager
     */
    private $manager;

    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * DeleteTrickAction constructor.
     * @param SessionInterface $session
     * @param TrickManager $manager
     * @param FileUploader $uploader
     */
    public function __construct(
        SessionInterface $session,
        TrickManager $manager,
        FileUploader $uploader)
    {
        $this->session =$session;
        $this->manager =$manager;
        $this->uploader=$uploader;
    }

    /**
     * @param Request $request
     * @param DeleteTrickResponder $responder
     * @return Response
     */
    public function __invoke(
        Request $request,
        DeleteTrickResponder $responder): Response
    {
        $trick=$this->manager
            ->findTrickbySlug($request->attributes->get('slug'));


        $this->manager->deleteTrick($trick);

        $this->session->getFlashBag()->add("info", "Le trick a bien été supprimé.");

        return $responder();
    }
}
