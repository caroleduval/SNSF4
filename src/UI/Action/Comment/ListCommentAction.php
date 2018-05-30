<?php

namespace App\UI\Action\Comment;

use App\UI\Responder\Comment\ListCommentResponder;
use App\Domain\Repository\TrickManager;
use App\Domain\Repository\CommentManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/trick/{slug}/comments/{page}", name="comments_list", requirements={"page" = "\d+"}, methods={"GET"})
 */
final class ListCommentAction
{
    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * ListCommentAction constructor.
     * @param CommentManager $commentManager
     * @param TrickManager $trickManager
     */
    public function __construct(CommentManager $commentManager, TrickManager $trickManager)
    {
        $this->commentManager=$commentManager;
        $this->trickManager=$trickManager;
    }

    /**
     * @param Request $request
     * @param ListCommentResponder $responder
     * @param int $page
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        ListCommentResponder $responder,
        $page=1): Response
    {
        $trick=$this->trickManager
            ->findTrickbySlug($request->attributes->get('slug'));

        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        $nbPerPage = 10;

        $listComments = $this->commentManager
            ->getComments($trick, $page, $nbPerPage);

        if (count($listComments)==0) {
            $nbPages =1;
        }
        else {
            $nbPages = ceil(count($listComments) / $nbPerPage);
        }
        if ($page > $nbPages) {
            throw new NotFoundHttpException("La page ".$page." n'existe pas.");
        }

        return $responder([
            'trick' => $trick,
            'listComments'=>$listComments,
            'nbPages'=> $nbPages,
            'page'=>$page
        ]);
    }
}
