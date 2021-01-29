<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Form\PostType;
use App\Helpers\Helpers;
use App\Repository\LikesRepository;
use App\Repository\PostRepository\PostRepository;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PostController extends AbstractController
{
    /**
     * @Route("/comment/post/{id}", name="post_commit", methods={"POST"})
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     */
    public function postCommit(Post $post,FileManagerServiceInterface $fileManagerService, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $comment = new PostComment();
        $currentUser = $this->getUser();
        if ($this->isCsrfTokenValid('post_commit'.$post->getId(), $request->request->get('_token'))) {
            $comment->setText($request->request->get('text'));
            $comment->setPost($post);
            $comment->setAuthor($currentUser);
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('news');
    }

    /**
     * @Route("/like/post/{id}", name="post_like", methods={"POST"})
     * @param Post $post
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param LikesRepository $likesRepository
     * @return RedirectResponse
     */
    public function postLike(Post $post, Request $request, EntityManagerInterface $entityManager, LikesRepository $likesRepository): RedirectResponse
    {
        $like = new Likes();
        $currentUser = $this->getUser();
        if ($this->isCsrfTokenValid('post_like'.$post->getId(), $request->request->get('_token'))) {
            $repeatLike = $likesRepository->repeatLike($currentUser, $post);
            if ($repeatLike) {
                $like->setPost($post);
                $like->setUser($currentUser);
                $entityManager->persist($like);
                $entityManager->flush();
            }else {
                $currentLike = (object)($likesRepository->getLike($currentUser, $post)[0]);
                $likesRepository->setDelete($currentLike);
            }
        }
        return $this->redirectToRoute('user_page', ['slug' => $currentUser->getSlug()]);
    }


    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @param PostRepository $postRepository
     * @return Response
     */
    public function edit(Request $request, Post $post,FileManagerServiceInterface $fileManagerService, PostRepository $postRepository): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->setSave($post, $form, $fileManagerService);
            return $this->redirectToRoute('user_page', ['slug' => $currentUser->getSlug()]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     * @param Request $request
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @param PostRepository $postRepository
     * @return Response
     */
    public function delete(Request $request, Post $post, FileManagerServiceInterface $fileManagerService, PostRepository $postRepository): Response
    {
        $currentUser = $this->getUser();

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->setDelete($post, $fileManagerService);
        }

        return $this->redirectToRoute('user_page', ['slug' => $currentUser->getSlug()]);
    }
}
