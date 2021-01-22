<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository\PostRepository;
use App\Service\FileManagerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    /**
     * @Route("/post/{id}", name="post_show", methods={"GET"})
     * @param Post $post
     * @param PostRepository $postsRepository
     * @return Response
     */
    public function show(Post $post, PostRepository $postsRepository): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
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
