<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostComment[]    findAll()
 * @method PostComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostComment::class);
    }

    public function findPostComments(Post $post)
    {
        return $this
            ->createQueryBuilder('postComment')
            ->join('postComment.post_id', 'id')
            ->setParameter('id', $post)
            ->getQuery()
            ->getResult()
            ;
    }
}
