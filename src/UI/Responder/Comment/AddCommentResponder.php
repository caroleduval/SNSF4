<?php

namespace App\UI\Responder\Comment;

use App\Domain\Model\Comment;
use App\Domain\Model\Trick;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormInterface;

final class AddCommentResponder
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
     * @param Trick|null $trick
     * @param Comment|null $comment
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($redirect=false, FormInterface $form = null, Trick $trick = null, Comment $comment=null)
    {
        $response = $redirect
            ? new RedirectResponse($this->router->generate('trick_view', ['slug'=>$trick->getSlug()]))
            : new Response($this->twig->render('Comment/add.html.twig',[
                'form' => $form->createView(),
                'trick'=> $trick
            ]));
        return $response;
    }
}