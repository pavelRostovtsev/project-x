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
     * @return void
     */
    public function setCreate(Group $group,
                              Form $form,
                              FileManagerServiceInterface $fileManagerService,
                              User $user): void;

    /**
     * @param Group $group
     * @param Form $form
     * @param FileManagerServiceInterface $fileManagerService
     * @return void
     */
    public function setSave(Group $group,
                            Form $form,
                            FileManagerServiceInterface $fileManagerService): void;

    /**
     * @param Group $group
     * @return void
     */
    public function delete(Group $group): void;

}