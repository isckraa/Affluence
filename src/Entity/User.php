<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="There is already an account with this pseudo")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $pseudo;

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
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity=InfoFileAttente::class, mappedBy="user")
     */
    private $infoFileAttentes;

    /**
     * @ORM\OneToOne(targetEntity=Boutique::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $boutique;

    /**
     * @ORM\OneToMany(targetEntity=Recompense::class, mappedBy="user")
     */
    private $recompenses;

    public function __construct()
    {
        $this->infoFileAttentes = new ArrayCollection();
        $this->recompenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection|InfoFileAttente[]
     */
    public function getInfoFileAttentes(): Collection
    {
        return $this->infoFileAttentes;
    }

    public function addInfoFileAttente(InfoFileAttente $infoFileAttente): self
    {
        if (!$this->infoFileAttentes->contains($infoFileAttente)) {
            $this->infoFileAttentes[] = $infoFileAttente;
            $infoFileAttente->setUser($this);
        }

        return $this;
    }

    public function removeInfoFileAttente(InfoFileAttente $infoFileAttente): self
    {
        if ($this->infoFileAttentes->contains($infoFileAttente)) {
            $this->infoFileAttentes->removeElement($infoFileAttente);
            // set the owning side to null (unless already changed)
            if ($infoFileAttente->getUser() === $this) {
                $infoFileAttente->setUser(null);
            }
        }

        return $this;
    }

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $boutique ? null : $this;
        if ($boutique->getUser() !== $newUser) {
            $boutique->setUser($newUser);
        }

        return $this;
    }

    /**
     * @return Collection|Recompense[]
     */
    public function getRecompenses(): Collection
    {
        return $this->recompenses;
    }

    public function addRecompense(Recompense $recompense): self
    {
        if (!$this->recompenses->contains($recompense)) {
            $this->recompenses[] = $recompense;
            $recompense->setUser($this);
        }

        return $this;
    }

    public function removeRecompense(Recompense $recompense): self
    {
        if ($this->recompenses->contains($recompense)) {
            $this->recompenses->removeElement($recompense);
            // set the owning side to null (unless already changed)
            if ($recompense->getUser() === $this) {
                $recompense->setUser(null);
            }
        }

        return $this;
    }
}
