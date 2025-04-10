<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ContratRepository;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
#[ORM\Table(name: 'contrat')]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idContrat", type: 'integer')]  // Explicit name
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

    #[ORM\Column(type: 'date', nullable: false)]
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

    #[ORM\Column(type: 'date', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $StatusContrat = null;

    public function getStatusContrat(): ?string
    {
        return $this->StatusContrat;
    }

    public function setStatusContrat(string $StatusContrat): self
    {
        $this->StatusContrat = $StatusContrat;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $modePaiement = null;

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): self
    {
        $this->modePaiement = $modePaiement;
        return $this;
    }

}
