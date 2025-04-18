<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\Typecontrat;
use App\Entity\User;
use App\Repository\ContratEmployeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ContratEmployeRepository::class)]
#[ORM\Table(name: 'contratemploye')]

#[Callback('validate')]
class ContratEmploye
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idContratEmp", type: 'integer', unique: true)]
    private ?int $idContratEmp = null;

    public function getIdContratEmp(): ?int
    {
        return $this->idContratEmp;
    }

    public function setIdContratEmp(int $idContratEmp): self
    {
        $this->idContratEmp = $idContratEmp;
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

    #[ORM\Column(name: "DateFinContrat", type: 'date', nullable: true)]
    #[Assert\GreaterThan(propertyPath: "DateDebutContrat", message: "La date de fin doit être postérieure à la date de début")]
    private ?\DateTimeInterface $DateFinContrat = null;

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->DateFinContrat;
    }

    public function setDateFinContrat(?\DateTimeInterface $DateFinContrat): self
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

    #[ORM\Column(name: "Salaire", type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Le salaire est requis")]
    #[Assert\Type(
        type: 'numeric',
        message: 'Le salaire doit être un nombre.'
    )]
    #[Assert\Positive(
        message: 'Le salaire doit être supérieur à 0.'
    )]
    private ?int $Salaire = null;

    public function getSalaire(): ?int
    {
        return $this->Salaire;
    }

    public function setSalaire(int $Salaire): self
    {
        $this->Salaire = $Salaire;
        return $this;
    }

    
    
    #[ORM\Column(name: "typecontrat",type: Types::STRING, enumType: Typecontrat::class)]
    #[Assert\NotBlank(message: "Le type du contrat est requis")]
private ?Typecontrat $typecontrat = null;


public function gettypecontrat(): ?Typecontrat
{
    return $this->typecontrat;
}

public function settypecontrat(?Typecontrat $typecontrat): self
{
    $this->typecontrat = $typecontrat;
    return $this;
}




#[ORM\OneToOne]
#[ORM\JoinColumn(name: "id_user", referencedColumnName: "ID_User", onDelete: 'CASCADE', nullable: false)]
#[Assert\NotBlank(message: "L'employé est requis")]
private ?User $user = null;

public function getUser(): ?User
{
    return $this->user;
}

public function setUser(?User $user): self
{
    $this->user = $user;
    return $this;
}




public function validate(ExecutionContextInterface $context): void
{
    if ($this->gettypecontrat() !== Typecontrat::CDI && $this->getDateFinContrat() === null) {
        $context->buildViolation('La date de fin est obligatoire pour ce type de contrat.')
            ->atPath('DateFinContrat')
            ->addViolation();
    }
}



}
