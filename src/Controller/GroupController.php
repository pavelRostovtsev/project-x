<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\GroupsUsers;
use App\Entity\Post;
use App\Entity\User;
use App\Form\GroupType;
use App\Form\PostType;
use App\Helpers\Helpers;
use App\Repository\FriendRepository;
use App\Repository\GroupRepository\GroupRepository;
use App\Repository\GroupsUsersRepository\GroupsUsersRepository;
use App\Repository\UserRepository;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/group", name="group_index", methods={"GET"})
     * @param GroupRepository $groupRepository
     * @return Response
     */
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findAll(),
        ]);
    }

    /**
     * @Route("/group/new", name="group_new", methods={"GET","POST"})
     * @param Request $request
     * @param GroupRepository $groupRepository
     * @param FileManagerServiceInterface $fileManagerService
     * @return Response
     */
    public function new(Request $request, GroupRepository $groupRepository, FileManagerServiceInterface $fileManagerService): Response
    {
        $currentUser = $this->getUser();
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupRepository->setCreate($group, $form, $fileManagerService, $currentUser);
            return $this->redirectToRoute('group_index');
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/{slug}", name="group_show", methods={"GET"})
     * @param Group $group
     * @param Request $request
     * @param FileManagerServiceInterface $fileManagerService
     * @return Response
     */
    public function show(Group $group, Request $request, FileManagerServiceInterface $fileManagerService): Response
    {
        $currentUser = $this->getUser();
        $statusAdmin = $group->getCreator()->getSlug() == $currentUser->getSlug();

        return $this->render('group/show.html.twig', [
            'group' => $group,
            'statusAdmin' => $statusAdmin
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="group_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Group $group
     * @param GroupRepository $groupRepository
     * @param FileManagerServiceInterface $fileManagerService
     * @return Response
     */
    public function edit(Request $request, Group $group, GroupRepository $groupRepository, FileManagerServiceInterface $fileManagerService): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupRepository->setSave($group, $form, $fileManagerService);
            return $this->redirectToRoute('group_index');
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_delete", methods={"DELETE"})
     * @param Request $request
     * @param Group $group
     * @param GroupRepository $groupRepository
     * @return Response
     */
    public function delete(Request $request, Group $group, GroupRepository $groupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            $groupRepository->delete($group);
        }

        return $this->redirectToRoute('group_index');
    }

    /**
     * @Route("/group/group-management/{slug}", name="group_management", methods={"GET"})
     * @param Group $group
     * @param Request $request
     * @param FriendRepository $friendRepository
     * @return Response
     */
    public function groupManagement(Group $group, Request $request, FriendRepository $friendRepository): Response
    {
        $currentUser = $this->getUser();
        $allFriends = $friendRepository->getAllFriends($currentUser);
        return $this->render('group/management.html.twig',[
            'group' => $group,
            'allFriends' => $allFriends
        ]);
    }

    /**
     * @Route("/group/invite/{slug}", name="group_invite", methods={"POST"})
     * @param Request $request
     * @param Group $group
     * @param GroupsUsersRepository $groupsUsersRepository
     * @param UserRepository $userRepository
     * @param GroupsUsers $groupsUsers
     * @return Response
     */
    public function invite(Request $request,
                           Group $group,
                           GroupsUsersRepository $groupsUsersRepository,
                           UserRepository $userRepository): Response
    {
        $groupsUsers = new GroupsUsers();
        if ($this->isCsrfTokenValid('invite'.$group->getSlug(), $request->request->get('_token'))) {
            $groupsUsersRepository->invite($group,$groupsUsers ,$request, $userRepository);
        }

        return $this->redirectToRoute('group_management', ['slug' => $group->getSlug()]);
    }
}
