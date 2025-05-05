<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\ServiceRepository;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'services')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idService", type: 'integer')]
    private ?int $idService = null;

    public function getIdService(): ?int
    {
        return $this->idService;
    }

    public function setIdService(int $idService): self
    {
        $this->idService = $idService;
        return $this;
    }

    #[ORM\Column(name: "NomService", type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le nom de service est requis")]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: "Le nom du service doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le nom du service ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $NomService = null;

    public function getNomService(): ?string
    {
        return $this->NomService;
    }

    public function setNomService(string $NomService): self
    {
        $this->NomService = $NomService;
        return $this;
    }

    #[ORM\Column(name: "DescriptionService", type: 'text', nullable: false)]
    #[Assert\NotBlank(message: "La description du service est requise")]
    #[Assert\Length(
    min: 10,
    minMessage: "La description doit comporter au moins {{ limit }} caractères"
)]
    private ?string $DescriptionService = null;

    public function getDescriptionService(): ?string
    {
        return $this->DescriptionService;
    }

    public function setDescriptionService(string $DescriptionService): self
    {
        $this->DescriptionService = $DescriptionService;
        return $this;
    }

    #[ORM\Column(name: "TypeService", type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le type de service est requis")]
    private ?string $TypeService = null;

    public function getTypeService(): ?string
    {
        return $this->TypeService;
    }

    public function setTypeService(string $TypeService): self
    {
        $this->TypeService = $TypeService;
        return $this;
    }

    #[ORM\Column(name: "DateDebutService", type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de début de service est requise")]
    #[Assert\LessThan(propertyPath: "DateFinService", message: "La date de début doit être antérieure à la date de fin")]
    private ?\DateTimeInterface $DateDebutService = null;

    public function getDateDebutService(): ?\DateTimeInterface
    {
        return $this->DateDebutService;
    }

    public function setDateDebutService(\DateTimeInterface $DateDebutService): self
    {
        $this->DateDebutService = $DateDebutService;
        return $this;
    }

    #[ORM\Column(name: "DateFinService", type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de fin de service est requise")]
    #[Assert\GreaterThan(propertyPath: "DateDebutService", message: "La date de fin doit être postérieure à la date de début")]
    private ?\DateTimeInterface $DateFinService = null;

    public function getDateFinService(): ?\DateTimeInterface
    {
        return $this->DateFinService;
    }

    public function setDateFinService(\DateTimeInterface $DateFinService): self
    {
        $this->DateFinService = $DateFinService;
        return $this;
    }

    #[ORM\Column(name: "StatusService", type: 'string', nullable: false)]
    private ?string $StatusService = null;

    public function getStatusService(): ?string
    {
        return $this->StatusService;
    }

    public function setStatusService(string $StatusService): self
    {
        $this->StatusService = $StatusService;
        return $this;
    }







    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ContratService::class)]
private Collection $contratServices;

public function __construct()
{
    $this->contratServices = new ArrayCollection();
}

public function getContratServices(): Collection
{
    return $this->contratServices;
}



public function addContratService(ContratService $contratService): self
    {
        if (!$this->contratServices->contains($contratService)) {
            $this->contratServices[] = $contratService;
        }
        return $this;
    }

    public function removeContratService(ContratService $contratService): self
    {
        $this->contratServices->removeElement($contratService);
        return $this;
    }




}
