<?php

namespace App\Entity;

use App\Helpers\Helpers;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->post = new ArrayCollection();
        $this->postComment = new ArrayCollection();
        $this->likePost = new ArrayCollection();
        $this->userOne = new ArrayCollection();
        $this->userTwo = new ArrayCollection();
        $this->createdByMeGroup = new ArrayCollection();
        $this->myGroup = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $city;

    /**
     * Date of Birth
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $DOB;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author", orphanRemoval=true)
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity=PostComment::class, mappedBy="author")
     */
    private $postComment;

    /**
     * @ORM\OneToMany(targetEntity=Likes::class, mappedBy="user")
     */
    private $likePost;

    /**
     * @ORM\OneToMany(targetEntity=Friend::class, mappedBy="user")
     */
    private $userOne;

    /**
     * @ORM\OneToMany(targetEntity=Friend::class, mappedBy="user2")
     */
    private $userTwo;

    /**
     * @ORM\OneToMany(targetEntity=Group::class, mappedBy="creator", orphanRemoval=true)
     */
    private $createdByMeGroup;

    /**
     * @ORM\OneToMany(targetEntity=GroupsUsers::class, mappedBy="user", orphanRemoval=true)
     */
    private $myGroup;

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return $this
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return $this
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDOB(): ?string
    {
        return $this->DOB;
    }

    /**
     * @param string $DOB
     * @return $this
     */
    public function setDOB(string $DOB): self
    {
        $this->DOB = $DOB;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = Helpers::translit($this->userName).'-'. Helpers::translit($this->surname).'-'.uniqid();
        }
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): self
    {
        if (!$this->post->contains($post)) {
            $this->post[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostComment[]
     */
    public function getPostComment(): Collection
    {
        return $this->postComment;
    }

    public function addPostComment(PostComment $postComment): self
    {
        if (!$this->postComment->contains($postComment)) {
            $this->postComment[] = $postComment;
            $postComment->setAuthor($this);
        }

        return $this;
    }

    public function removePostComment(PostComment $postComment): self
    {
        if ($this->postComment->removeElement($postComment)) {
            // set the owning side to null (unless already changed)
            if ($postComment->getAuthor() === $this) {
                $postComment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Likes[]
     */
    public function getLikePost(): Collection
    {
        return $this->likePost;
    }

    public function addLikePost(Likes $likePost): self
    {
        if (!$this->likePost->contains($likePost)) {
            $this->likePost[] = $likePost;
            $likePost->setUser($this);
        }

        return $this;
    }

    public function removeLikePost(Likes $likePost): self
    {
        if ($this->likePost->removeElement($likePost)) {
            // set the owning side to null (unless already changed)
            if ($likePost->getUser() === $this) {
                $likePost->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friend[]
     */
    public function getUserOne(): Collection
    {
        return $this->userOne;
    }

    public function addUserOne(Friend $userOne): self
    {
        if (!$this->userOne->contains($userOne)) {
            $this->userOne[] = $userOne;
            $userOne->setUser($this);
        }

        return $this;
    }

    public function removeUserOne(Friend $userOne): self
    {
        if ($this->userOne->removeElement($userOne)) {
            // set the owning side to null (unless already changed)
            if ($userOne->getUser() === $this) {
                $userOne->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friend[]
     */
    public function getUserTwo(): Collection
    {
        return $this->userTwo;
    }

    public function addUserTwo(Friend $userTwo): self
    {
        if (!$this->userTwo->contains($userTwo)) {
            $this->userTwo[] = $userTwo;
            $userTwo->setUser2($this);
        }

        return $this;
    }

    public function removeUserTwo(Friend $userTwo): self
    {
        if ($this->userTwo->removeElement($userTwo)) {
            // set the owning side to null (unless already changed)
            if ($userTwo->getUser2() === $this) {
                $userTwo->setUser2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getCreatedByMeGroup(): Collection
    {
        return $this->createdByMeGroup;
    }

    public function addCreatedByMeGroup(Group $myGroup): self
    {
        if (!$this->createdByMeGroup->contains($myGroup)) {
            $this->createdByMeGroup[] = $myGroup;
            $myGroup->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedByMeGroup(Group $createdByMeGroup): self
    {
        if ($this->createdByMeGroup->removeElement($createdByMeGroup)) {
            // set the owning side to null (unless already changed)
            if ($createdByMeGroup->getCreator() === $this) {
                $createdByMeGroup->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupsUsers[]
     */
    public function getMyGroup(): Collection
    {
        return $this->myGroup;
    }

    public function addMyGroup(GroupsUsers $myGroup): self
    {
        if (!$this->myGroup->contains($myGroup)) {
            $this->myGroup[] = $myGroup;
            $myGroup->setUser($this);
        }

        return $this;
    }

    public function removeMyGroup(GroupsUsers $myGroup): self
    {
        if ($this->myGroup->removeElement($myGroup)) {
            // set the owning side to null (unless already changed)
            if ($myGroup->getUser() === $this) {
                $myGroup->setUser(null);
            }
        }

        return $this;
    }
}
