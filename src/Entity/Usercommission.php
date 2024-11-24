<?php
namespace App\Entity;

use App\Repository\UsercommissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsercommissionRepository::class)]
#[ORM\UniqueConstraint(columns: ['user_id', 'commission_id'])]
class Usercommission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'usercommissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Commission::class, inversedBy: 'usercommissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commission $commission = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isFollowed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommission(): ?Commission
    {
        return $this->commission;
    }

    public function setCommission(?Commission $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function isFollowed(): bool
    {
        return $this->isFollowed;
    }

    public function setIsFollowed(bool $isFollowed): self
    {
        $this->isFollowed = $isFollowed;

        return $this;
    }
}