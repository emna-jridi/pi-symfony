<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\SalleRepository;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[ORM\Table(name: 'salle')]
class Salle {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idSalle',type: 'integer')]
    private ?int $idSalle = null;
    
    public function getIdSalle(): ?int
    {
        return $this->idSalle;
    }
    
    public function setIdSalle(int $idSalle): self
    {
        $this->idSalle = $idSalle;
        return $this;
    }
    
    #[ORM\Column(name:'RefSalle',type: 'string', nullable: false)]
    private ?string $RefSalle = null;
    
    public function getRefSalle(): ?string
    {
        return $this->RefSalle;
    }
    
    public function setRefSalle(string $RefSalle): self
    {
        $this->RefSalle = $RefSalle;
        return $this;
    }
    
    #[ORM\Column(name:'Capacite',type: 'integer', nullable: false)]
    private ?int $Capacite = null;
    
    public function getCapacite(): ?int
    {
        return $this->Capacite;
    }
    
    public function setCapacite(int $Capacite): self
    {
        $this->Capacite = $Capacite;
        return $this;
    }
    
    #[ORM\Column(name:'Disponibilite',type: 'string', nullable: false)]
    private ?string $Disponibilite = null;
    
    public function getDisponibilite(): ?string
    {
        return $this->Disponibilite;
    }
    
    public function setDisponibilite(string $Disponibilite): self
    {
        $this->Disponibilite = $Disponibilite;
        return $this;
    }
    
    #[ORM\Column(name:'TypeSalle',type: 'string', nullable: false)]
    private ?string $TypeSalle = null;
    
    public function getTypeSalle(): ?string
    {
        return $this->TypeSalle;
    }
    
    public function setTypeSalle(string $TypeSalle): self
    {
        $this->TypeSalle = $TypeSalle;
        return $this;
    }
    
    /**
     * Converts the Salle entity to a string representation
     * This is used when PHP needs to convert the object to a string
     */
    public function __toString(): string
    {
        return $this->RefSalle ?? 'Salle #' . $this->idSalle;
    }
}