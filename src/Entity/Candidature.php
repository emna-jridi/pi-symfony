<?php

namespace App\Entity;

use App\Enum\Statut;
use App\Entity\Offreemploi;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidatureRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ORM\Table(name: 'candidature')]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: "dateCandidature", type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateCandidature = null;

    #[ORM\Column(name: "statut", type: 'string', enumType: Statut::class, nullable: true)]
    private ?Statut $statut = null;

    #[ORM\Column(name: "cvUrl", type: 'string', nullable: true)]
    private ?string $cvUrl = null;

    #[ORM\Column(name: "lettreMotivation", type: 'string', nullable: true)]
    private ?string $lettreMotivation = null;

    #[ORM\ManyToOne(targetEntity: Offreemploi::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(name: 'offreId', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?Offreemploi $offre = null;

    #[ORM\Column(name: "nom", type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le Nom est obligatoire.")]
    private ?string $nom = null;

    #[ORM\Column(name: "prenom", type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le Prènom est obligatoire.")]
    private ?string $prenom = null;

    #[ORM\Column(name: "email", type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(name: "telephone", type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le numéro de téléphone doit contenir exactement 8 chiffres."
    )]
    private ?string $telephone = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'candidat_id', referencedColumnName: 'ID_User', nullable: true, onDelete: 'SET NULL')]
    private ?User $candidat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDateCandidature(): ?\DateTimeInterface
    {
        return $this->dateCandidature;
    }

    public function setDateCandidature(?\DateTimeInterface $dateCandidature): self
    {
        $this->dateCandidature = $dateCandidature;
        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }
    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCvUrl(): ?string
    {
        return $this->cvUrl;
    }

    public function setCvUrl(?string $cvUrl): self
    {
        $this->cvUrl = $cvUrl;
        return $this;
    }

    public function getLettreMotivation(): ?string
    {
        return $this->lettreMotivation;
    }

    public function setLettreMotivation(?string $lettreMotivation): self
    {
        $this->lettreMotivation = $lettreMotivation;
        return $this;
    }

    public function getOffre(): ?Offreemploi
    {
        return $this->offre;
    }
    public function setOffre(?Offreemploi $offre): self
    {
        $this->offre = $offre;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getCandidat(): ?User
    {
        return $this->candidat;
    }

    public function setCandidat(?User $candidat): self
    {
        $this->candidat = $candidat;
        return $this;
    }
}
