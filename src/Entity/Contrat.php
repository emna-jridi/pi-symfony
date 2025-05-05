<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\ModePaiement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\ContratRepository;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
#[ORM\Table(name: 'contrat')]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idContrat", type: 'integer', unique: true)]
    private ?int $idContrat = null;

    public function getIdContrat(): ?int
    {
        return $this->idContrat;
    }

    public function setIdContrat(int $idContrat): self
    {
        $this->idContrat = $idContrat;
        return $this;
    }

    #[ORM\Column(name: "DateDebutContrat", type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de début du contrat est requis")]
    #[Assert\LessThan(propertyPath: "DateFinContrat", message: "La date de début doit être antérieure à la date de fin")]
    private ?\DateTimeInterface $DateDebutContrat = null;

    public function getDateDebutContrat(): ?\DateTimeInterface
    {
        return $this->DateDebutContrat;
    }

    public function setDateDebutContrat(\DateTimeInterface $DateDebutContrat): self
    {
        $this->DateDebutContrat = $DateDebutContrat;
        return $this;
    }

    #[ORM\Column(name: "DateFinContrat", type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de fin du contrat est requis")]
    #[Assert\GreaterThan(propertyPath: "DateDebutContrat", message: "La date de fin doit être postérieure à la date de début")]
    private ?\DateTimeInterface $DateFinContrat = null;

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->DateFinContrat;
    }

    public function setDateFinContrat(\DateTimeInterface $DateFinContrat): self
    {
        $this->DateFinContrat = $DateFinContrat;
        return $this;
    }

    #[ORM\Column(name: "StatusContrat", type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le statut du contrat est requis")]
    #[Assert\Choice(
        choices: ['Actif', 'Inactif'],
        message: "Veuillez choisir un statut valide."
    )]
    private ?string $StatusContrat = 'Actif';

    public function getStatusContrat(): ?string
    {
        return $this->StatusContrat;
    }

    public function setStatusContrat(string $StatusContrat): self
    {
        $this->StatusContrat = $StatusContrat;
        return $this;
    }

    #[ORM\Column(name: "MontantContrat", type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Le montant est requis")]
    #[Assert\Type(
        type: 'numeric',
        message: 'Le montant doit être un nombre.'
    )]
    #[Assert\Positive(
        message: 'Le montant doit être supérieur à 0.'
    )]
    private ?int $MontantContrat = null;

    public function getMontantContrat(): ?int
    {
        return $this->MontantContrat;
    }

    public function setMontantContrat(int $MontantContrat): self
    {
        $this->MontantContrat = $MontantContrat;
        return $this;
    }

    #[ORM\Column(name: "NomClient", type: 'string')]
    #[Assert\NotBlank(message: "Le nom du client est requis")]
    private ?string $NomClient = null;

    public function getNomClient(): ?string
    {
        return $this->NomClient;
    }

    public function setNomClient(string $NomClient): self
    {
        $this->NomClient = $NomClient;
        return $this;
    }

    #[ORM\Column(name: "EmailClient", type: 'string')]
    #[Assert\NotBlank(message: "L'Email est requis")]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide")]
    private ?string $EmailClient = null;

    public function getEmailClient(): ?string
    {
        return $this->EmailClient;
    }

    public function setEmailClient(string $EmailClient): self
    {
        $this->EmailClient = $EmailClient;
        return $this;
    }

    #[ORM\Column(name: "telephoneClient", type: 'string')]
    #[Assert\NotBlank(message: "Le numéro de téléphone est requis")]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: 'Le numéro de téléphone doit contenir exactement 8 chiffres.'
    )]
    
    private ?string $telephoneClient = null;

    public function getTelephoneClient(): ?string
    {
        return $this->telephoneClient;
    }

    public function setTelephoneClient(string $telephoneClient): self
    {
        $this->telephoneClient = $telephoneClient;
        return $this;
    }

    #[ORM\Column(name: "modePaiement", type: Types::STRING, enumType: ModePaiement::class)]
    #[Assert\NotBlank(message: "Le mode de paiement est requis")]
    private ?ModePaiement $modePaiement = null;

    public function getModePaiement(): ?ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?ModePaiement $modePaiement): self
    {
        $this->modePaiement = $modePaiement;
        return $this;
    }

    

    #[ORM\OneToMany(mappedBy: 'contrat', targetEntity: ContratService::class)]
   
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

public function getServices(): Collection
{
    return new ArrayCollection(array_map(fn($cs) => $cs->getService(), $this->contratServices->toArray()));
}









}
