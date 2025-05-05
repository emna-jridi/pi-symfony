<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContratServiceRepository;

#[ORM\Entity(repositoryClass: ContratServiceRepository::class)]
#[ORM\Table(name: 'contrat_services')]
class ContratService
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Contrat::class, inversedBy: 'contratServices')]
    #[ORM\JoinColumn(name: "contrat_id", referencedColumnName: "idContrat", nullable: false, onDelete: "CASCADE")]
    private ?Contrat $contrat = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'contratServices')]
    #[ORM\JoinColumn(name: "service_id", referencedColumnName: "idService", nullable: false, onDelete: "CASCADE")]
    private ?Service $service = null;

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;
        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;
        return $this;
    }
}

