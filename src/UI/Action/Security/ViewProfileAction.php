<?php

namespace App\UI\Action\Security;

use App\UI\Responder\Security\ViewProfileResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/profile", name="view_profile", methods={"GET"})
 */
class ViewProfileAction
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

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
        $user=$this->tokenStorage->getToken()->getUser();

        return $responder([
            'user' => $user
        ]);
    }
}
