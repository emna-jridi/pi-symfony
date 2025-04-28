<?php

namespace App\Entity;

use App\Repository\ReunionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReunionRepository::class)]
#[ORM\Table(name: "reunion")]
class Reunion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id")]
    private ?int $id = null;

    #[ORM\Column(name: "titre", length: 255)]
    #[Assert\NotBlank(message: "Le titre est requis.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $titre = null;

    #[ORM\Column(name: "date", type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date est requise.")]
    #[Assert\Type("\DateTimeInterface")]
    #[Assert\GreaterThanOrEqual("today", message: "La date doit être aujourd'hui ou dans le futur.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(name: "type", length: 255)]
    #[Assert\NotBlank(message: "Le type est requis.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le type ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $type = null;

    #[ORM\Column(name: "description", length: 1000)]
    #[Assert\NotBlank(message: "La description est requise.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "ID_User")]
    #[Assert\NotNull(message: "Un utilisateur doit être associé à la réunion.")]
    private ?User $id_user = null;

    // Getters & Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
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
