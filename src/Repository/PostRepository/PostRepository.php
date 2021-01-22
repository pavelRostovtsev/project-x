<?php

namespace App\Repository\PostRepository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }


    public function setCreate(Post $post): PostRepositoryInterface
    {
        // TODO: Implement setCreate() method.
    }

    public function setSave(Post $post): PostRepositoryInterface
    {
        // TODO: Implement setSave() method.
    }

    public function setDelete(Post $post)
    {
        // TODO: Implement setDelete() method.
    }

    public function findUserPosts(User $user)
    {
        return $this
            ->createQueryBuilder('post')
            ->join('post.author', 'user')
            ->andWhere('post.author = :id')
            ->setParameter('id', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPostComments($id)
    {
        return $this
            ->createQueryBuilder('post')
            ->andWhere('post.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }
}
