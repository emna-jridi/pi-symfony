<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\EmployéRepository;

#[ORM\Entity(repositoryClass: EmployéRepository::class)]
#[ORM\Table(name: 'employés')]
class Employé
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $IdEmploye = null;

    public function getIdEmploye(): ?int
    {
        return $this->IdEmploye;
    }

    public function setIdEmploye(int $IdEmploye): self
    {
        $this->IdEmploye = $IdEmploye;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $NomEmploye = null;

    public function getNomEmploye(): ?string
    {
        return $this->NomEmploye;
    }

    public function setNomEmploye(string $NomEmploye): self
    {
        $this->NomEmploye = $NomEmploye;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $PrenomEmploye = null;

    public function getPrenomEmploye(): ?string
    {
        return $this->PrenomEmploye;
    }

    public function setPrenomEmploye(string $PrenomEmploye): self
    {
        $this->PrenomEmploye = $PrenomEmploye;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateNaissanceEmploye = null;

    public function getDateNaissanceEmploye(): ?\DateTimeInterface
    {
        return $this->DateNaissanceEmploye;
    }

    public function setDateNaissanceEmploye(\DateTimeInterface $DateNaissanceEmploye): self
    {
        $this->DateNaissanceEmploye = $DateNaissanceEmploye;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $AdresseEmploye = null;

    public function getAdresseEmploye(): ?string
    {
        return $this->AdresseEmploye;
    }

    public function setAdresseEmploye(string $AdresseEmploye): self
    {
        $this->AdresseEmploye = $AdresseEmploye;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $TelephoneEmploye = null;

    public function getTelephoneEmploye(): ?float
    {
        return $this->TelephoneEmploye;
    }

    public function setTelephoneEmploye(float $TelephoneEmploye): self
    {
        $this->TelephoneEmploye = $TelephoneEmploye;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $EmailEmploye = null;

    public function getEmailEmploye(): ?string
    {
        return $this->EmailEmploye;
    }

    public function setEmailEmploye(string $EmailEmploye): self
    {
        $this->EmailEmploye = $EmailEmploye;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $PosteEmploye = null;

    public function getPosteEmploye(): ?string
    {
        return $this->PosteEmploye;
    }

    public function setPosteEmploye(string $PosteEmploye): self
    {
        $this->PosteEmploye = $PosteEmploye;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateEmbaucheEmploye = null;

    public function getDateEmbaucheEmploye(): ?\DateTimeInterface
    {
        return $this->DateEmbaucheEmploye;
    }

    public function setDateEmbaucheEmploye(\DateTimeInterface $DateEmbaucheEmploye): self
    {
        $this->DateEmbaucheEmploye = $DateEmbaucheEmploye;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $NbTTValidé = null;

    public function getNbTTValidé(): ?int
    {
        return $this->NbTTValidé;
    }

    public function setNbTTValidé(int $NbTTValidé): self
    {
        $this->NbTTValidé = $NbTTValidé;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $NbTTRefusé = null;

    public function getNbTTRefusé(): ?int
    {
        return $this->NbTTRefusé;
    }

    public function setNbTTRefusé(int $NbTTRefusé): self
    {
        $this->NbTTRefusé = $NbTTRefusé;
        return $this;
    }

}
