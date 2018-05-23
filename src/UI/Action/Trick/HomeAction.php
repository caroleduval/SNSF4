<?php

namespace App\UI\Action\Trick;

use App\UI\Responder\HomeResponder;
use App\Domain\Repository\TrickManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="homepage", methods={"GET"})
 */
final class HomeAction
{
    /**
     * @param HomeResponder $responder
     * @param TrickManager $manager
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        HomeResponder $responder,
        TrickManager $manager): Response
    {
        $listTricks = $manager->findTricksByOrder();

        return $responder([
            'listTricks' => $listTricks
        ]);
    }
}