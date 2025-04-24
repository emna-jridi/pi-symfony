<?php

namespace App\Entity;
use App\Enum\Statut;

use App\Entity\Offreemploi;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidatureRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ORM\Table(name: 'candidature')]
class Candidature
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




    #[ORM\Column(name:"dateCandidature",type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateCandidature = null;

    public function getDateCandidature(): ?\DateTimeInterface
    {
        return $this->dateCandidature;
    }

    public function setDateCandidature(?\DateTimeInterface $dateCandidature): self
    {
        $this->dateCandidature = $dateCandidature;
        return $this;
    }






    #[ORM\Column(name:"statut",type: 'string', enumType: Statut::class, nullable: true)]
    private ?Statut $statut = null;

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }
    
    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;
        return $this;
    }






    #[ORM\Column(name:"cvUrl",type: 'string', nullable: true)]
  /*   #[Assert\NotBlank(message: "Le CV est obligatoire.")] */

   
    private ?string $cvUrl = null;

    public function getCvUrl(): ?string
    {
        return $this->cvUrl;
    }

    public function setCvUrl(?string $cvUrl): self
    {
        $this->cvUrl = $cvUrl;
        return $this;
    }





    #[ORM\Column(name:"lettreMotivation",type: 'string', nullable: true)]
/*     #[Assert\NotBlank(message: "La lettre de motivation est obligatoire.")] */

    private ?string $lettreMotivation = null;

    public function getLettreMotivation(): ?string
    {
        return $this->lettreMotivation;
    }

    public function setLettreMotivation(?string $lettreMotivation): self
    {
        $this->lettreMotivation = $lettreMotivation;
        return $this;
    }






   /*  #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $offreId = null;

    public function getOffreId(): ?int
    {
        return $this->offreId;
    }

    public function setOffreId(?int $offreId): self
    {
        $this->offreId = $offreId;
        return $this;
    } */

    #[ORM\ManyToOne(targetEntity: Offreemploi::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(name: 'offreId', referencedColumnName: 'id', nullable: true,onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "L'offre d'emploi est obligatoire.")]
    private ?Offreemploi $offre = null;
    
    public function getOffre(): ?Offreemploi
    {
        return $this->offre;
    }
    
    public function setOffre(?Offreemploi $offre): self
    {
        $this->offre = $offre;
        return $this;
    }



    #[ORM\Column(name:"nom",type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le Nom est obligatoire.")]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(name:"prenom",type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le Prènom est obligatoire.")]
    private ?string $prenom = null;

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    #[ORM\Column(name:"email",type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
#[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(name:"telephone",type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le numéro de téléphone doit contenir exactement 8 chiffres."
    )]
    private ?string $telephone = null;

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    #[ORM\Column(name:"candidat_id ",type: 'integer', nullable: true)]
    private ?int $candidat_id = null;

    public function getCandidat_id(): ?int
    {
        return $this->candidat_id;
    }

    public function setCandidat_id(?int $candidat_id): self
    {
        $this->candidat_id = $candidat_id;
        return $this;
    }

    public function getCandidatId(): ?int
    {
        return $this->candidat_id;
    }

    public function setCandidatId(?int $candidat_id): static
    {
        $this->candidat_id = $candidat_id;

        return $this;
    }

}
