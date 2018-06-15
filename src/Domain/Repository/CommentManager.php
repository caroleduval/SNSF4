<?php

namespace App\Domain\Repository;

use App\Domain\Model\Comment;
use App\Domain\Model\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CommentManager extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CommentManager constructor.
     * @param RegistryInterface $registry
     * @param EntityManagerInterface $em
     */
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Comment::class);
        $this->em = $em;
    }

    /**
     * @param Trick $trick
     * @param $page
     * @param $nbPerPage
     * @return Paginator
     */
    public function getComments(Trick $trick, $page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.author', 'u')
            ->addSelect('u')
            ->leftJoin('u.photo', 'p')
            ->addSelect('p')
            ->orderBy('c.dateCrea', 'DESC')
            ->where('c.trick=:trick_id')
            ->setParameter('trick_id',$trick->getId())
            ->getQuery()
        ;

        $query
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbPerPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // on retourne l'objet Paginator correspondant à la requête construite
        return new Paginator($query, true);
    }

    /**
     * @param $comment
     */
    public function addComment($comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }
}
