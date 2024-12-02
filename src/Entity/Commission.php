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

    public function __construct()
    {
        $this->usercommissions = new ArrayCollection();
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

}