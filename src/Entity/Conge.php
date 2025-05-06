<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CongeRepository;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
#[ORM\Table(name: 'conge')]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $Id = null;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function setId(int $Id): self
    {
        $this->Id = $Id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Type_conge = null;

    public function getType_conge(): ?string
    {
        return $this->Type_conge;
    }

    public function setType_conge(string $Type_conge): self
    {
        $this->Type_conge = $Type_conge;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $Date_debut = null;

    public function getDate_debut(): ?\DateTimeInterface
    {
        return $this->Date_debut;
    }

    public function setDate_debut(\DateTimeInterface $Date_debut): self
    {
        $this->Date_debut = $Date_debut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $Date_fin = null;

    public function getDate_fin(): ?\DateTimeInterface
    {
        return $this->Date_fin;
    }

    public function setDate_fin(\DateTimeInterface $Date_fin): self
    {
        $this->Date_fin = $Date_fin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Status = null;

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;
        return $this;
    }

    public function getTypeConge(): ?string
    {
        return $this->Type_conge;
    }

    public function setTypeConge(string $Type_conge): static
    {
        $this->Type_conge = $Type_conge;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->Date_debut;
    }

    public function setDateDebut(\DateTimeInterface $Date_debut): static
    {
        $this->Date_debut = $Date_debut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->Date_fin;
    }

    public function setDateFin(\DateTimeInterface $Date_fin): static
    {
        $this->Date_fin = $Date_fin;

        return $this;
    }

}
