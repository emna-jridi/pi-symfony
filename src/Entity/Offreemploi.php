<?php

namespace App\Entity;

use App\Enum\Experiencerequise;
use App\Enum\NiveauEtudes;
use App\Enum\Niveaulangues;
use App\Enum\Typecontrat;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\OffreemploiRepository;
use App\Enum\Statut;

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
    #[Assert\PositiveOrZero(message: 'Le nombre de candidatures reçues doit être positif ou zéro')]
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
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: 'Le titre doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères'
    )]
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
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    #[Assert\Length(
        min: 20,
        max: 2000,
        minMessage: 'La description doit faire au moins {{ limit }} caractères',
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères'
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

    #[ORM\Column(name:"experiencerequise",type: Types::STRING, enumType: Experiencerequise::class, nullable: true)]
    #[Assert\NotNull(message: 'Veuillez spécifier le niveau d\'expérience requis')]
    private ?Experiencerequise $experiencerequise = null;
    
    public function getExperiencerequise(): ?Experiencerequise
    {
        return $this->experiencerequise;
    }
    
    public function setExperiencerequise(?Experiencerequise $experiencerequise): self
    {
        $this->experiencerequise = $experiencerequise;
        return $this;
    }

    #[ORM\Column(name:"niveauEtudes",type: Types::STRING, enumType: NiveauEtudes::class, nullable: true)]
    #[Assert\NotNull(message: 'Veuillez spécifier le niveau d\'études requis')]
    private ?NiveauEtudes $niveauEtudes = null;

    public function getNiveauEtudes(): ?NiveauEtudes
    {
        return $this->niveauEtudes;
    }

    public function setNiveauEtudes(?NiveauEtudes $niveauEtudes): self
    {
        $this->niveauEtudes = $niveauEtudes;
        return $this;
    }

    #[ORM\Column(name:"competences",type: 'string', nullable: true)]
  
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



    #[ORM\Column(name:"typecontrat",type: Types::STRING, enumType: Typecontrat::class, nullable: true)]
    #[Assert\NotNull(message: 'Veuillez spécifier le type de contrat')]
    private ?Typecontrat $typecontrat = null;

    public function getTypecontrat(): ?Typecontrat
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(?Typecontrat $typecontrat): self
    {
        $this->typecontrat = $typecontrat;
        return $this;
    }

    #[ORM\Column(name:"localisation",type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'La localisation est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'La localisation doit faire au moins {{ limit }} caractères',
        maxMessage: 'La localisation ne peut pas dépasser {{ limit }} caractères'
    )]
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
   

    #[ORM\Column(name:"niveaulangues",type: Types::STRING, enumType: Niveaulangues::class, nullable: true)]
    #[Assert\NotNull(message: 'Veuillez spécifier le niveau de langue requis')]
    private ?Niveaulangues $niveaulangues = null;
    
    public function getNiveaulangues(): ?Niveaulangues
    {
        return $this->niveaulangues;
    }
    
    public function setNiveaulangues(?Niveaulangues $niveaulangues): self
    {
        $this->niveaulangues = $niveaulangues;
        return $this;
    }



 /*    #[ORM\Column(name: "niveaulangues", type: Types::JSON)]
#[Assert\NotNull(message: 'Veuillez spécifier le(s) niveau(x) de langue requis')]
private array $niveaulangues = [];
public function getNiveaulangues(): array
{
    return $this->niveaulangues;
}

public function setNiveaulangues(array $niveaulangues): self
{
    $this->niveaulangues = $niveaulangues;
    return $this;
} */

   /*  #[ORM\Column(name:"dateCreation",type: 'date', nullable: true)]
    #[Assert\NotNull(message: 'La date de création est obligatoire')]
    #[Assert\LessThanOrEqual(
        value: 'today',
        message: 'La date de création ne peut pas être dans le futur'
    )]
    private ?\DateTimeInterface $dateCreation = null;

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    } */
    

     #[ORM\Column(name:"dateCreation", type:"date", nullable:true)]
     
   /*  #[Assert\NotNull(message: 'La date de création est obligatoire')]
    #[Assert\LessThanOrEqual(
        value: 'today',
        message: 'La date de création ne peut pas être dans le futur'
    )] */
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


    #[ORM\Column(name:"dateExpiration",type: 'date', nullable: true)]
    #[Assert\NotNull(message: 'La date d\'expiration est obligatoire')]
    #[Assert\GreaterThan(
        propertyPath: 'dateCreation',
        message: 'La date d\'expiration doit être postérieure à la date de création'
    )]
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

    /* #[ORM\Column(name:"statut",type: Types::STRING, enumType: Statut::class, nullable: true)]
    #[Assert\NotNull(
        message: 'Le statut doit être l\'un des suivants: publié, brouillon, archivé, expiré'
    )]
    private ?Statut  $statut = null;

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;
        return $this;
    } */
   
    #[ORM\Column(name: "statut", type: Types::STRING, length: 255, nullable: true)]
   
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
    

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Candidature::class)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->localisation = 'Pôle Technologique, 1, 2 rue André Ampère, Cebalat 2083';
    }

    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setOffre($this);
        
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            if ($candidature->getOffre() === $this) {
                $candidature->setOffre(null);
               
            }
        }

        return $this;
    }

    /**
     * Validation personnalisée pour s'assurer que la date d'expiration est dans le futur
     */
    #[Assert\IsTrue(message: 'La date d\'expiration doit être dans le futur')]
    public function isDateExpirationValid(): bool
    {
        if ($this->dateExpiration === null) {
            return true;
        }
        
        return $this->dateExpiration > new \DateTime();
    }
  /*   public function __toString(): string
    {
        return $this->getTitre(); // ou un autre champ de votre entité que vous souhaitez afficher
    } */


   

}
