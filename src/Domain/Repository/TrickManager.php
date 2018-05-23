<?php

namespace App\Domain\Repository;

use App\Domain\Model\Trick;
use App\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TrickManager extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * TrickManager constructor.
     * @param EntityManagerInterface $em
     * @param ManagerRegistry $registry
     * @param FileUploader $uploader
     */
    public function __construct(
        EntityManagerInterface $em,
        ManagerRegistry $registry,
        FileUploader $uploader)
    {
        parent::__construct($registry, Trick::class);
        $this->em = $em;
        $this->uploader=$uploader;
    }

    /**
     * @return array
     */
    public function findTricksByOrder()
    {
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
        ;

        return $query->execute();
    }

    /**
     * @return null|object
     */
    public function findLastTrick()
    {
        $qb=$this->findOneBy([],['createdAt' => 'desc']);
        return $qb;
    }

    /**
     * @param $slug
     * @return Trick
     */
    public function findTrickbySlug($slug) : Trick
    {
        return $this->em->getRepository(Trick::class)->findOneBySlug($slug);
    }

    /**
     * @param $trick
     */
    public function addTrick(Trick $trick)
    {
        $trick->setLastUpdate(new \DateTime());
        $this->em->persist($trick);
        $this->em->flush();
    }

    /**
     * @param $trick
     */
    public function deleteTrick(Trick $trick)
    {
        $this->em->remove($trick);
        $this->em->flush();
    }

    /**
     * @param Trick $trick
     */
    public function editTrick(Trick $trick)
    {
        $trick->setLastUpdate(new \DateTime());
        $this->em->flush();
    }
}