<?php

namespace App\Entity;

use App\Repository\ReservationSalleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationSalleRepository::class)]
#[ORM\Table(name: 'reservation_salle')]
class ReservationSalle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdReservation', type: 'integer')]
    private ?int $IdReservation = null;

    #[ORM\Column(name: 'IdEmploye', type: 'integer')]
    private ?int $IdEmploye = null;

    #[ORM\ManyToOne(targetEntity: Salle::class)]
#[ORM\JoinColumn(name: 'IdSalle', referencedColumnName: 'idSalle')]
private ?Salle $IdSalle = null;

    #[ORM\Column(name: 'DateReservation', type: 'date')]
    private ?\DateTimeInterface $DateReservation = null;

    #[ORM\Column(name: 'DureeReservation', type: 'integer')]
    private ?int $DureeReservation = null;

    #[ORM\Column(name: 'StatutReservation', type: 'string', length: 255)]
    private ?string $StatutReservation = 'en attente';

    public function __construct()
    {
        $this->StatutReservation = 'en attente';
    }

    public function getIdReservation(): ?int
    {
        return $this->IdReservation;
    }

    public function setIdReservation(int $IdReservation): self
    {
        $this->IdReservation = $IdReservation;
        return $this;
    }

    public function getIdEmploye(): ?int
    {
        return $this->IdEmploye;
    }

    public function setIdEmploye(int $IdEmploye): self
    {
        $this->IdEmploye = $IdEmploye;
        return $this;
    }

    public function getIdSalle(): ?Salle
    {
        return $this->IdSalle;
    }

    public function setIdSalle(int $IdSalle): self
    {
        $this->IdSalle = $IdSalle;
        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->DateReservation;
    }

    public function setDateReservation(\DateTimeInterface $DateReservation): self
    {
        $this->DateReservation = $DateReservation;
        return $this;
    }

    public function getDureeReservation(): ?int
    {
        return $this->DureeReservation;
    }

    public function setDureeReservation(int $DureeReservation): self
    {
        $this->DureeReservation = $DureeReservation;
        return $this;
    }

    public function getStatutReservation(): ?string
    {
        return $this->StatutReservation;
    }

    public function setStatutReservation(string $StatutReservation): self
    {
        $this->StatutReservation = $StatutReservation;
        return $this;
    }
}
