<?php

namespace App\UI\Form\Handler;

use App\Domain\Repository\CommentManager;
use App\Service\CollectionUpdater;
use Symfony\Component\Form\FormInterface;

class AddCommentHandler
{
    /**
     * @var CommentManager
     */
    private $manager;

    /**
     * @var CollectionUpdater
     */
    private $collectionUpdater;

    /**
     * AddCommentHandler constructor.
     * @param CommentManager $manager
     * @param CollectionUpdater $collectionUpdater
     */
    public function __construct(
        CommentManager $manager,
        CollectionUpdater $collectionUpdater)
    {
        $this->manager=$manager;
        $this->collectionUpdater=$collectionUpdater;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $comment=$form->getData();
            $this->manager->addComment($comment);

            // Remise du formulaire à zéro avant de renvoyer la page mise à jour
            unset($comment);
            unset($form);

            return true;
        } else {
            return false;
        }
    }
}
