<?php

namespace App\Entity;

use App\Repository\UsercommissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsercommissionRepository::class)]
class Usercommission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?commission $commission = null;

    #[ORM\Column(type: "boolean", options: ["default" => false])]
    private $isFollowed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCommission(): ?commission
    {
        return $this->commission;
    }

    public function setCommission(commission $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getIsFollowed(): ?bool
    {
        return $this->isFollowed;
    }

    public function setIsFollowed(?bool $isFollowed): self
    {
        $this->isClosed = $isFollowed;

        return $this;
    }
}
