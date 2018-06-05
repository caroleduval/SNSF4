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

    /**
     * @param $username
     * @return User
     */
    public function findUserbyUsername($username) : User
    {
        return $this->em->getRepository(User::class)->findOneByUsername($username);
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }


    /**
     *
     */
    public function editUser()
    {
        $this->em->flush();
    }
}