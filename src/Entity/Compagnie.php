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
     * @var Collection<int, Vole>
     */
    #[ORM\OneToMany(targetEntity: Vole::class, mappedBy: 'compagnie')]
    private Collection $passager;

    public function __construct()
    {
        $this->passager = new ArrayCollection();
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
     * @return Collection<int, Vole>
     */
    public function getPassager(): Collection
    {
        return $this->passager;
    }

    public function addPassager(Vole $passager): static
    {
        if (!$this->passager->contains($passager)) {
            $this->passager->add($passager);
            $passager->setCompagnie($this);
        }

        return $this;
    }

    public function removePassager(Vole $passager): static
    {
        if ($this->passager->removeElement($passager)) {
            // set the owning side to null (unless already changed)
            if ($passager->getCompagnie() === $this) {
                $passager->setCompagnie(null);
            }
        }

        return $this;
    }
}
