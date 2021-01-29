<?php


namespace App\Repository\GroupsUsersRepository;


use App\Entity\Group;
use App\Entity\GroupsUsers;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;


interface GroupsUsersRepositoryInterface
{
    /**
     * @param Group $group
     * @param GroupsUsers $groupsUsers
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function invite(Group $group ,GroupsUsers $groupsUsers, Request $request,UserRepository $userRepository):void;

    public function exclude():void;

    public function acceptInvitation():void;
}