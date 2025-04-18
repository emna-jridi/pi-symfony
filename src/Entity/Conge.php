<?php

namespace App\Entity;

use App\Repository\CongeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id")]
    private ?int $id = null;

    #[ORM\Column(name: "Type_conge", length: 255)]
    #[Assert\NotBlank(message: "Le type de congé est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le type de congé ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $Type_conge = null;

    #[ORM\Column(name: "Date_debut", type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    #[Assert\Type("\DateTimeInterface")]
    #[Assert\LessThanOrEqual(
        propertyPath: "Date_fin",
        message: "La date de début doit être antérieure ou égale à la date de fin."
    )]
    private ?\DateTimeInterface $Date_debut = null;

    #[ORM\Column(name: "Status", length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Choice(
        choices: ['En attente', 'Accepté', 'Refusé'],
        message: "Le statut doit être 'En attente', 'Accepté' ou 'Refusé'."
    )]
    private ?string $Status = null;

    #[ORM\Column(name: "Date_fin", type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de fin est obligatoire.")]
    #[Assert\Type("\DateTimeInterface")]
    #[Assert\GreaterThanOrEqual(
        propertyPath: "Date_debut",
        message: "La date de fin doit être postérieure ou égale à la date de début."
    )]
    private ?\DateTimeInterface $Date_fin = null;

    #[ORM\ManyToOne(inversedBy: 'conges')]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "ID_User")]
    #[Assert\NotNull(message: "L'utilisateur est obligatoire.")]
    private ?User $id_user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): static
    {
        $this->Status = $Status;
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

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;
        return $this;
    }
}
