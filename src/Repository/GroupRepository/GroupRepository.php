<?php

namespace App\Repository\GroupRepository;

use App\Entity\Group;
use App\Entity\User;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository implements GroupRepositoryInterface
{
    public $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Group::class);
        $this->entityManager =$entityManager;
    }

    public function setCreate(Group $group,
                              Form  $form,
                              FileManagerServiceInterface $fileManagerService,
                              User $user): GroupRepositoryInterface
    {
        $image = $form->get('img')->getData();
        if($image) {
            $fileName = $fileManagerService->uploadImage($image);
            $group->setImg($fileName);
        }
        $group->setCreator($user);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $this;
    }

    public function setSave(Group $group): GroupRepositoryInterface
    {

    }

    public function delete(Group $group): GroupRepositoryInterface
    {

    }
}
