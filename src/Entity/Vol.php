<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heurDepart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heurArrive = null;

    #[ORM\ManyToOne(inversedBy: 'vols')]
    private ?Compagnie $compagnie = null;



    #[ORM\ManyToOne(inversedBy: 'vols')]
    private ?Avion $avion = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'vol')]
    private Collection $reservation;

    #[ORM\ManyToOne(inversedBy: 'vols')]
    private ?Aeroport $aeroport = null;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeurDepart(): ?\DateTimeInterface
    {
        return $this->heurDepart;
    }

    public function setHeurDepart(\DateTimeInterface $heurDepart): static
    {
        $this->heurDepart = $heurDepart;
 
        return $this;
    }

    public function getHeurArrive(): ?\DateTimeInterface
    {
        return $this->heurArrive;
    }

    public function setHeurArrive(\DateTimeInterface $heurArrive): static
    {
        $this->heurArrive = $heurArrive;

        return $this;
    }

    public function getCompagnie(): ?Compagnie
    {
        return $this->compagnie;
    }

    public function setCompagnie(?Compagnie $compagnie): static
    {
        $this->compagnie = $compagnie;

        return $this;
    }







    public function getAvion(): ?Avion
    {
        return $this->avion;
    }

    public function setAvion(?Avion $avion): static
    {
        $this->avion = $avion;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setVol($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVol() === $this) {
                $reservation->setVol(null);
            }
        }

        return $this;
    }

    public function getAeroport(): ?Aeroport
    {
        return $this->aeroport;
    }

    public function setAeroport(?Aeroport $aeroport): static
    {
        $this->aeroport = $aeroport;

        return $this;
    }
}