<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\SalleRepository;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\Table(name: 'salle')]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idSalle = null;

    public function getIdSalle(): ?int
    {
        return $this->idSalle;
    }

    public function setIdSalle(int $idSalle): self
    {
        $this->idSalle = $idSalle;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $RefSalle = null;

    public function getRefSalle(): ?string
    {
        return $this->RefSalle;
    }

    public function setRefSalle(string $RefSalle): self
    {
        $this->RefSalle = $RefSalle;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $Capacite = null;

    public function getCapacite(): ?int
    {
        return $this->Capacite;
    }

    public function setCapacite(int $Capacite): self
    {
        $this->Capacite = $Capacite;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Disponibilité = null;

    public function getDisponibilité(): ?string
    {
        return $this->Disponibilité;
    }

    public function setDisponibilité(string $Disponibilité): self
    {
        $this->Disponibilité = $Disponibilité;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $TypeSalle = null;

    public function getTypeSalle(): ?string
    {
        return $this->TypeSalle;
    }

    public function setTypeSalle(string $TypeSalle): self
    {
        $this->TypeSalle = $TypeSalle;
        return $this;
    }

}
