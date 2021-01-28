<?php

namespace App\EntityListener;

use App\Entity\Group;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class GroupEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Group $user, LifecycleEventArgs $event)
    {
        $user->computeSlug($this->slugger);
    }

    public function preUpdate(Group $user, LifecycleEventArgs $event)
    {
        $user->computeSlug($this->slugger);
    }
}