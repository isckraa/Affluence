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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Latitude;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxClient;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $maskRequired;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gel;

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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(?float $Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->Latitude;
    }

    public function setLatitude(?float $Latitude): self
    {
        $this->Latitude = $Latitude;

        return $this;
    }

    public function getMaxClient(): ?int
    {
        return $this->maxClient;
    }

    public function setMaxClient(?int $maxClient): self
    {
        $this->maxClient = $maxClient;

        return $this;
    }

    public function getMaskRequired(): ?bool
    {
        return $this->maskRequired;
    }

    public function setMaskRequired(?bool $maskRequired): self
    {
        $this->maskRequired = $maskRequired;

        return $this;
    }

    public function getGel(): ?bool
    {
        return $this->gel;
    }

    public function setGel(?bool $gel): self
    {
        $this->gel = $gel;

        return $this;
    }
}
