<?php

namespace App\Entity;

use App\Repository\FileAttenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileAttenteRepository::class)
 */
class FileAttente
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
    private $type;

    /**
     * @ORM\Column(type="time")
     */
    private $duree;

    /**
     * @ORM\ManyToOne(targetEntity=Boutique::class, inversedBy="fileAttente",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, name="boutique_id", referencedColumnName="id")
     */
    private $boutique;

    /**
     * @ORM\OneToMany(targetEntity=InfoFileAttente::class, mappedBy="fileAttente")
     */
    private $infoFileAttentes;

    public function __construct()
    {
        $this->infoFileAttentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

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
            $infoFileAttente->setFileAttente($this);
        }

        return $this;
    }

    public function removeInfoFileAttente(InfoFileAttente $infoFileAttente): self
    {
        if ($this->infoFileAttentes->contains($infoFileAttente)) {
            $this->infoFileAttentes->removeElement($infoFileAttente);
            // set the owning side to null (unless already changed)
            if ($infoFileAttente->getFileAttente() === $this) {
                $infoFileAttente->setFileAttente(null);
            }
        }

        return $this;
    }
}
