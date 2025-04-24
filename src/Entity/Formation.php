<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;  
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ORM\Table(name: 'formation')]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "id_formation", type: "integer")]
private ?int $idFormation = null;

    public function getIdFormation(): ?int
    {
        return $this->idFormation;
    }



    #[ORM\Column(name: "nom_formation")] 
    #[Assert\NotBlank(message: "Veuillez saisir un nom de formation.")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Le nom de la formation doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom de la formation ne doit pas dépasser {{ limit }} caractères."
    )]
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


    #[ORM\Column(name: "theme_formation", nullable: false)]
    #[Assert\NotBlank(message: "Veuillez choisir un theme de formation.")]
 
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

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: "Veuillez donner une description de formation.")]
    #[Assert\Length(
        max: 2000,
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères."
    )]
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

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Veuillez saisir un url de formation.")]
    #[Assert\Url(message: "Veuillez saisir une URL valide.")]
    private ?string $lienFormation = null;
    public function getLienFormation(): ?string
    {
        return $this->lienFormation;
    }
    
    public function setLienFormation(?string $lienFormation): self
    {
        $this->lienFormation = $lienFormation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Veuillez saisir un niveau de formation.")]
   
    private ?string $niveauDifficulte = null;

    public function getNiveauDifficulte(): ?string
    {
        return $this->niveauDifficulte;
    }

    public function setNiveauDifficulte(?string $niveauDifficulte): self
    {
        $this->niveauDifficulte = $niveauDifficulte;
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

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Veuillez saisir une durée de formation.")]
    #[Assert\Range(
        min: 1,
        max: 365,
        notInRangeMessage: "La durée doit être comprise entre {{ min }} et {{ max }} jours."
    )]
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


    #[Assert\Url(message: "Veuillez saisir une URL valide pour l'image.")]

    private ?string $imageUrl = null;

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\NotBlank(message: "Veuillez saisir une date.")]
    #[Assert\GreaterThan("today", message: "La date doit être postérieure à aujourd'hui.")]

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
    private ?File $imageFile = null;

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
       
        if ($imageFile) {
            $this->imageUrl = null;
        }
        return $this;
    }

}
