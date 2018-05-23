<?php

namespace App\UI\Responder;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

final class ListCommentResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ViewTrickResponder constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(array $data)
    {
        return new Response($this->twig->render('Comment/list.html.twig',$data));
    }
}