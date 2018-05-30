<?php

namespace App\UI\Action\Security;

use App\UI\Responder\Security\ViewProfileResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="view_profile", methods={"GET"})
 */
class ViewProfileAction
{
    /**
     * @param Request $request
     * @param ViewProfileResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        ViewProfileResponder $responder): Response
    {
        return $responder();
    }
}
