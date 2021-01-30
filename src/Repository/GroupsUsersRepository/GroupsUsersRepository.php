<?php

namespace App\Repository\GroupsUsersRepository;

use App\Entity\Group;
use App\Entity\GroupsUsers;
use App\Entity\User;
use App\Repository\GroupRepository\GroupRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GroupsUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupsUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupsUsers[]    findAll()
 * @method GroupsUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupsUsersRepository extends ServiceEntityRepository implements GroupsUsersRepositoryInterface
{

    public $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, GroupsUsers::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Group $group
     * @param GroupsUsers $groupsUsers
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function invite(Group $group ,GroupsUsers $groupsUsers, Request $request, UserRepository $userRepository): void
    {
        $slugUser = $request->request->get('user');
        $user = $userRepository->findBySlug($slugUser)[0];
        $groupVerification = $this->groupVerification($group, $groupsUsers, $slugUser, $user);

        if ($groupVerification == null){
            $groupsUsers->setUser($user);
            $groupsUsers->setPublic($group);
            $groupsUsers->setStatus(false);
            $this->entityManager->persist($groupsUsers);
            $this->entityManager->flush();
        }
    }

    public function exclude(): void
    {

    }

    public function acceptInvitation(): void
    {
        // TODO: Implement acceptInvitation() method.
    }

    /**
     * @param Group $group
     * @param GroupsUsers $groupsUsers
     * @param string $slugUser
     * @param User $user
     * @return self
     */
    public function groupVerification(Group $group ,GroupsUsers $groupsUsers, string $slugUser, User $user)
    {
        $groupVerification = $this
            ->createQueryBuilder('groups_users')
            ->where('groups_users.user = :user')
            ->andWhere('groups_users.public = :group')
            ->setParameter('user', $user)
            ->setParameter('group', $group)
            ->getQuery()
            ->getResult();

        if ($groupVerification) {
            return (object)$groupVerification[0];
        }
        return $groupVerification;

    }
}
