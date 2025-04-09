<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FormationRepository;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ORM\Table(name: 'formation')]
class Formation
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(name: "id_formation")]
private $idFormation;

    public function getIdFormation(): ?int
    {
        return $this->idFormation;
    }

    public function setIdFormation(int $idFormation): self
    {
        $this->idFormation = $idFormation;
        return $this;
    }
    #[ORM\Column(name: "nom_formation")] 
    private $NomFormation;

    public function getNomFormation(): ?string
    {
        return $this->NomFormation;
    }

    public function setNomFormation(string $NomFormation): self
    {
        $this->NomFormation = $NomFormation;
        return $this;
    }


    #[ORM\Column(name: "theme_formation", nullable: true)]
    private $ThemeFormation;

    public function getThemeFormation(): ?string
    {
        return $this->ThemeFormation;
    }

    public function setThemeFormation(?string $ThemeFormation): self
    {
        $this->ThemeFormation = $ThemeFormation;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
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
    private ?string $lien_formation = null;

    public function getLien_formation(): ?string
    {
        return $this->lien_formation;
    }

    public function setLien_formation(?string $lien_formation): self
    {
        $this->lien_formation = $lien_formation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $niveau_difficulte = null;

    public function getNiveau_difficulte(): ?string
    {
        return $this->niveau_difficulte;
    }

    public function setNiveau_difficulte(?string $niveau_difficulte): self
    {
        $this->niveau_difficulte = $niveau_difficulte;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $niveau = null;

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duree = null;

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $image_url = null;

    public function getImage_url(): ?string
    {
        return $this->image_url;
    }

    public function setImage_url(?string $image_url): self
    {
        $this->image_url = $image_url;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date = null;

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getLienFormation(): ?string
    {
        return $this->lien_formation;
    }

    public function setLienFormation(?string $lien_formation): static
    {
        $this->lien_formation = $lien_formation;

        return $this;
    }

    public function getNiveauDifficulte(): ?string
    {
        return $this->niveau_difficulte;
    }

    public function setNiveauDifficulte(?string $niveau_difficulte): static
    {
        $this->niveau_difficulte = $niveau_difficulte;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

}
