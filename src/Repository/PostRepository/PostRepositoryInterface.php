<?php


namespace App\Repository\PostRepository;


use App\Entity\Group;
use App\Entity\Post;
use App\Service\FileManagerServiceInterface;

interface PostRepositoryInterface
{
    /**
     * @param Post $post
     * @param Group $group
     * @param bool $statusAdmin
     * @param FileManagerServiceInterface $fileManagerService
     */
    public function setCreate(Post $post, Group $group, bool $statusAdmin, FileManagerServiceInterface $fileManagerService);

    /**
     * @param Post $post
     * @return $this
     */
    public function setSave(Post $post): self;

    /**
     * @param Post $post
     * @return mixed
     */
    public function setDelete(Post $post);
}