<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\User;
use App\Form\FriendAddType;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    /**
     * @Route("/users", name="users")
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
     * @param FriendRepository $friendRepository
     * @return Response
     */
    public function followers(FriendRepository $friendRepository): Response
    {
        $currentUser = $this->getUser();
        $followers = $friendRepository->getFollowers($currentUser);
        return $this->render('friend/followers.html.twig', [
            'followers' => $followers,
        ]);
    }

    /**
     * @Route("/friends", name="friends")
     * @param FriendRepository $friendRepository
     * @param User $user
     * @return Response
     */
    public function friends(FriendRepository $friendRepository): Response
    {
        $currentUser = $this->getUser();
        $user = new User();
        $allFriends = $friendRepository->getAllFriends($currentUser);
        return $this->render('friend/friends.html.twig', [
            'allFriends' => $allFriends,
        ]);
    }
}
