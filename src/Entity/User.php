<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "user")] // optional if your table is named "user"
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "ID_User")]
    private ?int $id = null;

    #[ORM\Column(name: "NomUser", length: 255)]
    private ?string $nomUser = null;

    #[ORM\Column(name: "PrenomUser", length: 255)]
    private ?string $prenomUser = null;

    #[ORM\Column(name: "DateNaissanceUser", type: "date")]
    private ?\DateTimeInterface $dateNaissanceUser = null;

    #[ORM\Column(name: "AdresseUser", length: 255)]
    private ?string $adresseUser = null;

    #[ORM\Column(name: "TelephoneUser", length: 20)]
    private ?string $telephoneUser = null;

    #[ORM\Column(name: "EmailUser", length: 255)]
    private ?string $emailUser = null;

    #[ORM\Column(name: "role", length: 255)]
    private ?string $role = null;

    #[ORM\Column(name: "Password", length: 255)]
    private ?string $password = null;

    #[ORM\Column(name: "isActive")]
    private ?bool $isActive = null;

    #[ORM\Column(name: "reset_code", nullable: true)]
    private ?string $resetCode = null;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Conge::class)]
    private Collection $conges;

    public function __construct()
    {
        $this->conges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): static
    {
        $this->nomUser = $nomUser;
        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): static
    {
        $this->prenomUser = $prenomUser;
        return $this;
    }

    public function getDateNaissanceUser(): ?\DateTimeInterface
    {
        return $this->dateNaissanceUser;
    }

    public function setDateNaissanceUser(\DateTimeInterface $dateNaissanceUser): static
    {
        $this->dateNaissanceUser = $dateNaissanceUser;
        return $this;
    }

    public function getAdresseUser(): ?string
    {
        return $this->adresseUser;
    }

    public function setAdresseUser(string $adresseUser): static
    {
        $this->adresseUser = $adresseUser;
        return $this;
    }

    public function getTelephoneUser(): ?string
    {
        return $this->telephoneUser;
    }

    public function setTelephoneUser(string $telephoneUser): static
    {
        $this->telephoneUser = $telephoneUser;
        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): static
    {
        $this->emailUser = $emailUser;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): static
    {
        $this->resetCode = $resetCode;
        return $this;
    }

    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conge $conge): static
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setIdUser($this);
        }

        return $this;
    }

    public function removeConge(Conge $conge): static
    {
        if ($this->conges->removeElement($conge)) {
            if ($conge->getIdUser() === $this) {
                $conge->setIdUser(null);
            }
        }

        return $this;
    }
}
