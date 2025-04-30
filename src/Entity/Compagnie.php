<?php

namespace App\Entity;

use App\Repository\CompagnieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompagnieRepository::class)]
class Compagnie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    /**
     * @var Collection<int, Vol>
     */
    #[ORM\OneToMany(targetEntity: Vol::class, mappedBy: 'compagnie')]
    private Collection $vols;

    /**
     * @var Collection<int, Avion>
     */
    #[ORM\OneToMany(targetEntity: Avion::class, mappedBy: 'compagnie')]
    private Collection $avions;

    public function __construct()
    {
        $this->vols = new ArrayCollection();
        $this->avions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection<int, Vol>
     */
    public function getVols(): Collection
    {
        return $this->vols;
    }

    public function addVol(Vol $vol): static
    {
        if (!$this->vols->contains($vol)) {
            $this->vols->add($vol);
            $vol->setCompagnie($this);
        }

        return $this;
    }

    public function removeVol(Vol $vol): static
    {
        if ($this->vols->removeElement($vol)) {
            // set the owning side to null (unless already changed)
            if ($vol->getCompagnie() === $this) {
                $vol->setCompagnie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avion>
     */
    public function getAvions(): Collection
    {
        return $this->avions;
    }

    public function addAvion(Avion $avion): static
    {
        if (!$this->avions->contains($avion)) {
            $this->avions->add($avion);
            $avion->setCompagnie($this);
        }

        return $this;
    }

    public function removeAvion(Avion $avion): static
    {
        if ($this->avions->removeElement($avion)) {
            // set the owning side to null (unless already changed)
            if ($avion->getCompagnie() === $this) {
                $avion->setCompagnie(null);
            }
        }

        return $this;
    }
}
