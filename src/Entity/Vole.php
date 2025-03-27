<?php

namespace App\Entity;

use App\Repository\VoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoleRepository::class)]
class Vole
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

    #[ORM\ManyToOne(inversedBy: 'passager')]
    private ?compagnie $compagnie = null;

    /**
     * @var Collection<int, passager>
     */
    #[ORM\OneToMany(targetEntity: passager::class, mappedBy: 'vole')]
    private Collection $passager;

    /**
     * @var Collection<int, reservation>
     */
    #[ORM\OneToMany(targetEntity: reservation::class, mappedBy: 'vole')]
    private Collection $reservation;

    public function __construct()
    {
        $this->passager = new ArrayCollection();
        $this->reservation = new ArrayCollection();
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

    public function getCompagnie(): ?compagnie
    {
        return $this->compagnie;
    }

    public function setCompagnie(?compagnie $compagnie): static
    {
        $this->compagnie = $compagnie;

        return $this;
    }

    /**
     * @return Collection<int, passager>
     */
    public function getPassager(): Collection
    {
        return $this->passager;
    }

    public function addPassager(passager $passager): static
    {
        if (!$this->passager->contains($passager)) {
            $this->passager->add($passager);
            $passager->setVole($this);
        }

        return $this;
    }

    public function removePassager(passager $passager): static
    {
        if ($this->passager->removeElement($passager)) {
            // set the owning side to null (unless already changed)
            if ($passager->getVole() === $this) {
                $passager->setVole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(reservation $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setVole($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVole() === $this) {
                $reservation->setVole(null);
            }
        }

        return $this;
    }
}
