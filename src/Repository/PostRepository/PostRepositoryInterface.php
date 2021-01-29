<?php


namespace App\Repository\PostRepository;


use App\Entity\Group;
use App\Entity\Post;
use App\Entity\User;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\Form\Form;

interface PostRepositoryInterface
{
    /**
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @param Form $formPost
     * @param User $currentUser
     * @param bool $status
     * @return void
     */
    public function setCreate(Post $post,
                              FileManagerServiceInterface $fileManagerService,
                              Form $formPost,
                              User $currentUser,
                              bool $status): void;

    /**
     * @param Post $post
     * @param Form $form
     * @param FileManagerServiceInterface $fileManagerService
     * @return void
     */
    public function setSave(Post $post,
                            Form $form,
                            FileManagerServiceInterface $fileManagerService): void;

    /**
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @return mixed
     */
    public function setDelete(Post $post, FileManagerServiceInterface $fileManagerService);
}