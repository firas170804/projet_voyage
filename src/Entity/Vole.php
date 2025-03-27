<?php

namespace App\Entity;

use App\Repository\VoleRepository;
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
}
