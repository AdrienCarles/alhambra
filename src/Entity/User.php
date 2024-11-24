<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "json")]
    private array $roles = ['ROLE_USER'];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Usercommission::class)]
    private Collection $usercommissions;

    public function __construct()
    {
        $this->usercommissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $validRoles = ['ROLE_USER', 'ROLE_ADMINISTRATEUR', 'ROLE_BENEVOLE', 'ROLE_PRIVILEGED_USER'];
        $this->roles = array_intersect($roles, $validRoles);
        
        if (!in_array('ROLE_USER', $this->roles)) {
            $this->roles[] = 'ROLE_USER';
        }
    
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
            $usercommission->setUser($this);
        }

        return $this;
    }

    public function removeUsercommission(Usercommission $usercommission): self
    {
        if ($this->usercommissions->removeElement($usercommission)) {
            // set the owning side to null (unless already changed)
            if ($usercommission->getUser() === $this) {
                $usercommission->setUser(null);
            }
        }

        return $this;
    }
    
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}