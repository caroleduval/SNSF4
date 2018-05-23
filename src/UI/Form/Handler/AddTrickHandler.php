<?php

namespace App\UI\Form\Handler;

use App\Domain\Model\Trick;
use App\Service\CollectionUpdater;
use App\Service\FileUploader;
use App\Domain\Repository\TrickManager;
use Symfony\Component\Form\FormInterface;

class AddTrickHandler
{
    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * @var CollectionUpdater
     */
    private $collectionUpdater;

    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * AddTrickHandler constructor.
     * @param TrickManager $trickManager
     * @param CollectionUpdater $collectionUpdater
     * @param FileUploader $uploader
     */
    public function __construct(
        TrickManager $trickManager,
        CollectionUpdater $collectionUpdater,
        FileUploader $uploader
        )
    {
        $this->trickManager=$trickManager;
        $this->collectionUpdater=$collectionUpdater;
        $this->uploader=$uploader;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $trick=$form->getData();
            $this->trickManager->addTrick($trick);
            return true;
        } else {
            return false;
        }
    }
}
