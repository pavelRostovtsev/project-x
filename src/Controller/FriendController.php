<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\User;
use App\Form\FriendAddType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    /**
     * @Route("/friend", name="friend")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        $allUser = $userRepository->allUsers();
        return $this->render('friend/index.html.twig', [
            'allUser' => $allUser,
        ]);
    }

    /**
     * @Route("/followers", name="followers")
     */
    public function followers()
    {

    }
}
