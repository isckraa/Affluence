<?php

namespace App\Entity;

use App\Repository\InfoFileAttenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InfoFileAttenteRepository::class)
 */
class InfoFileAttente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_entree;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_sortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $affluence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="infoFileAttentes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=FileAttente::class, inversedBy="infoFileAttentes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fileAttente;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dayDate;

    public function __construct()
    {
        $this->fileAttentes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureEntree(): ?\DateTimeInterface
    {
        return $this->heure_entree;
    }

    public function setHeureEntree(\DateTimeInterface $heure_entree): self
    {
        $this->heure_entree = $heure_entree;

        return $this;
    }

    public function getHeureSortie(): ?\DateTimeInterface
    {
        return $this->heure_sortie;
    }

    public function setHeureSortie(\DateTimeInterface $heure_sortie): self
    {
        $this->heure_sortie = $heure_sortie;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAffluence(): ?int
    {
        return $this->affluence;
    }

    public function setAffluence(int $affluence): self
    {
        $this->affluence = $affluence;

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

    public function getFileAttente(): ?FileAttente
    {
        return $this->fileAttente;
    }

    public function setFileAttente(?FileAttente $fileAttente): self
    {
        $this->fileAttente = $fileAttente;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDayDate(): ?\DateTimeInterface
    {
        return $this->dayDate;
    }

    public function setDayDate(?\DateTimeInterface $dayDate): self
    {
        $this->dayDate = $dayDate;

        return $this;
    }
}
