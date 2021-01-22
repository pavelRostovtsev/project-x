<?php


namespace App\Repository\PostRepository;


use App\Entity\Post;

interface PostRepositoryInterface
{
    /**
     * @param Post $post
     * @return $this
     */
    public function setCreate(Post $post): self;

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