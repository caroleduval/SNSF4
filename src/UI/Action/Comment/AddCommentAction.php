<?php

namespace App\UI\Action\Comment;

use App\Domain\Model\Comment;
use App\Domain\Repository\CommentManager;
use App\Domain\Repository\TrickManager;
use App\UI\Form\Handler\AddCommentHandler;
use App\UI\Form\Type\CommentType;
use App\UI\Responder\Comment\AddCommentResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/trick/{slug}/comments/add", name="comment_add", methods={"GET","POST"})
 * @Security("has_role('ROLE_USER')")
 */
final class AddCommentAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var AddCommentHandler
     */
    private $handler;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CommentManager
     */
    private $manager;

    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * AddCommentAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param AddCommentHandler $handler
     * @param SessionInterface $session
     * @param CommentManager $manager
     * @param TrickManager $trickManager
     * @param TokenStorageInterface $tokenStorage
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        AddCommentHandler $handler,
        SessionInterface $session,
        CommentManager $manager,
        TrickManager $trickManager,
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->handler = $handler;
        $this->session = $session;
        $this->manager = $manager;
        $this->trickManager = $trickManager;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param AddCommentResponder $responder
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        AddCommentResponder $responder): Response
    {
        $user = $this->tokenStorage
            ->getToken()->getUser();
        $trick = $this->trickManager
            ->findTrickbySlug($request->attributes->get('slug'));

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setTrick($trick);

        $form = $this->formFactory
            ->create(CommentType::class, $comment)
            ->handleRequest($request);

        if ($this->handler->handle($form)){
            $this->session->getFlashBag()->add("info", "Le commentaire a bien été enregistré.");
            return $responder(true, $form, $trick, $comment);
        }

        return $responder(false, $form, $trick, $comment);
    }
}


//if ($form->isSubmitted() && $form->isValid()) {
//    $this->manager->addComment($comment);
//
//    // Remise du formulaire à zéro avant de renvoyer la page mise à jour
//    unset($comment);
//    unset($form);
//    $comment = new Comment();
//    $comment->setTrick($trick);
//    $form = $this->formFactory
//        ->create(CommentType::class, $comment);
//
//    return new RedirectResponse($this->router->generate('trick_view', array('slug' => $trick->getSlug())));
//}
//return new RedirectResponse($this->router->generate('trick_view', ['slug'=>$trick->getSlug()]));