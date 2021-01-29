<?php

namespace App\Repository;

use App\Entity\Likes;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Likes::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function repeatLike(User $user, Post $post): bool
    {
        $query =  $this
            ->createQueryBuilder('likes')
            ->where('likes.user = :user')
            ->andWhere('likes.post = :post')
            ->setParameter('user', $user)
            ->setParameter('post', $post)
            ->getQuery()
            ->getResult();
        $count = count($query);
        if ($count >= 1) {
            return false;
        }
        return  true;
    }

    /**
     * @return int
     */
    public function numberLikes(): int
    {
        $query =  $this
            ->createQueryBuilder('likes')
            ->getQuery()
            ->getResult();

        return count($query);

    }


    /**
     * @param User $user
     * @param Post $post
     * @return int|mixed|string
     */
    public function getLike(User $user, Post $post)
    {
        return  $this
            ->createQueryBuilder('likes')
            ->where('likes.user = :user')
            ->andWhere('likes.post = :post')
            ->setParameter('user', $user)
            ->setParameter('post', $post)
            ->getQuery()
            ->getResult();

    }

    public function setDelete(Likes $likes)
    {
        $this->entityManager->remove($likes);
        $this->entityManager->flush();
    }
}
