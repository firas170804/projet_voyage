<?php

namespace App\Entity;

use App\Repository\PassagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PassagerRepository::class)]
class Passager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    /**
     * @var Collection<int, reservation>
     */
    #[ORM\OneToMany(targetEntity: reservation::class, mappedBy: 'passager')]
    private Collection $idPassager;

    public function __construct()
    {
        $this->idPassager = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getIdPassager(): Collection
    {
        return $this->idPassager;
    }

    public function addIdPassager(reservation $idPassager): static
    {
        if (!$this->idPassager->contains($idPassager)) {
            $this->idPassager->add($idPassager);
            $idPassager->setPassager($this);
        }

        return $this;
    }

    public function removeIdPassager(reservation $idPassager): static
    {
        if ($this->idPassager->removeElement($idPassager)) {
            // set the owning side to null (unless already changed)
            if ($idPassager->getPassager() === $this) {
                $idPassager->setPassager(null);
            }
        }

        return $this;
    }
}
