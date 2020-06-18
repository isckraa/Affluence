<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BoutiqueRepository::class)
 */
class Boutique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=FileAttente::class, mappedBy="boutique")
     */
    private $fileAttente;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="boutique", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->fileAttente = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|FileAttente[]
     */
    public function getFileAttente(): Collection
    {
        return $this->fileAttente;
    }

    public function addFileAttente(FileAttente $fileAttente): self
    {
        if (!$this->fileAttente->contains($fileAttente)) {
            $this->fileAttente[] = $fileAttente;
            $fileAttente->setBoutique($this);
        }

        return $this;
    }

    public function removeFileAttente(FileAttente $fileAttente): self
    {
        if ($this->fileAttente->contains($fileAttente)) {
            $this->fileAttente->removeElement($fileAttente);
            // set the owning side to null (unless already changed)
            if ($fileAttente->getBoutique() === $this) {
                $fileAttente->setBoutique(null);
            }
        }

        return $this;
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
}
