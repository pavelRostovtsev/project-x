<?php


namespace App\Repository\GroupRepository;


use App\Entity\Group;
use App\Entity\User;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\Form\Form;

interface GroupRepositoryInterface
{
    /**
     * @param Group $group
     * @param Form $form
     * @param FileManagerServiceInterface $fileManagerService
     * @param User $user
     * @return $this
     */
    public function setCreate(Group $group,
                              Form $form,
                              FileManagerServiceInterface $fileManagerService,
                              User $user):self;

    /**
     * @param Group $group
     * @return $this
     */
    public function setSave(Group $group):self;

    /**
     * @param Group $group
     * @return $this
     */
    public function delete(Group $group):self;

}