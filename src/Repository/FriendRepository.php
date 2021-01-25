<?php

namespace App\Repository;

use App\Entity\Friend;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendRepository extends ServiceEntityRepository
{
    public const ANON = 'anon';
    public const FOLLOWER = 'follower';
    public const FRIEND = 'friend';
    private $entityManager;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Friend::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return string
     */
    public function getStatus(User $user1, User $user2): string
    {
        $query =  $this
            ->createQueryBuilder('friend')
            ->where('friend.user = :user')
            ->andWhere('friend.user2 = :user2')
            ->setParameter('user', $user1)
            ->setParameter('user2', $user2)
            ->getQuery()
            ->getResult();
        if($query) {
            if ($query[0]->getStatus() === self::FRIEND) {
                return self::FRIEND;
            } else {
                return self::FOLLOWER;
            }
        } else {
            return self::ANON;
        }
    }

    public function addFriend(User $user1, User $user2, Friend $friend)
    {
        $friend->setUser($user1);
        $friend->setUser2($user2);
        $friend->setStatus(false);

        $this->entityManager->persist($friend);
        $this->entityManager->flush();
    }

    /**
     * @param User $user1
     * @param User $user2
     */
    public function deleteFriend(User $user1, User $user2)
    {
        $query =  $this
            ->createQueryBuilder('friend')
            ->where('friend.user = :user')
            ->andWhere('friend.user2 = :user2')
            ->setParameter('user', $user1)
            ->setParameter('user2', $user2)
            ->getQuery()
            ->getResult();
        $this->entityManager->remove((object)$query[0]);
        $this->entityManager->flush();
    }
}
