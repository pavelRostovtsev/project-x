<?php

namespace App\Controller;

use App\Entity\User;
use App\Helpers\Helpers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    /**
     * @Route("/page/{slug}", name="user_page")
     */
    public function index(User $user, Request $request): Response
    {
        $currentUser = $this->getUser();
        $status = Helpers::getCurrentSlugPersonalArea($request->getRequestUri()) == $this->getUser()->getSlug();

        return $this->render('user_page/index.html.twig', [
            'page_owner' => $user,
            'status' => $status
        ]);
    }
}
