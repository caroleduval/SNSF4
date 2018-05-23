<?php

namespace App\UI\Responder;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class DeleteTrickResponder
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
     * DeleteTrickResponder constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig, UrlGeneratorInterface $router)
    {
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @return RedirectResponse
     */
    public function __invoke()
    {
        return new RedirectResponse($this->router->generate('homepage'));
    }
}