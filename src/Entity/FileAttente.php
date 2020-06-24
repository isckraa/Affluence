<?php

namespace App\Entity;

use App\Repository\FileAttenteRepository;
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
     * @ORM\ManyToOne(targetEntity=InfoFileAttente::class, inversedBy="fileAttentes")
     */
    private $infoFileAttente;

    /**
     * @ORM\ManyToOne(targetEntity=Boutique::class, inversedBy="fileAttente",cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, name="boutique_id", referencedColumnName="id")
     */
    private $boutique;

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

    public function getInfoFileAttente(): ?InfoFileAttente
    {
        return $this->infoFileAttente;
    }

    public function setInfoFileAttente(?InfoFileAttente $infoFileAttente): self
    {
        $this->infoFileAttente = $infoFileAttente;

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
}
