<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\OffreemploiRepository;

#[ORM\Entity(repositoryClass: OffreemploiRepository::class)]
#[ORM\Table(name: 'offreemploi')]
class Offreemploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $candidaturesrecues = null;

    public function getCandidaturesrecues(): ?int
    {
        return $this->candidaturesrecues;
    }

    public function setCandidaturesrecues(?int $candidaturesrecues): self
    {
        $this->candidaturesrecues = $candidaturesrecues;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $titre = null;

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $experiencerequise = null;

    public function getExperiencerequise(): ?string
    {
        return $this->experiencerequise;
    }

    public function setExperiencerequise(?string $experiencerequise): self
    {
        $this->experiencerequise = $experiencerequise;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $niveauEtudes = null;

    public function getNiveauEtudes(): ?string
    {
        return $this->niveauEtudes;
    }

    public function setNiveauEtudes(?string $niveauEtudes): self
    {
        $this->niveauEtudes = $niveauEtudes;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $competences = null;

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(?string $competences): self
    {
        $this->competences = $competences;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $typecontrat = null;

    public function getTypecontrat(): ?string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(?string $typecontrat): self
    {
        $this->typecontrat = $typecontrat;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $localisation = null;

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $niveaulangues = null;

    public function getNiveaulangues(): ?string
    {
        return $this->niveaulangues;
    }

    public function setNiveaulangues(?string $niveaulangues): self
    {
        $this->niveaulangues = $niveaulangues;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateExpiration = null;

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(?\DateTimeInterface $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

}
