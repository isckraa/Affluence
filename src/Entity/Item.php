<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
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
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $cout;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\OneToMany(targetEntity=Recompense::class, mappedBy="item")
     */
    private $recompenses;

    /**
     * @ORM\ManyToOne(targetEntity=CatalogueRecompense::class, inversedBy="items")
     */
    private $catalogueRecompense;

    public function __construct()
    {
        $this->recompenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCout(): ?int
    {
        return $this->cout;
    }

    public function setCout(int $cout): self
    {
        $this->cout = $cout;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

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
            $recompense->setItem($this);
        }

        return $this;
    }

    public function removeRecompense(Recompense $recompense): self
    {
        if ($this->recompenses->contains($recompense)) {
            $this->recompenses->removeElement($recompense);
            // set the owning side to null (unless already changed)
            if ($recompense->getItem() === $this) {
                $recompense->setItem(null);
            }
        }

        return $this;
    }

    public function getCatalogueRecompense(): ?CatalogueRecompense
    {
        return $this->catalogueRecompense;
    }

    public function setCatalogueRecompense(?CatalogueRecompense $catalogueRecompense): self
    {
        $this->catalogueRecompense = $catalogueRecompense;

        return $this;
    }
}
