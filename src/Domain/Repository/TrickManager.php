<?php

namespace App\Domain\Repository;

use App\Domain\Model\Trick;
//use App\Event\ChangeTrickEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TrickManager extends ServiceEntityRepository
{
    private $dispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TrickManager constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param ManagerRegistry $registry
     */
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
        $this->em = $em;
        $this->dispatcher = $dispatcher;
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
//        $this->dispatcher->dispatch(
//            'on.trick.registration',
//            new ChangeTrickEvent($trick)
//        );
    }

    /**
     * @param $trick
     */
    public function deleteTrick(Trick $trick)
    {
        // avt remove
        $this->em->remove($trick);
        $this->em->flush();
        // ap remove
    }

    /**
     *
     */
    public function editTrick(Trick $trick)
    {
        $trick->setLastUpdate(new \DateTime());
        $this->em->flush();
    }
}