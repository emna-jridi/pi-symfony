<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Employé;
use App\Repository\TeletravailRepository;
use PhpParser\Node\Name;

#[ORM\Entity(repositoryClass: TeletravailRepository::class)]
#[ORM\Table(name: 'teletravail')]
class Teletravail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idTeletravail',type: 'integer')]
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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "IdEmploye", referencedColumnName: "ID_User")]
    private ?User $employe = null;
    
    public function getEmploye(): ?User
    {
        return $this->employe;
    }
    
    public function setEmploye(?User $employe): self
    {
        $this->employe = $employe;
        return $this;
    }

    #[ORM\Column(name:'DateDemandeTT',type: 'date', nullable: false)]
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

    #[ORM\Column(name:'DateDebutTT',type: 'date', nullable: false)]
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

    #[ORM\Column(name:'DateFinTT',type: 'date', nullable: false)]
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

    #[ORM\Column(name:'StatutTT',type: 'string', nullable: false)]
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

    #[ORM\Column(name:'RaisonTT',type: 'string', nullable: false)]
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
