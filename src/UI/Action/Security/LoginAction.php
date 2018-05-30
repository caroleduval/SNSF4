<?php

namespace App\UI\Action\Security;

use App\UI\Responder\Security\LoginResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route("/Login", name="Login", methods={"GET","POST"})
 */
class LoginAction
{
    /**
     * @var AuthenticationUtils
     */
    private $helper;

    /**
     * LoginAction constructor.
     * @param AuthenticationUtils $helper
     */
    public function __construct(AuthenticationUtils $helper)
    {
        $this->helper=$helper;
    }

    /**
     * @param LoginResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        LoginResponder $responder): Response
    {
        return $responder([
            'last_username' => $this->helper->getLastUsername(),
            'error' => $this->helper->getLastAuthenticationError()
        ]);
    }
}
