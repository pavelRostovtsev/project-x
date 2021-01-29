<?php

namespace App\Entity;

use App\Repository\GroupsUsersRepository\GroupsUsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupsUsersRepository::class)
 */
class GroupsUsers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="myGroup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="public")
     * @ORM\JoinColumn(nullable=false)
     */
    private $public;

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="myGroup")
     * @ORM\JoinColumn(nullable=false)
     */

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPublic(): ?Group
    {
        return $this->public;
    }

    public function setPublic(?Group $public): self
    {
        $this->public = $public;

        return $this;
    }

}
