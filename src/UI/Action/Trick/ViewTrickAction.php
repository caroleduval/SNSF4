<?php

namespace App\UI\Action\Trick;

use App\Domain\Repository\TrickManager;
use App\Service\BiblioMessager;
use App\UI\Responder\ViewTrickResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick/{slug}/{page}", name="trick_view",requirements={"page":"\d+"}, methods={"GET"})
 */
final class ViewTrickAction
{
    private $biblioMessager;

    private $manager;

    public function __construct(
        BiblioMessager $biblioMessager,
        TrickManager $manager)
    {
        $this->biblioMessager=$biblioMessager;
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @param ViewTrickResponder $responder
     * @param int $page
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        ViewTrickResponder $responder,
        $page=1): Response
    {
        $trick=$this->manager
            ->findTrickbySlug($request->attributes->get('slug'));

        $nbitems=$trick->countItems();
        $message=$this->biblioMessager->messageCreator($trick);

        return $responder([
            'trick' => $trick,
            'page'=>$page,
            'count'=> $nbitems,
            'message'=>$message
        ]);
    }
}
