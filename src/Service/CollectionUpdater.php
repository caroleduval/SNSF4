<?php

namespace App\Service;

use App\Domain\Model\Trick;
use Doctrine\ORM\EntityManagerInterface;

class CollectionUpdater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CollectionUpdater constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Trick $trick
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function initializeListPhotos(Trick $trick)
    {
        return clone $trick->getPhotos();
    }

    /**
     * @param Trick $trick
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function initializeListVideos(Trick $trick)
    {
        return clone $trick->getVideos();
    }

    /**
     * @param Trick $trick
     * @param $listPhotos
     * @param $listVideos
     */
    public function compareCollections(Trick $trick, $listPhotos, $listVideos)
    {
        foreach ($listPhotos as $photo) {
            if (false === $trick->getPhotos()->contains($photo)) {
                $this->em->remove($photo);
            }
        }

        foreach ($listVideos as $video) {
            if (false === $trick->getVideos()->contains($video)) {
                $this->em->remove($video);
            }
        }
    }
}
