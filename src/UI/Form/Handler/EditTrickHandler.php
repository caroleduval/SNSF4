<?php

namespace App\UI\Form\Handler;

use App\Domain\Model\Trick;
use App\Domain\Repository\TrickManager;
use App\Service\CollectionUpdater;
use App\Service\FileUploader;
use Symfony\Component\Form\FormInterface;

class EditTrickHandler
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
     * EditTrickHandler constructor.
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
     * @param $listPhotos
     * @param $listVideos
     * @return bool
     */
    public function handle(FormInterface $form, $listPhotos, $listVideos): bool
    {
        if ($form->isSubmitted() && $form->isValid()){
            $trick=$form->getData();
            $this->collectionUpdater->compareCollections($trick, $listPhotos, $listVideos);
            $this->trickManager->editTrick($trick);
            return true;
        } else {
            return false;
        }
    }
}
