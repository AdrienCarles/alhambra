<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CommissionRepository")]
class Commission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\Column(type: "text", nullable: true)]
    private $description;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private $isClosed = false;

    #[ORM\OneToMany(mappedBy: 'commission', targetEntity: Usercommission::class)]
    private Collection $usercommissions;

    #[ORM\OneToMany(mappedBy: 'commission', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->usercommissions = new ArrayCollection();
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

    public function getIsClosed(): bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    /**
     * @return Collection|Usercommission[]
     */
    public function getUsercommissions(): Collection
    {
        return $this->usercommissions;
    }

    public function addUsercommission(Usercommission $usercommission): self
    {
        if (!$this->usercommissions->contains($usercommission)) {
            $this->usercommissions[] = $usercommission;
            $usercommission->setCommission($this);
        }

        return $this;
    }

    public function removeUsercommission(Usercommission $usercommission): self
    {
        if ($this->usercommissions->removeElement($usercommission)) {
            // set the owning side to null (unless already changed)
            if ($usercommission->getCommission() === $this) {
                $usercommission->setCommission(null);
            }
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