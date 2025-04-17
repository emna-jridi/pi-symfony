<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\EquipeRepository;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[ORM\Table(name: 'equipe')]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $IdEmploye = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEmploye(): ?int
    {
        return $this->IdEmploye;
    }

    public function setIdEmploye(int $IdEmploye): self
    {
        $this->IdEmploye = $IdEmploye;
        return $this;
    }
}
