<?php

namespace App\Repository\PostRepository;

use App\Entity\Group;
use App\Entity\Post;
use App\Entity\User;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Post::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @param Form $formPost
     * @param User $currentUser
     * @param bool $status
     * @return void
     */
    public function setCreate(Post $post, FileManagerServiceInterface $fileManagerService, Form $formPost, User $currentUser, bool $status):void
    {
        if ($status) {
            $image = $formPost->get('img')->getData();
            if($image) {
                $fileName = $fileManagerService->uploadImage($image);
                $post->setImg($fileName);
            }
            $post->setAuthor($currentUser);
            $this->entityManager->persist($post);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Post $post
     * @param Form $form
     * @param FileManagerServiceInterface $fileManagerService
     */
    public function setSave(Post $post, Form $form, FileManagerServiceInterface $fileManagerService): void
    {
        $image = $form->get('img')->getData();
        $oldImg = $post->getImg();
        if($image) {
            if ($oldImg) {
                $fileManagerService->removeImage($oldImg);
            }
            $fileName = $fileManagerService->uploadImage($image);
            $post->setImg($fileName);
        }
        $this->entityManager->flush();;
    }

    /**
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @return mixed|void
     */
    public function setDelete(Post $post, FileManagerServiceInterface $fileManagerService)
    {
        $img = $post->getImg();
        if($img) {
            $fileManagerService->removeImage($img);
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
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

    /**
     * @param $id
     * @return int|mixed|string
     */
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
