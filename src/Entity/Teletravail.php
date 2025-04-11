<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TeletravailRepository;

#[ORM\Entity(repositoryClass: TeletravailRepository::class)]
#[ORM\Table(name: 'teletravail')]
class Teletravail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $IdTeletravail = null;

    
    public function getIdTeletravail(): ?int
    {
        return $this->IdTeletravail;
    }

    public function setIdTeletravail(int $IdTeletravail): self
    {
        $this->IdTeletravail = $IdTeletravail;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
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

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateDemandeTT = null;

    public function getDateDemandeTT(): ?\DateTimeInterface
    {
        return $this->DateDemandeTT;
    }

    public function setDateDemandeTT(\DateTimeInterface $DateDemandeTT): self
    {
        $this->DateDemandeTT = $DateDemandeTT;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateDebutTT = null;

    public function getDateDebutTT(): ?\DateTimeInterface
    {
        return $this->DateDebutTT;
    }

    public function setDateDebutTT(\DateTimeInterface $DateDebutTT): self
    {
        $this->DateDebutTT = $DateDebutTT;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $DateFinTT = null;

    public function getDateFinTT(): ?\DateTimeInterface
    {
        return $this->DateFinTT;
    }

    public function setDateFinTT(\DateTimeInterface $DateFinTT): self
    {
        $this->DateFinTT = $DateFinTT;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $StatutTT = null;

    public function getStatutTT(): ?string
    {
        return $this->StatutTT;
    }

    public function setStatutTT(string $StatutTT): self
    {
        $this->StatutTT = $StatutTT;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $RaisonTT = null;

    public function getRaisonTT(): ?string
    {
        return $this->RaisonTT;
    }

    public function setRaisonTT(string $RaisonTT): self
    {
        $this->RaisonTT = $RaisonTT;
        return $this;
    }

}
