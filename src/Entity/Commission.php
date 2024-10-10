<?php
// src/Entity/Commission.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: "App\Repository\CommissionRepository")]
class Commission
{
    #[Id, GeneratedValue, Column(type: "integer")]
    private $id;

    #[Column(type: "string", length: 255)]
    private $name;

    #[Column(type: "text", nullable: true)]
    private $description;

    #[Column(type: "boolean", options: ["default" => false])]
    private $isClosed = false;

    #[ManyToMany(targetEntity: User::class, mappedBy: 'commissions')]
    private Collection $users;

    #[OneToMany(mappedBy: "commission", targetEntity: "App\Entity\Post")]
    private $posts;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(?bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addCommission($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeCommission($this);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
    
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCommission($this);
        }
    
        return $this;
    }
    
    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCommission() === $this) {
                $post->setCommission(null);
            }
        }
    
        return $this;
    }
}