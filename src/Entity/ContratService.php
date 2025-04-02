<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ContratServiceRepository;

#[ORM\Entity(repositoryClass: ContratServiceRepository::class)]
#[ORM\Table(name: 'contrat_services')]
class ContratService
{
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $contrat_id = null;

    public function getContrat_id(): ?int
    {
        return $this->contrat_id;
    }

    public function setContrat_id(int $contrat_id): self
    {
        $this->contrat_id = $contrat_id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $service_id = null;

    public function getService_id(): ?int
    {
        return $this->service_id;
    }

    public function setService_id(int $service_id): self
    {
        $this->service_id = $service_id;
        return $this;
    }

    public function getContratId(): ?int
    {
        return $this->contrat_id;
    }

    public function setContratId(int $contrat_id): static
    {
        $this->contrat_id = $contrat_id;

        return $this;
    }

    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    public function setServiceId(int $service_id): static
    {
        $this->service_id = $service_id;

        return $this;
    }

}
