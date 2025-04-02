<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $heureDepart = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $heureArrivee = null;

    #[ORM\ManyToOne(inversedBy: 'vols')]
    private ?compagnie $idCompagnie = null;

    /**
     * @var Collection<int, reservation>
     */
    #[ORM\OneToMany(targetEntity: reservation::class, mappedBy: 'vol')]
    private Collection $idVol;

    public function __construct()
    {
        $this->idVol = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDepart(): ?\DateTimeImmutable
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(\DateTimeImmutable $heureDepart): static
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }

    public function getHeureArrivee(): ?\DateTimeImmutable
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(\DateTimeImmutable $heureArrivee): static
    {
        $this->heureArrivee = $heureArrivee;

        return $this;
    }

    public function getIdCompagnie(): ?compagnie
    {
        return $this->idCompagnie;
    }

    public function setIdCompagnie(?compagnie $idCompagnie): static
    {
        $this->idCompagnie = $idCompagnie;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getIdVol(): Collection
    {
        return $this->idVol;
    }

    public function addIdVol(reservation $idVol): static
    {
        if (!$this->idVol->contains($idVol)) {
            $this->idVol->add($idVol);
            $idVol->setVol($this);
        }

        return $this;
    }

    public function removeIdVol(reservation $idVol): static
    {
        if ($this->idVol->removeElement($idVol)) {
            // set the owning side to null (unless already changed)
            if ($idVol->getVol() === $this) {
                $idVol->setVol(null);
            }
        }

        return $this;
    }
}
