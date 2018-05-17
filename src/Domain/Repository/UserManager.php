<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserManager extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     * @param ManagerRegistry $registry
     */
    public function __construct(
        EntityManagerInterface $em,
        ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->em = $em;
    }
}