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


    public function setCreate(Post $post, Group $group, bool $statusAdmin, FileManagerServiceInterface $fileManagerService, Form $formPost)
    {
        if ($statusAdmin) {
            $image = $formPost->get('img')->getData();
            if($image) {
                $fileName = $fileManagerService->uploadImage($image);
                $post->setImg($fileName);
            }
            $post->setAuthor($currentUser);
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('group_show', ['slug' => $group->getSlug()]);
        }else {
            throw new HttpException(400, 'Ты чет хитришь');
        }
    }

    public function setSave(Post $post): PostRepositoryInterface
    {
        // TODO: Implement setSave() method.
    }

    public function setDelete(Post $post)
    {
        // TODO: Implement setDelete() method.
    }

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
