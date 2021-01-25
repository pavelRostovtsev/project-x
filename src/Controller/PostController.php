<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\Post;
use App\Entity\PostComment;
use App\Form\PostCommentType;
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
        return $this->redirectToRoute('news');
    }


    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @param FileManagerServiceInterface $fileManagerService
     * @return Response
     */
    public function edit(Request $request, Post $post,FileManagerServiceInterface $fileManagerService): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('img')->getData();
            $oldImg = $post->getImg();
            if($image) {
                if ($oldImg) {
                    $fileManagerService->removeImage($oldImg);
                }
                $fileName = $fileManagerService->uploadImage($image);
                $post->setImg($fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news');
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
     * @return Response
     */
    public function delete(Request $request, Post $post, FileManagerServiceInterface $fileManagerService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $img = $post->getImg();
            if($img) {
                $fileManagerService->removeImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('news');
    }


}
