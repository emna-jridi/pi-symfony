<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationSalleRepository;

#[ORM\Entity(repositoryClass: ReservationSalleRepository::class)]
#[ORM\Table(name: 'reservation_salle')]
class ReservationSalle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $IdReservation = null;

    public function getIdReservation(): ?int
    {
        return $this->IdReservation;
    }

    public function setIdReservation(int $IdReservation): self
    {
        $this->IdReservation = $IdReservation;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $IdEmploye = null;

    public function getIdEmploye(): ?int
    {
        return $this->IdEmploye;
    }

    public function setIdEmploye(int $IdEmploye): self
    {
        $this->IdEmploye = $IdEmploye;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $IdSalle = null;

    public function getIdSalle(): ?int
    {
        return $this->IdSalle;
    }

    public function setIdSalle(int $IdSalle): self
    {
        $this->IdSalle = $IdSalle;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateReservation = null;

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->DateReservation;
    }

    public function setDateReservation(\DateTimeInterface $DateReservation): self
    {
        $this->DateReservation = $DateReservation;
        return $this;
    }

    #[ORM\Column(type: 'time', nullable: false)]
    private ?string $DureeReservation = null;

    public function getDureeReservation(): ?string
    {
        return $this->DureeReservation;
    }

    public function setDureeReservation(string $DureeReservation): self
    {
        $this->DureeReservation = $DureeReservation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $StatutReservation = null;

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
