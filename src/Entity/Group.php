<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $аadmins;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subscribers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $blackList;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getаadmins(): ?string
    {
        return $this->аadmins;
    }

    public function setаadmins(string $аadmins): self
    {
        $this->аadmins = $аadmins;

        return $this;
    }

    public function getSubscribers(): ?string
    {
        return $this->subscribers;
    }

    public function setSubscribers(string $subscribers): self
    {
        $this->subscribers = $subscribers;

        return $this;
    }

    public function getBlackList(): ?string
    {
        return $this->blackList;
    }

    public function setBlackList(?string $blackList): self
    {
        $this->blackList = $blackList;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
