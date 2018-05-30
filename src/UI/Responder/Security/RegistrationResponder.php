<?php

namespace App\UI\Responder\Security;

use App\Domain\Model\User;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormInterface;

final class RegistrationResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * TrickViewResponder constructor.
     * @param Environment $twig
     * @param UrlGeneratorInterface $router
     */
    public function __construct(Environment $twig, UrlGeneratorInterface $router)
    {
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param bool $redirect
     * @param FormInterface|null $form
     * @param User|null $trick
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($redirect=false, FormInterface $form = null, User $trick = null)
    {
        $response = $redirect
            ? new RedirectResponse($this->router->generate('homepage'))
            : new Response($this->twig->render('Security/registration.html.twig',[
                'form' => $form->createView()
            ]));
        return $response;
    }
}