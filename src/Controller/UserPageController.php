<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Helpers\Helpers;
use App\Repository\LikesRepository;
use App\Repository\PostRepository\PostRepository;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    /**
     * @Route("/page/{slug}", name="user_page")
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param FileManagerServiceInterface $fileManagerService
     * @param PostRepository $postRepository
     * @param LikesRepository $likesRepository
     * @return Response
     */
    public function index(User $user,
                          Request $request,
                          EntityManagerInterface $entityManager,
                          FileManagerServiceInterface $fileManagerService,
                          PostRepository $postRepository,
                          LikesRepository $likesRepository): Response
    {
        $currentUser = $this->getUser();
        $status = Helpers::getCurrentSlugPersonalArea($request->getRequestUri()) == $this->getUser()->getSlug();

        $post = new Post();
        $formPost = $this->createForm(PostType::class, $post);
        $formPost->handleRequest($request);

        if ($formPost->isSubmitted() && $formPost->isValid()) {
            if ($status) {
                $image = $formPost->get('img')->getData();
                if($image) {
                    $fileName = $fileManagerService->uploadImage($image);
                    $post->setImg($fileName);
                }
                $post->setAuthor($currentUser);
                $entityManager->persist($post);
                $entityManager->flush();
                return $this->redirectToRoute('user_page', ['slug' => $user->getSlug()]);
            }else {
                throw new HttpException(400, 'Ты чет хитришь');
            }
        }

        $numberLikes = $likesRepository->numberLikes();
        return $this->render('user_page/index.html.twig', [
            'page_owner' => $user,
            'status' => $status,
            'form_post' => $formPost->createView(),
            'posts' => $postRepository->findUserPosts($user),
            'numberLikes' => $numberLikes
        ]);
    }
}
